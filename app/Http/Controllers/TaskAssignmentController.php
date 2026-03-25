<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskAssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index(Request $request)
    {
        $query = TaskAssignment::query()->with(['task', 'agent', 'assigner']);

        // Apply filters
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->has('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'assigned_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 20);
        $assignments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'stats' => $this->getAssignmentStats()
        ]);
    }

    /**
     * Store a new assignment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'agent_id' => 'required|exists:users,id',
            'assignment_type' => ['required', Rule::in(['auto', 'manual', 'reassigned'])],
            'priority_score' => 'nullable|numeric|between:0,1',
            'match_score' => 'nullable|numeric|between:0,1',
            'status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected', 'completed'])]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['assigned_by'] = Auth::id();
        $data['assigned_at'] = now();

        // Get task for score calculations
        $task = Task::find($data['task_id']);

        // Calculate scores if not provided
        if (!isset($data['priority_score'])) {
            $data['priority_score'] = $this->calculatePriorityScore($task);
        }

        if (!isset($data['match_score'])) {
            $data['match_score'] = $this->calculateMatchScore($task, $data['agent_id']);
        }

        // Check for existing pending assignment for same task and agent
        $existingAssignment = TaskAssignment::where('task_id', $data['task_id'])
            ->where('agent_id', $data['agent_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingAssignment) {
            return response()->json([
                'success' => false,
                'message' => 'Pending assignment already exists for this task and agent'
            ], 409);
        }

        $assignment = TaskAssignment::create($data);

        // Update task assignment if this is the first or only assignment
        $this->updateTaskAssignment($task, $data['agent_id']);

        return response()->json([
            'success' => true,
            'message' => 'Assignment created successfully',
            'data' => $assignment->load(['task', 'agent', 'assigner'])
        ], 201);
    }

    /**
     * Display the specified assignment.
     */
    public function show(TaskAssignment $assignment)
    {
        return response()->json([
            'success' => true,
            'data' => $assignment->load(['task', 'agent', 'assigner'])
        ]);
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, TaskAssignment $assignment)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['sometimes', Rule::in(['pending', 'accepted', 'rejected', 'completed'])],
            'priority_score' => 'nullable|numeric|between:0,1',
            'match_score' => 'nullable|numeric|between:0,1',
            'completed_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle status transitions
        if (isset($data['status']) && $data['status'] !== $assignment->status) {
            $this->handleAssignmentStatusTransition($assignment, $data['status']);
        }

        $assignment->update($data);

        // Update task status if assignment is completed
        if ($assignment->status === 'completed') {
            $this->updateTaskFromAssignment($assignment);
        }

        return response()->json([
            'success' => true,
            'message' => 'Assignment updated successfully',
            'data' => $assignment->fresh(['task', 'agent', 'assigner'])
        ]);
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(TaskAssignment $assignment)
    {
        // Don't allow deletion of completed assignments
        if ($assignment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete completed assignment'
            ], 400);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Assignment deleted successfully'
        ]);
    }

    /**
     * Accept an assignment.
     */
    public function accept(TaskAssignment $assignment)
    {
        if ($assignment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending assignments can be accepted'
            ], 400);
        }

        // Reject other pending assignments for the same task
        TaskAssignment::where('task_id', $assignment->task_id)
            ->where('id', '!=', $assignment->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'completed_at' => now()
            ]);

        $assignment->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        // Update task status
        $task = $assignment->task;
        $task->update([
            'assigned_to' => $assignment->agent_id,
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assignment accepted successfully',
            'data' => $assignment->fresh(['task', 'agent', 'assigner'])
        ]);
    }

    /**
     * Reject an assignment.
     */
    public function reject(TaskAssignment $assignment)
    {
        if ($assignment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending assignments can be rejected'
            ], 400);
        }

        $assignment->update([
            'status' => 'rejected',
            'completed_at' => now()
        ]);

        // Try to auto-assign to another agent
        $task = $assignment->task;
        $this->tryAutoReassign($task, $assignment->agent_id);

        return response()->json([
            'success' => true,
            'message' => 'Assignment rejected successfully',
            'data' => $assignment->fresh(['task', 'agent', 'assigner'])
        ]);
    }

    /**
     * Complete an assignment.
     */
    public function complete(TaskAssignment $assignment)
    {
        if (!in_array($assignment->status, ['accepted', 'in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only accepted or in-progress assignments can be completed'
            ], 400);
        }

        $assignment->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Update task status
        $this->updateTaskFromAssignment($assignment);

        return response()->json([
            'success' => true,
            'message' => 'Assignment completed successfully',
            'data' => $assignment->fresh(['task', 'agent', 'assigner'])
        ]);
    }

    /**
     * Get assignments for current user.
     */
    public function myAssignments(Request $request)
    {
        $user = Auth::user();
        
        $query = TaskAssignment::where('agent_id', $user->id)
            ->with(['task', 'assigner']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        // Sort by priority and assignment date
        $query->orderBy('priority_score', 'desc')
              ->orderBy('assigned_at', 'desc');

        $perPage = $request->get('per_page', 20);
        $assignments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $assignments,
            'user_stats' => $this->getUserAssignmentStats($user->id)
        ]);
    }

    /**
     * Get assignment statistics.
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getAssignmentStats()
        ]);
    }

    /**
     * Calculate priority score for task.
     */
    private function calculatePriorityScore(Task $task): float
    {
        $score = 0.5;

        $priorityMap = [
            'low' => 0.2,
            'medium' => 0.5,
            'high' => 0.8,
            'critical' => 1.0
        ];

        if (isset($priorityMap[$task->priority])) {
            $score = $priorityMap[$task->priority];
        }

        if ($task->due_date) {
            $daysUntilDue = now()->diffInDays($task->due_date, false);
            
            if ($daysUntilDue < 0) {
                $score = 1.0;
            } elseif ($daysUntilDue < 3) {
                $score = max($score, 0.9);
            } elseif ($daysUntilDue < 7) {
                $score = max($score, 0.7);
            }
        }

        return $score;
    }

    /**
     * Calculate match score between task and agent.
     */
    private function calculateMatchScore(Task $task, $agentId): float
    {
        $agent = User::find($agentId);
        
        if (!$agent) {
            return 0.5;
        }

        $score = 0.5;

        $agentSkills = $agent->skills ?? [];
        if (in_array($task->category, $agentSkills)) {
            $score += 0.2;
        }

        $agentPreferences = $agent->task_preferences ?? [];
        if (in_array($task->type, $agentPreferences)) {
            $score += 0.15;
        }

        $agentWorkload = TaskAssignment::where('agent_id', $agentId)
            ->whereNotIn('status', ['completed', 'rejected'])
            ->count();
        
        if ($agentWorkload < 5) {
            $score += 0.1;
        } elseif ($agentWorkload > 10) {
            $score -= 0.1;
        }

        $completedAssignments = TaskAssignment::where('agent_id', $agentId)
            ->where('status', 'completed')
            ->count();
        
        $totalAssignments = TaskAssignment::where('agent_id', $agentId)->count();
        
        if ($totalAssignments > 0) {
            $completionRate = $completedAssignments / $totalAssignments;
            $score += ($completionRate - 0.5) * 0.1;
        }

        return min(1.0, max(0.0, $score));
    }

    /**
     * Update task assignment.
     */
    private function updateTaskAssignment(Task $task, $agentId): void
    {
        // Only update if task doesn't have an assignee or has lower priority
        if (!$task->assigned_to || $task->priority === 'low') {
            $task->update(['assigned_to' => $agentId]);
        }
    }

    /**
     * Handle assignment status transition.
     */
    private function handleAssignmentStatusTransition(TaskAssignment $assignment, string $newStatus): void
    {
        $now = now();

        switch ($newStatus) {
            case 'accepted':
                $assignment->accepted_at = $now;
                break;
                
            case 'completed':
            case 'rejected':
                $assignment->completed_at = $now;
                break;
        }
    }

    /**
     * Update task from assignment.
     */
    private function updateTaskFromAssignment(TaskAssignment $assignment): void
    {
        $task = $assignment->task;

        // Check if all assignments for this task are completed
        $incompleteAssignments = TaskAssignment::where('task_id', $task->id)
            ->whereNotIn('status', ['completed', 'rejected'])
            ->exists();

        if (!$incompleteAssignments) {
            $task->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }
    }

    /**
     * Try to auto-reassign task after rejection.
     */
    private function tryAutoReassign(Task $task, $rejectedAgentId): void
    {
        // Find other agents excluding the one who rejected
        $otherAgents = User::where('role', 'agent')
            ->where('id', '!=', $rejectedAgentId)
            ->get();

        $bestAgent = null;
        $bestScore = 0;

        foreach ($otherAgents as $agent) {
            $score = $this->calculateMatchScore($task, $agent->id);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestAgent = $agent;
            }
        }

        if ($bestScore >= 0.3 && $bestAgent) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'agent_id' => $bestAgent->id,
                'assigned_by' => Auth::id(),
                'assignment_type' => 'auto',
                'priority_score' => $this->calculatePriorityScore($task),
                'match_score' => $bestScore,
                'status' => 'pending'
            ]);
        }
    }

    /**
     * Get assignment statistics.
     */
    private function getAssignmentStats(): array
    {
        return [
            'total' => TaskAssignment::count(),
            'pending' => TaskAssignment::where('status', 'pending')->count(),
            'accepted' => TaskAssignment::where('status', 'accepted')->count(),
            'completed' => TaskAssignment::where('status', 'completed')->count(),
            'rejected' => TaskAssignment::where('status', 'rejected')->count(),
            'by_type' => TaskAssignment::groupBy('assignment_type')->selectRaw('assignment_type, count(*) as count')->get()->pluck('count', 'assignment_type'),
            'by_status' => TaskAssignment::groupBy('status')->selectRaw('status, count(*) as count')->get()->pluck('count', 'status'),
            'avg_match_score' => TaskAssignment::avg('match_score'),
            'avg_priority_score' => TaskAssignment::avg('priority_score'),
        ];
    }

    /**
     * Get user assignment statistics.
     */
    private function getUserAssignmentStats($userId): array
    {
        return [
            'total' => TaskAssignment::where('agent_id', $userId)->count(),
            'pending' => TaskAssignment::where('agent_id', $userId)->where('status', 'pending')->count(),
            'accepted' => TaskAssignment::where('agent_id', $userId)->where('status', 'accepted')->count(),
            'completed' => TaskAssignment::where('agent_id', $userId)->where('status', 'completed')->count(),
            'rejected' => TaskAssignment::where('agent_id', $userId)->where('status', 'rejected')->count(),
            'avg_match_score' => TaskAssignment::where('agent_id', $userId)->avg('match_score'),
            'completion_rate' => TaskAssignment::where('agent_id', $userId)->where('status', 'completed')->count() / 
                                max(1, TaskAssignment::where('agent_id', $userId)->whereIn('status', ['completed', 'rejected'])->count()),
        ];
    }
}