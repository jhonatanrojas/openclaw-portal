<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks (web view).
     */
    public function index(Request $request)
    {
        // For web requests, return the view
        if ($request->expectsJson()) {
            return $this->apiIndex($request);
        }
        
        return view('tasks.simple-index');
    }
    
    /**
     * Display the create task form.
     */
    public function create()
    {
        $users = User::where('role', 'agent')->get();
        return view('tasks.create', compact('users'));
    }
    
    /**
     * Display the edit task form.
     */
    public function edit(Task $task)
    {
        $users = User::where('role', 'agent')->get();
        return view('tasks.edit', compact('task', 'users'));
    }
    
    /**
     * Display the specified task (web view).
     */
    public function show(Task $task)
    {
        if (request()->expectsJson()) {
            return $this->apiShow($task);
        }
        
        $task->load(['creator', 'assignee', 'assignments.agent', 'assignments.assigner']);
        return view('tasks.show', compact('task'));
    }
    
    /**
     * Display a listing of tasks (API).
     */
    public function apiIndex(Request $request)
    {
        $query = Task::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'overall_priority');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 20);
        $tasks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'filters' => $request->all(),
            'stats' => $this->getTaskStats()
        ]);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['nullable', Rule::in(['pending', 'in_progress', 'completed', 'blocked', 'cancelled'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'critical'])],
            'type' => ['nullable', Rule::in(['feature', 'bug', 'improvement', 'maintenance', 'documentation', 'devops'])],
            'category' => ['nullable', Rule::in(['backend', 'frontend', 'devops', 'documentation', 'general'])],
            'estimated_hours' => 'nullable|integer|min:1',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'tags' => 'nullable|array',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:tasks,id',
            'acceptance_criteria' => 'nullable|string',
            'complexity_score' => 'nullable|numeric|between:0,1',
            'urgency_score' => 'nullable|numeric|between:0,1',
            'importance_score' => 'nullable|numeric|between:0,1',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['created_by'] = Auth::id();

        // Calculate overall priority if scores provided
        if (isset($data['complexity_score']) && isset($data['urgency_score']) && isset($data['importance_score'])) {
            $data['overall_priority'] = (
                $data['complexity_score'] + 
                $data['urgency_score'] + 
                $data['importance_score']
            ) / 3;
        }

        $task = Task::create($data);

        // Create initial assignment if assigned_to is provided
        if (isset($data['assigned_to'])) {
            TaskAssignment::create([
                'task_id' => $task->id,
                'agent_id' => $data['assigned_to'],
                'assigned_by' => Auth::id(),
                'assignment_type' => $request->get('assignment_type', 'manual'),
                'priority_score' => $data['priority'] ?? 0.5,
                'match_score' => $this->calculateMatchScore($task, $data['assigned_to']),
                'status' => 'pending'
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task->load(['creator', 'assignee', 'assignments'])
            ], 201);
        }
        
        return redirect()->route('tasks.show', $task)->with('success', 'Task created successfully');
    }

    /**
     * Display the specified task (API).
     */
    public function apiShow(Task $task)
    {
        return response()->json([
            'success' => true,
            'data' => $task->load([
                'creator', 
                'assignee', 
                'assignments.agent',
                'assignments.assigner'
            ])
        ]);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['pending', 'in_progress', 'completed', 'blocked', 'cancelled'])],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high', 'critical'])],
            'estimated_hours' => 'nullable|integer|min:1',
            'actual_hours' => 'nullable|integer|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'exists:tasks,id',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'complexity_score' => 'nullable|numeric|between:0,1',
            'urgency_score' => 'nullable|numeric|between:0,1',
            'importance_score' => 'nullable|numeric|between:0,1',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Handle status transitions
        if (isset($data['status'])) {
            $this->handleStatusTransition($task, $data['status']);
        }

        // Handle assignment changes
        if (isset($data['assigned_to']) && $data['assigned_to'] != $task->assigned_to) {
            $this->handleAssignmentChange($task, $data['assigned_to']);
        }

        // Recalculate overall priority if scores changed
        if (isset($data['complexity_score']) || isset($data['urgency_score']) || isset($data['importance_score'])) {
            $complexity = $data['complexity_score'] ?? $task->complexity_score;
            $urgency = $data['urgency_score'] ?? $task->urgency_score;
            $importance = $data['importance_score'] ?? $task->importance_score;
            $data['overall_priority'] = ($complexity + $urgency + $importance) / 3;
        }

        $task->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $task->fresh(['creator', 'assignee', 'assignments'])
            ]);
        }
        
        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task)
    {
        // Check if task has dependencies
        $hasDependents = Task::whereJsonContains('dependencies', $task->id)->exists();
        
        if ($hasDependents) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete task that other tasks depend on'
                ], 400);
            }
            
            return back()->with('error', 'Cannot delete task that other tasks depend on');
        }

        $task->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        }
        
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }

    /**
     * Get task statistics.
     */
    public function stats()
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $this->getTaskStats()
            ]);
        }
        
        return view('tasks.stats', ['stats' => $this->getTaskStats()]);
    }

    /**
     * Assign task to agent automatically.
     */
    public function autoAssign(Task $task)
    {
        $bestAgent = $this->findBestAgentForTask($task);
        
        if (!$bestAgent) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No suitable agent found for this task'
                ], 404);
            }
            
            return back()->with('error', 'No suitable agent found for this task');
        }

        $assignment = TaskAssignment::create([
            'task_id' => $task->id,
            'agent_id' => $bestAgent['agent']->id,
            'assigned_by' => Auth::id(),
            'assignment_type' => 'auto',
            'priority_score' => $this->calculatePriorityScore($task),
            'match_score' => $bestAgent['score'],
            'status' => 'pending'
        ]);

        $task->update(['assigned_to' => $bestAgent['agent']->id]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task auto-assigned successfully',
                'data' => [
                    'task' => $task->fresh(['assignee']),
                    'assignment' => $assignment,
                    'match_score' => $bestAgent['score']
                ]
            ]);
        }
        
        return redirect()->route('tasks.show', $task)->with('success', 'Task auto-assigned to ' . $bestAgent['agent']->name);
    }

    /**
     * Get tasks for current user.
     */
    public function myTasks(Request $request)
    {
        $user = Auth::user();
        
        $query = Task::where('assigned_to', $user->id)
            ->orWhere('created_by', $user->id);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort by due date and priority
        $query->orderBy('due_date', 'asc')
              ->orderBy('overall_priority', 'desc');

        $perPage = $request->get('per_page', 20);
        $tasks = $query->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tasks,
                'user_stats' => $this->getUserTaskStats($user->id)
            ]);
        }
        
        return view('tasks.my-tasks', [
            'tasks' => $tasks,
            'user_stats' => $this->getUserTaskStats($user->id)
        ]);
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

        $score = 0.5; // Base score

        // Match category with agent skills
        $agentSkills = $agent->skills ?? [];
        if (in_array($task->category, $agentSkills)) {
            $score += 0.2;
        }

        // Match type with agent preferences
        $agentPreferences = $agent->task_preferences ?? [];
        if (in_array($task->type, $agentPreferences)) {
            $score += 0.15;
        }

        // Consider agent workload
        $agentWorkload = Task::where('assigned_to', $agentId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        
        if ($agentWorkload < 5) {
            $score += 0.1;
        } elseif ($agentWorkload > 10) {
            $score -= 0.1;
        }

        // Consider agent performance
        $completedTasks = Task::where('assigned_to', $agentId)
            ->where('status', 'completed')
            ->count();
        
        $totalTasks = Task::where('assigned_to', $agentId)->count();
        
        if ($totalTasks > 0) {
            $completionRate = $completedTasks / $totalTasks;
            $score += ($completionRate - 0.5) * 0.1;
        }

        return min(1.0, max(0.0, $score));
    }

    /**
     * Find best agent for task.
     */
    private function findBestAgentForTask(Task $task): ?array
    {
        $agents = User::where('role', 'agent')->get();
        
        $bestAgent = null;
        $bestScore = 0;

        foreach ($agents as $agent) {
            $score = $this->calculateMatchScore($task, $agent->id);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestAgent = $agent;
            }
        }

        if ($bestScore < 0.3) {
            return null;
        }

        return [
            'agent' => $bestAgent,
            'score' => $bestScore
        ];
    }

    /**
     * Calculate priority score for task.
     */
    private function calculatePriorityScore(Task $task): float
    {
        $score = 0.5;

        // Priority mapping
        $priorityMap = [
            'low' => 0.2,
            'medium' => 0.5,
            'high' => 0.8,
            'critical' => 1.0
        ];

        if (isset($priorityMap[$task->priority])) {
            $score = $priorityMap[$task->priority];
        }

        // Adjust for due date
        if ($task->due_date) {
            $daysUntilDue = now()->diffInDays($task->due_date, false);
            
            if ($daysUntilDue < 0) {
                $score = 1.0; // Overdue
            } elseif ($daysUntilDue < 3) {
                $score = max($score, 0.9);
            } elseif ($daysUntilDue < 7) {
                $score = max($score, 0.7);
            }
        }

        return $score;
    }

    /**
     * Handle status transition logic.
     */
    private function handleStatusTransition(Task $task, string $newStatus): void
    {
        $now = now();

        switch ($newStatus) {
            case 'in_progress':
                if (!$task->started_at) {
                    $task->started_at = $now;
                }
                break;
                
            case 'completed':
                if (!$task->completed_at) {
                    $task->completed_at = $now;
                }
                // Update assignments
                $task->assignments()->where('status', '!=', 'completed')->update([
                    'status' => 'completed',
                    'completed_at' => $now
                ]);
                break;
                
            case 'blocked':
                // Check if actually blocked by dependencies
                if (!$task->isBlocked()) {
                    throw new \Exception('Task cannot be marked as blocked without incomplete dependencies');
                }
                break;
        }
    }

    /**
     * Handle assignment change.
