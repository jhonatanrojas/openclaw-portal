@extends('layouts.simple-app')

@section('content')
<div class="page-container animate-fade-in">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-alt mr-2"></i> {{ $title }}
        </h1>
        <p class="page-subtitle">
            Gestiona los archivos de configuración y personalidad de OpenClaw desde la interfaz web.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400">
                    <i class="fas fa-file text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Total Archivos</p>
                    <p class="stats-card-value">{{ $stats['total_files'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-edit text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Editables</p>
                    <p class="stats-card-value">{{ $stats['editable_files'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-eye text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Solo Lectura</p>
                    <p class="stats-card-value">{{ $stats['readonly_files'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Tamaño Total</p>
                    <p class="stats-card-value">{{ number_format($stats['total_size'] / 1024, 2) }} KB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Files Table -->
    <div class="card">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-list mr-2"></i> Archivos Disponibles
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Selecciona un archivo para ver, editar o consultar su historial.
            </p>
        </div>

        <div class="table-container custom-scrollbar">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Archivo</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Última Modificación</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                    <tr class="transition-colors-200">
                        <td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center 
                                        {{ $file['editable'] ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' }}">
                                        <i class="fas {{ $file['editable'] ? 'fa-edit' : 'fa-eye' }}"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $file['name'] }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="badge 
                                            {{ $file['category'] === 'configuration' ? 'badge-primary' : 
                                               ($file['category'] === 'personality' ? 'badge-warning' :
                                               ($file['category'] === 'memory' ? 'badge-info' :
                                               'badge-secondary')) }}">
                                            {{ $file['category'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file['description'] }}</div>
                        </td>
                        <td class="whitespace-nowrap">
                            @if($file['exists'])
                            <span class="badge badge-success">
                                <i class="fas fa-check mr-1"></i> Existe
                            </span>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format($file['size'] / 1024, 2) }} KB
                            </div>
                            @else
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i> No existe
                            </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $file['last_modified'] ?? 'Nunca' }}
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('openclaw-files.show', $file['id']) }}" 
                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors-200">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                
                                @if($file['editable'])
                                <a href="{{ route('openclaw-files.edit', $file['id']) }}" 
                                   class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors-200">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @endif
                                
                                <a href="{{ route('openclaw-files.history', $file['id']) }}" 
                                   class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors-200">
                                    <i class="fas fa-history"></i> Historial
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Mostrando {{ count($files) }} archivos
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('openclaw-files.stats') }}" 
                       class="btn-primary">
                        <i class="fas fa-chart-bar mr-2"></i> Estadísticas
                    </a>
                    <a href="{{ route('openclaw-files.backups') }}" 
                       class="btn-secondary">
                        <i class="fas fa-save mr-2"></i> Backups
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
            </h3>
            <ul class="space-y-3">
                <li>
                    <a href="{{ route('openclaw-files.edit', 'agents') }}" 
                       class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-robot mr-2"></i> Editar AGENTS.md
                    </a>
                </li>
                <li>
                    <a href="{{ route('openclaw-files.edit', 'soul') }}" 
                       class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-heart mr-2"></i> Editar SOUL.md
                    </a>
                </li>
                <li>
                    <a href="{{ route('openclaw-files.edit', 'tools') }}" 
                       class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-tools mr-2"></i> Editar TOOLS.md
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-info-circle mr-2"></i> Información
            </h3>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <p><i class="fas fa-check-circle text-green-500 mr-2"></i> Los archivos editables permiten modificación completa</p>
                <p><i class="fas fa-eye text-blue-500 mr-2"></i> Los archivos de solo lectura solo pueden visualizarse</p>
                <p><i class="fas fa-save text-yellow-500 mr-2"></i> Se crea backup automático antes de cada edición</p>
                <p><i class="fas fa-history text-purple-500 mr-2"></i> Historial completo de cambios disponible</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-shield-alt mr-2"></i> Seguridad
            </h3>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <p><i class="fas fa-lock text-red-500 mr-2"></i> Validación de contenido antes de guardar</p>
                <p><i class="fas fa-user-shield text-green-500 mr-2"></i> Permisos verificados para cada operación</p>
                <p><i class="fas fa-file-export text-blue-500 mr-2"></i> Backups almacenados en directorio seguro</p>
                <p><i class="fas fa-clipboard-check text-purple-500 mr-2"></i> Log de auditoría de todos los cambios</p>
            </div>
        </div>
    </div>

    <!-- API Info -->
    <div class="mt-8 bg-gray-50 dark:bg-gray-900 rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-code mr-2"></i> API Disponible
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Endpoints:</h4>
                <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <li><code class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded">GET /api/openclaw-files</code> - Listar archivos</li>
                    <li><code class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded">GET /api/openclaw-files/{id}</code> - Leer archivo</li>
                    <li><code class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded">PUT /api/openclaw-files/{id}</code> - Guardar archivo</li>
                    <li><code class="bg-gray-200 dark:bg-gray-800 px-2 py-1 rounded">GET /api/openclaw-files/{id}/history</code> - Historial</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Uso:</h4>
                <pre class="bg-gray-800 text-gray-100 p-3 rounded text-xs overflow-x-auto">
<code>// Ejemplo: Leer AGENTS.md
fetch('/api/openclaw-files/agents')
  .then(response => response.json())
  .then(data => console.log(data));</code></pre>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('error') }}', 'error');
    });
</script>
@endif

<style>
    .notification {
        position: fixed;
        top: 1rem;
        right: 1rem;
        padding: 1rem;
        border-radius: 0.5rem;
        color: white;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }
    
    .notification.success {
        background-color: #10b981;
    }
    
    .notification.error {
        background-color: #ef4444;
