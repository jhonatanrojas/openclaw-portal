@extends('layouts.simple-app')

@section('title', 'Gestión de Agentes - OpenClaw Portal')

@section('content')
<div class="page-container animate-fade-in">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-robot mr-2"></i> Gestión de Agentes
        </h1>
        <p class="page-subtitle">
            Administra y monitorea los agentes de OpenClaw
        </p>
        
        <div class="flex space-x-2 mt-4">
            <a href="{{ url('/agents') }}" class="btn-primary">
                <i class="fas fa-sync-alt mr-2"></i>Actualizar
            </a>
            <button onclick="showAddAgentModal()" class="btn-success">
                <i class="fas fa-plus mr-2"></i>Nuevo Agente
            </button>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Total Agentes</p>
                    <p class="stats-card-value">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Activos</p>
                    <p class="stats-card-value">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Ocupados</p>
                    <p class="stats-card-value">{{ $stats['busy'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-cogs text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Tareas Activas</p>
                    <p class="stats-card-value">{{ $stats['active_tasks'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-filter mr-2"></i> Filtros
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tipo</label>
                <select id="filter-type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2">
                    <option value="">Todos</option>
                    <option value="backend">Backend</option>
                    <option value="frontend">Frontend</option>
                    <option value="devops">DevOps</option>
                    <option value="documentation">Documentación</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Estado</label>
                <select id="filter-status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2">
                    <option value="">Todos</option>
                    <option value="active">Activo</option>
                    <option value="inactive">Inactivo</option>
                    <option value="busy">Ocupado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Búsqueda</label>
                <input type="text" id="filter-search" placeholder="Nombre o capacidades..." 
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2">
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="btn-primary w-full">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de agentes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
            <i class="fas fa-list mr-2"></i> Agentes Disponibles
        </h3>
        
        @if(count($agents) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($agents as $agent)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $agent->name }}</h3>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="px-2 py-1 text-xs rounded {{ $agent->type === 'backend' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : ($agent->type === 'frontend' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : ($agent->type === 'devops' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($agent->type === 'documentation' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'))) }}">
                                {{ ucfirst($agent->type) }}
                            </span>
                            <span class="px-2 py-1 text-xs rounded {{ $agent->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : ($agent->status === 'busy' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300') }}">
                                {{ $agent->status === 'active' ? 'Activo' : ($agent->status === 'busy' ? 'Ocupado' : 'Inactivo') }}
                            </span>
                        </div>
                    </div>
                    <button onclick="viewAgent({{ $agent->id }})" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Tareas activas:</span>
                        <span class="font-bold text-gray-900 dark:text-gray-100">{{ $agent->task_count }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Completadas:</span>
                        <span class="font-bold text-gray-900 dark:text-gray-100">{{ $agent->completed_tasks }}</span>
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span>Progreso</span>
                            <span>{{ min($agent->completed_tasks / 50 * 100, 100) | number_format(0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($agent->completed_tasks / 50 * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <button onclick="assignTask({{ $agent->id }})" class="flex-1 btn-primary py-2 text-sm">
                        Asignar Tarea
                    </button>
                    <button onclick="viewDetails({{ $agent->id }})" class="flex-1 btn-secondary py-2 text-sm">
                        Detalles
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-robot text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-400">No hay agentes disponibles</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Crea un nuevo agente para comenzar</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal para nuevo agente -->
<div id="add-agent-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                <i class="fas fa-plus-circle mr-2"></i> Nuevo Agente
            </h3>
            <form id="add-agent-form">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" name="name" required 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Tipo</label>
                        <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2">
                            <option value="backend">Backend</option>
                            <option value="frontend">Frontend</option>
                            <option value="devops">DevOps</option>
                            <option value="documentation">Documentación</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Estado inicial</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 px-3 py-2">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                            <option value="busy">Ocupado</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" onclick="hideAddAgentModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Crear Agente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function applyFilters() {
    const type = document.getElementById('filter-type').value;
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('filter-search').value;
    
    // Por ahora solo mostramos alerta
    // En producción, esto haría una llamada AJAX o recargaría la página con parámetros
    alert(`Filtros aplicados:\nTipo: ${type || 'Todos'}\nEstado: ${status || 'Todos'}\nBúsqueda: ${search || 'Ninguna'}`);
}

function showAddAgentModal() {
    document.getElementById('add-agent-modal').classList.remove('hidden');
}

function hideAddAgentModal() {
    document.getElementById('add-agent-modal').classList.add('hidden');
}

function viewAgent(id) {
    alert(`Ver detalles del agente ${id}\n\nEn producción, esto navegaría a /agents/${id}`);
}

function assignTask(agentId) {
    alert(`Asignar tarea al agente ${agentId}\n\nEn producción, esto abriría un modal de asignación`);
}

function viewDetails(agentId) {
    alert(`Ver detalles completos del agente ${agentId}\n\nEn producción, esto navegaría a /agents/${agentId}/details`);
}

// Formulario para nuevo agente
document.getElementById('add-agent-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const agentData = Object.fromEntries(formData);
    
    alert(`Agente creado exitosamente:\n\nNombre: ${agentData.name}\nTipo: ${agentData.type}\nEstado: ${agentData.status}`);
    hideAddAgentModal();
    
    // En producción, aquí haríamos una llamada AJAX para crear el agente
    // y luego recargaríamos la lista
});
</script>
@endsection