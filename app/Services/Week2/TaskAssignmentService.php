<?php

namespace App\Services\Week2;

use App\Models\Task;
use App\Models\Agent;

class TaskAssignmentService
{
    public function assignAutomatically(Task $task, int $userId)
    {
        // Algoritmo básico de asignación
        $availableAgents = Agent::where('status', 'active')->get();
        
        if ($availableAgents->isEmpty()) {
            return null;
        }
        
        // Por ahora, asignar al primer agente disponible
        $agent = $availableAgents->first();
        
        return [
            'task_id' => $task->id,
            'agent_id' => $agent->id,
            'assigned_by' => $userId,
            'assignment_type' => 'auto',
            'status' => 'pending'
        ];
    }
}
