@extends('layouts.simple-app')

@section('title', 'Dashboard - OpenClaw Portal')

@section('content')
<div class="page-container animate-fade-in">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-bar mr-2"></i> Dashboard
        </h1>
        <p class="page-subtitle">
            Panel de control y métricas del sistema OpenClaw
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Agentes Activos</p>
                    <p class="stats-card-value" id="active-agents">0</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Archivos Compartidos</p>
                    <p class="stats-card-value" id="shared-files">0</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Tareas Pendientes</p>
                    <p class="stats-card-value" id="pending-tasks">0</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-server text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">API Status</p>
                    <p class="stats-card-value" id="api-status">Checking...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Activity Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-chart-line mr-2"></i> Actividad Reciente
                </h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" onclick="updateChart('day')">Día</button>
                    <button class="px-3 py-1 text-sm rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" onclick="updateChart('week')">Semana</button>
                    <button class="px-3 py-1 text-sm rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" onclick="updateChart('month')">Mes</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- File Types Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                <i class="fas fa-file-pie-chart mr-2"></i> Tipos de Archivos
            </h3>
            <div class="h-64">
                <canvas id="fileTypesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
            <i class="fas fa-history mr-2"></i> Actividad Reciente
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="activity-table">
                    <!-- Activity rows will be loaded here -->
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Cargando actividad...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
            <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/file-share" class="quick-action-card bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <div class="quick-action-icon bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-upload"></i>
                </div>
                <h4 class="quick-action-title">Subir Archivo</h4>
                <p class="quick-action-desc">Comparte un nuevo archivo en el File Share</p>
            </a>

            <a href="/documentation/create" class="quick-action-card bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                <div class="quick-action-icon bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-400">
                    <i class="fas fa-book"></i>
                </div>
                <h4 class="quick-action-title">Nueva Documentación</h4>
                <p class="quick-action-desc">Crea nueva documentación para el equipo</p>
            </a>

            <a href="/api/status" class="quick-action-card bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800">
                <div class="quick-action-icon bg-purple-100 dark:bg-purple-800 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-server"></i>
                </div>
                <h4 class="quick-action-title">Ver Estado API</h4>
                <p class="quick-action-desc">Revisa el estado de todos los servicios</p>
            </a>
        </div>
    </div>
</div>

<!-- JavaScript for Dashboard -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Charts
    let activityChart, fileTypesChart;

    // Initialize charts
    function initCharts() {
        // Activity Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Actividad',
                    data: [12, 19, 8, 15, 22, 18, 24],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        // File Types Chart
        const fileTypesCtx = document.getElementById('fileTypesChart').getContext('2d');
        fileTypesChart = new Chart(fileTypesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Screenshots', 'Documentación', 'Reportes', 'Tareas', 'Otros'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#3b82f6', // Blue
                        '#10b981', // Green
                        '#f59e0b', // Amber
                        '#8b5cf6', // Purple
                        '#6b7280'  // Gray
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    // Update chart based on timeframe
    function updateChart(timeframe) {
        // Simulate data update
        const data = {
            day: [5, 8, 12, 9, 15, 18, 22],
            week: [45, 52, 48, 61, 55, 58, 62],
            month: [180, 195, 210, 205, 220, 215, 230]
        };

        activityChart.data.datasets[0].data = data[timeframe];
        activityChart.update();
    }

    // Load dashboard data
    async function loadDashboardData() {
        try {
            // Load API status
            const apiResponse = await fetch('/api/status');
            if (apiResponse.ok) {
                const apiData = await apiResponse.json();
                document.getElementById('api-status').textContent = apiData.status === 'online' ? '✅ Online' : '❌ Offline';
                document.getElementById('api-status').className = apiData.status === 'online' ? 
                    'stats-card-value text-green-600' : 'stats-card-value text-red-600';
            }

            // Load agents count
            const agentsResponse = await fetch('/api/agents');
            if (agentsResponse.ok) {
                const agentsData = await agentsResponse.json();
                document.getElementById('active-agents').textContent = agentsData.count || 0;
            }

            // Load file share stats
            const filesResponse = await fetch('/api/file-share/stats');
            if (filesResponse.ok) {
                const filesData = await filesResponse.json();
                document.getElementById('shared-files').textContent = filesData.total_files || 0;
            }

            // Load activity data
            loadActivityData();

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            document.getElementById('api-status').textContent = '❌ Error';
            document.getElementById('api-status').className = 'stats-card-value text-red-600';
        }
    }

    // Load activity data
    async function loadActivityData() {
        try {
            const response = await fetch('/api/activity');
            if (response.ok) {
                const activities = await response.json();
                const table = document.getElementById('activity-table');
                
                if (activities.length > 0) {
                    table.innerHTML = activities.map(activity => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full ${getActivityBadgeClass(activity.type)}">
                                    ${getActivityIcon(activity.type)} ${activity.type}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">${activity.description}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">${activity.user}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ${new Date(activity.timestamp).toLocaleString()}
                            </td>
                        </tr>
                    `).join('');
                } else {
                    table.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No hay actividad reciente
                            </td>
                        </tr>
                    `;
                }
            }
        } catch (error) {
            console.error('Error loading activity data:', error);
        }
    }

    // Helper functions
    function getActivityBadgeClass(type) {
        const classes = {
            'file_upload': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'documentation': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'login': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'error': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'info': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
        return classes[type] || classes.info;
    }

    function getActivityIcon(type) {
        const icons = {
            'file_upload': '📤',
            'documentation': '📚',
            'login': '🔐',
            'error': '⚠️',
            'info': 'ℹ️'
        };
        return icons[type] || icons.info;
    }

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        loadDashboardData();
        
        // Refresh data every 30 seconds
        setInterval(loadDashboardData, 30000);
        
        // Update current time
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString();
        }
        updateTime();
        setInterval(updateTime, 1000);
    });
</script>
@endsection