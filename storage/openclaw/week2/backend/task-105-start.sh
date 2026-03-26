#!/bin/bash
echo "👨‍💻 Backend Specialist - Iniciando TASK-105"
echo "=========================================="
echo "Tarea: Sistema de asignación automática de tareas"
echo "Prioridad: Alta"
echo ""

cd /var/www/openclaw-portal

echo "🎯 Objetivos:"
echo "1. Crear modelo TaskAssignment"
echo "2. Implementar algoritmo básico de asignación"
echo "3. Crear servicio TaskAssignmentService"
echo "4. Configurar migraciones"
echo ""

echo "🚀 Iniciando implementación..."
echo ""

# Crear modelo básico
cat > app/Models/TaskAssignment.php << 'MODELEOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = [
        'task_id',
        'agent_id',
        'assigned_by',
        'assignment_type',
        'priority_score',
        'match_score',
        'status'
    ];
}
MODELEOF

echo "✅ Modelo TaskAssignment creado"
echo ""

# Crear servicio básico
mkdir -p app/Services/Week2
cat > app/Services/Week2/TaskAssignmentService.php << 'SERVICEEOF'
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
SERVICEEOF

echo "✅ TaskAssignmentService creado"
echo ""

# Marcar como en progreso
echo "TASK-105:in_progress" > storage/openclaw/week2/backend/status.txt
echo "📝 Nota: Implementación básica completada"
echo "🔧 Pendiente: Migraciones, tests, algoritmo avanzado"
echo ""
echo "🐾 Backend Specialist listo para continuar..."
