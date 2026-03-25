@extends('layouts.app')

@section('title', 'Gestión de Agentes - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Gestión de Agentes</h2>
                    <div class="flex space-x-2">
                        <button onclick="loadAgents()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-sync-alt mr-2"></i>Actualizar
                        </button>
                        <button onclick="showAddAgentModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i>Nuevo Agente
                        </button>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div id="stats-container" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Cargando estadísticas...</p>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Tipo</label>
                            <select id="filter-type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                <option value="">Todos</option>
                                <option value="backend">Backend</option>
                                <option value="frontend">Frontend</option>
                                <option value="devops">DevOps</option>
                                <option value="documentation">Documentación</option>
                                <option value="general">General</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Estado</label>
                            <select id="filter-status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                <option value="">Todos</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="busy">Ocupado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Búsqueda</label>
                            <input type="text" id="filter-search" placeholder="Nombre o capacidades..." 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                        </div>
                        <div class="flex items-end">
                            <button onclick="applyFilters()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                <i class="fas fa-filter mr-2"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lista de agentes -->
                <div id="agents-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Cargando agentes...</p>
                    </div>
                </div>

                <!-- Paginación -->
                <div id="pagination" class="mt-6 flex justify-center">
                    <!-- Paginación se cargará aquí -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para nuevo agente -->
<div id="add-agent-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-4">Nuevo Agente</h3>
                <form id="add-agent-form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nombre</label>
                            <input type="text" name="name" required 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                   placeholder="Ej: Security Specialist">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Tipo</label>
                            <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                <option value="backend">Backend</option>
                                <option value="frontend">Frontend</option>
                                <option value="devops">DevOps</option>
                                <option value="documentation">Documentación</option>
                                <option value="general">General</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Estado inicial</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="busy">Ocupado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Capacidades (separadas por comas)</label>
                            <input type="text" name="capabilities" 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                   placeholder="laravel, api, testing">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="hideAddAgentModal()" 
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-lg">Cancelar</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de detalles de agente -->
<div id="agent-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold" id="agent-details-title">Detalles del Agente</h3>
                    <button onclick="hideAgentDetailsModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="agent-details-content">
                    <!-- Los detalles se cargarán aquí -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Estado global
let agents = [];
let filteredAgents = [];
let currentFilters = {};

// Funciones de utilidad
function getTypeColor(type) {
    const colors = {
        backend: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        frontend: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        devops: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        documentation: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        general: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
    };
    return colors[type] || colors.general;
}

function getStatusColor(status) {
    const colors = {
        active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        inactive: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        busy: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
    };
    return colors[status] || colors.inactive;
}

function getStatusText(status) {
    const texts = {
        active: 'Activo',
        inactive: 'Inactivo',
        busy: 'Ocupado'
    };
    return texts[status] || status;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Cargar agentes desde la API
async function loadAgents() {
    const container = document.getElementById('agents-container');
    const statsContainer = document.getElementById('stats-container');
    
    container.innerHTML = '<div class="text-center py-8 text-gray-500 col-span-3"><i class="fas fa-spinner fa-spin text-2xl mb-2"></i><p>Cargando agentes...</p></div>';
    statsContainer.innerHTML = '<div class="text-center py-8 text-gray-500 col-span-4"><i class="fas fa-spinner fa-spin text-2xl mb-2"></i><p>Cargando estadísticas...</p></div>';
    
    try {
        const response = await fetch('/api-agents.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            agents = data.data.agents;
            filteredAgents = [...agents];
            
            // Actualizar estadísticas
            updateStats(data.data.stats);
            
            // Aplicar filtros si existen
            if (Object.keys(currentFilters).length > 0) {
                applyFilters();
            } else {
                renderAgents();
            }
        } else {
            showError('Error al cargar agentes: ' + data.message);
        }
    } catch (error) {
        showError('Error de conexión: ' + error.message);
    }
}

// Actualizar estadísticas
function updateStats(stats) {
    const statsContainer = document.getElementById('stats-container');
    
    statsContainer.innerHTML = `
        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <div class="text-blue-600 dark:text-blue-300 font-semibold">Total Agentes</div>
            <div class="text-2xl font-bold">${stats.total}</div>
        </div>
        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
            <div class="text-green-600 dark:text-green-300 font-semibold">Activos</div>
            <div class="text-2xl font-bold">${stats.active}</div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
            <div class="text-yellow-600 dark:text-yellow-300 font-semibold">Ocupados</div>
            <div class="text-2xl font-bold">${stats.busy}</div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
            <div class="text-purple-600 dark:text-purple-300 font-semibold">Tareas Activas</div>
            <div class="text-2xl font-bold">${stats.active_tasks}</div>
        </div>
    `;
}

// Renderizar agentes
function renderAgents() {
    const container = document.getElementById('agents-container');
    
    if (filteredAgents.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-500 col-span-3"><i class="fas fa-search text-2xl mb-2"></i><p>No se encontraron agentes con los filtros aplicados</p></div>';
        return;
    }
    
    container.innerHTML = filteredAgents.map(agent => `
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-lg">${agent.name}</h3>
                    <div class="flex items-center mt-1">
                        <span class="px-2 py-1 text-xs rounded ${getTypeColor(agent.type)}">
                            ${agent.type.charAt(0).toUpperCase() + agent.type.slice(1)}
                        </span>
                        <span class="ml-2 px-2 py-1 text-xs rounded ${getStatusColor(agent.status)}">
                            ${getStatusText(agent.status)}
                        </span>
                    </div>
                </div>
                <button onclick="showAgentDetails(${agent.id})" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span>Tareas activas:</span>
                    <span class="font-bold">${agent.task_count}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span>Completadas:</span>
                    <span class="font-bold">${agent.completed_tasks}</span>
                </div>
                <div class="mt-3">
                    <div class="text-xs text-gray-500 mb-1">Eficiencia</div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: ${(agent.efficiency || 0.5) * 100}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1 text-right">${Math.round((agent.efficiency || 0.5) * 100)}%</div>
                </div>
            </div>
            <div class="mt-4 flex space-x-2">
                <button onclick="assignTaskToAgent(${agent.id})" class="flex-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Asignar
                </button>
                <button onclick="showAgentDetails(${agent.id})" class="flex-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                    Detalles
                </button>
            </div>
        </div>
    `).join('');
}

// Aplicar filtros
function applyFilters() {
    const typeFilter = document.getElementById('filter-type').value;
    const statusFilter = document.getElementById('filter-status').value;
    const searchFilter = document.getElementById('filter-search').value.toLowerCase();
    
    currentFilters = { type: typeFilter, status: statusFilter, search: searchFilter };
    
    filteredAgents = agents.filter(agent => {
        // Filtrar por tipo
        if (typeFilter && agent.type !== typeFilter) return false;
        
        // Filtrar por estado
        if (statusFilter && agent.status !== statusFilter) return false;
        
        // Filtrar por búsqueda
        if (searchFilter) {
            const searchInName = agent.name.toLowerCase().includes(searchFilter);
            const searchInCapabilities = Array.isArray(agent.capabilities) && 
                agent.capabilities.some(cap => cap.toLowerCase().includes(searchFilter));
            
            if (!searchInName && !searchInCapabilities) return false;
        }
        
        return true;
    });
    
    renderAgents();
}

// Mostrar detalles del agente
async function showAgentDetails(agentId) {
    try {
        const response = await fetch(`/api-agents.php/${agentId}`);
        const data = await response.json();
        
        if (data.status === 'success') {
            const agent = data.data;
            
            document.getElementById('agent-details-title').textContent = `Detalles: ${agent.name}`;
            
            const capabilities = Array.isArray(agent.capabilities) 
                ? agent.capabilities.map(cap => `<span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm mr-1 mb-1">${cap}</span>`).join('')
                : 'No especificadas';
            
            document.getElementById('agent-details-content').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1">${agent.id}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tipo</label>
                            <p class="mt-1"><span class="px-2 py-1 text-xs rounded ${getTypeColor(agent.type)}">${agent.type}</span></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Estado</label>
                            <p class="mt-1"><span class="px-2 py-1 text-xs rounded ${getStatusColor(agent.status)}">${getStatusText(agent.status)}</span></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tareas Activas</label>
                            <p class="mt-1 text-xl font-bold">${agent.task_count}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tareas Completadas</label>
                            <p class="mt-1 text-xl font-bold">${agent.completed_tasks}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Eficiencia</label>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-green-600 h-3 rounded-full" style="width: ${(agent.efficiency || 0.5) * 100}%"></div>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span>0%</span>
                                <span class="font-bold">${Math.round((agent.efficiency || 0.5) * 100)}%</span>
                                <span>100%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacidades</label>
                        <div class="mt-2 flex flex-wrap">
                            ${capabilities}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Última Actividad</label>
                        <p class="mt-1">${formatDate(agent.last_active)}</p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-2">
                    <button onclick="assignTaskToAgent(${agent.id})" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Asignar Tarea
                    </button>
                    <button onclick="hideAgentDetailsModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-lg">
                        Cerrar
                    </button>
                </div>
            `;
            
            document.getElementById('agent-details-modal').classList.remove('hidden');
        } else {
            showError('Error al cargar detalles del agente: ' + data.message);
        }
    } catch (error) {
        showError('Error de conexión: ' + error.message);
    }
}

// Asignar tarea a agente
function assignTaskToAgent(agentId) {
    const agent = agents.find(a => a.id === agentId);
    if (agent) {
        alert(`Asignar tarea al agente: ${agent.name}\n\nEsta funcionalidad se implementará en la siguiente fase.`);
    }
}

// Crear nuevo agente
async function createNewAgent(agentData) {
    try {
        const response = await fetch('/api-agents.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(agentData)
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showSuccess('Agente creado exitosamente');
            hideAddAgentModal();
            loadAgents(); // Recargar la lista
        } else {
            showError('Error al crear agente: ' + data.message);
        }
    } catch (error) {
        showError('Error de conexión: ' + error.message);
    }
}

// Mostrar/ocultar modales
function showAddAgentModal() {
    document.getElementById('add-agent-modal').classList.remove('hidden');
}

function hideAddAgentModal() {
    document.getElementById('add-agent-modal').classList.add('hidden');
    document.getElementById('add-agent-form').reset();
}

function hideAgentDetailsModal() {
    document.getElementById('agent-details-modal').classList.add('hidden');
}

// Mostrar mensajes
function showSuccess(message) {
    alert('✅ ' + message);
}

function showError(message) {
    alert('❌ ' + message);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Cargar agentes al inicio
    loadAgents();
    
    // Formulario para nuevo agente
    document.getElementById('add-agent-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const agentData = {
            name: formData.get('name'),
            type: formData.get('type'),
            status: formData.get('status'),
            capabilities: formData.get('capabilities') ? formData.get('capabilities').split(',').map(cap => cap.trim()) : []
        };
        
        createNewAgent(agentData);
    });
});

// Exponer funciones globalmente
window.loadAgents = loadAgents;
window.applyFilters = applyFilters;
window.showAddAgentModal = showAddAgentModal;
window.hideAddAgentModal = hideAddAgentModal;
window.showAgentDetails = showAgentDetails;
window.hideAgentDetailsModal = hideAgentDetailsModal;
window.assignTaskToAgent = assignTaskToAgent;
</script>
@endsection
