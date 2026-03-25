@extends('layouts.simple-app')

@section('content')
<div class="page-container animate-fade-in">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-share-alt mr-2"></i> {{ $title }}
        </h1>
        <p class="page-subtitle">
            Archivos que OpenClaw genera y comparte contigo: tareas, documentación, screenshots, reportes y más.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-folder text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Categorías</p>
                    <p class="stats-card-value">{{ $stats['total_categories'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-file text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Archivos Totales</p>
                    <p class="stats-card-value">{{ $stats['total_files'] }}</p>
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
                    <p class="stats-card-value">{{ $stats['total_size_formatted'] }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                    <i class="fas fa-sync-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Actualizado</p>
                    <p class="stats-card-value">Ahora</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            <i class="fas fa-folder-open mr-2"></i> Categorías de Archivos
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('file-share.category', $category['id']) }}" 
               class="card card-hover border-l-4 border-{{ $category['color'] }}-500 dark:border-{{ $category['color'] }}-400">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg bg-{{ $category['color'] }}-100 dark:bg-{{ $category['color'] }}-900 flex items-center justify-center">
                                <i class="{{ $category['icon'] }} text-{{ $category['color'] }}-600 dark:text-{{ $category['color'] }}-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $category['name'] }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $category['description'] }}
                            </p>
                            <div class="flex items-center mt-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-file mr-1"></i>
                                    {{ $category['file_count'] }} archivos
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-database mr-1"></i>
                                    {{ $category['total_size'] > 0 ? formatBytes($category['total_size']) : 'Vacío' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('file-share.category', 'screenshots') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-camera text-blue-600 dark:text-blue-400"></i>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Ver Screenshots
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Capturas recientes
                </p>
            </a>

            <a href="{{ route('file-share.category', 'tasks') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-tasks text-green-600 dark:text-green-400"></i>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Ver Tareas
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Listas y seguimiento
                </p>
            </a>

            <a href="{{ route('file-share.category', 'reports') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center mb-3 mx-auto">
                    <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400"></i>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Ver Reportes
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Análisis y métricas
                </p>
            </a>
        </div>
    </div>

    <!-- API Info -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-code mr-2"></i> API Disponible
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Endpoints:</h4>
                <ul class="space-y-2">
                    <li>
                        <code class="code-inline">GET /api/file-share/categories</code>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Listar categorías</span>
                    </li>
                    <li>
                        <code class="code-inline">GET /api/file-share/{category}/files</code>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Archivos por categoría</span>
                    </li>
                    <li>
                        <code class="code-inline">POST /api/file-share/{category}/upload</code>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Subir archivo</span>
                    </li>
                    <li>
                        <code class="code-inline">DELETE /api/file-share/{category}/{filename}</code>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Eliminar archivo</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Ejemplo de uso:</h4>
                <pre class="code-block">
<code>// Listar categorías
fetch('/api/file-share/categories')
  .then(response => response.json())
  .then(data => console.log(data));

// Subir archivo
const formData = new FormData();
formData.append('file', fileInput.files[0]);
formData.append('description', 'Mi archivo');

fetch('/api/file-share/screenshots/upload', {
  method: 'POST',
  body: formData
});</code></pre>
            </div>
        </div>
    </div>
</div>

@php
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
@endphp

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
@endsection