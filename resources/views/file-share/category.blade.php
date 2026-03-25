@extends('layouts.simple-app')

@section('title', $title)

@section('content')
<div class="page-container animate-fade-in">
    <!-- Header -->
    <div class="page-header">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 {{ $categoryInfo['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : ($categoryInfo['color'] === 'green' ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : ($categoryInfo['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400' : ($categoryInfo['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400' : 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400'))) }}">
                <i class="{{ $categoryInfo['icon'] }} text-xl"></i>
            </div>
            <div>
                <h1 class="page-title">
                    {{ $categoryInfo['name'] }}
                </h1>
                <p class="page-subtitle">
                    {{ $categoryInfo['description'] }}
                </p>
            </div>
        </div>
        
        <div class="flex space-x-2 mt-4">
            <a href="{{ route('file-share.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
            <a href="{{ route('file-share.create', $category) }}" class="btn-primary">
                <i class="fas fa-upload mr-2"></i>Subir Archivo
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-file text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Total Archivos</p>
                    <p class="stats-card-value">{{ count($files) }}</p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Tamaño Total</p>
                    <p class="stats-card-value">
                        @php
                            $totalSize = 0;
                            foreach ($files as $file) {
                                $totalSize += $file['size'];
                            }
                            echo formatBytes($totalSize);
                        @endphp
                    </p>
                </div>
            </div>
        </div>

        <div class="stats-card card-hover">
            <div class="flex items-center">
                <div class="stats-card-icon bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="stats-card-label">Última Actualización</p>
                    <p class="stats-card-value">
                        @if(count($files) > 0)
                            {{ $files[0]['last_modified_relative'] }}
                        @else
                            Nunca
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Files Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                <i class="fas fa-list mr-2"></i> Archivos ({{ count($files) }})
            </h3>
            
            <div class="flex space-x-2">
                <div class="relative">
                    <input type="text" id="search-files" placeholder="Buscar archivos..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button onclick="refreshFiles()" class="btn-secondary">
                    <i class="fas fa-sync-alt mr-2"></i>Actualizar
                </button>
            </div>
        </div>

        @if(count($files) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tamaño
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Última Modificación
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($files as $file)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center mr-3 
                                    {{ $file['is_image'] ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400' : 
                                       ($file['is_text'] ? 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400' : 
                                       ($file['is_document'] ? 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400' : 
                                       'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400')) }}">
                                    <i class="{{ $file['is_image'] ? 'fas fa-image' : 
                                               ($file['is_text'] ? 'fas fa-file-alt' : 
                                               ($file['is_document'] ? 'fas fa-file-pdf' : 'fas fa-file')) }}"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $file['name'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        .{{ $file['extension'] }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $file['size_formatted'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $file['is_image'] ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 
                                   ($file['is_text'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 
                                   ($file['is_document'] ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 
                                   'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300')) }}">
                                {{ $file['mime_type'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                {{ $file['last_modified_relative'] }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ $file['url'] }}" target="_blank" class="btn-primary py-1 px-3 text-xs">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                <a href="{{ route('file-share.download', [$category, $file['name']]) }}" class="btn-secondary py-1 px-3 text-xs">
                                    <i class="fas fa-download mr-1"></i>Descargar
                                </a>
                                <button onclick="showDeleteModal('{{ $file['name'] }}')" class="btn-danger py-1 px-3 text-xs">
                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Mostrando {{ count($files) }} archivos
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-6">
                <i class="fas fa-folder-open text-3xl text-gray-400"></i>
            </div>
            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                No hay archivos en esta categoría
            </h4>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Sube el primer archivo para comenzar
            </p>
            <a href="{{ route('file-share.create', $category) }}" class="btn-primary inline-flex items-center">
                <i class="fas fa-upload mr-2"></i>Subir Primer Archivo
            </a>
        </div>
        @endif
    </div>

    <!-- Quick Info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-3">
                <i class="fas fa-info-circle mr-2"></i> Información de la Categoría
            </h4>
            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
                <li class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>Extensiones permitidas: {{ implode(', ', $categoryInfo['extensions']) }}</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-weight-hanging mr-2"></i>
                    <span>Tamaño máximo por archivo: {{ $categoryInfo['max_size_mb'] }} MB</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-folder mr-2"></i>
                    <span>Ruta: {{ $categoryInfo['path'] }}</span>
                </li>
            </ul>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-3">
                <i class="fas fa-lightbulb mr-2"></i> Consejos
            </h4>
            <ul class="space-y-2 text-sm text-green-700 dark:text-green-400">
                <li class="flex items-center">
                    <i class="fas fa-upload mr-2"></i>
                    <span>Usa nombres descriptivos para los archivos</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-compress mr-2"></i>
                    <span>Comprime imágenes grandes antes de subirlas</span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i>
                    <span>Elimina archivos antiguos que ya no necesites</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i> Confirmar Eliminación
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6" id="delete-message">
                ¿Estás seguro de que quieres eliminar este archivo?
            </p>
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentFileToDelete = '';

function refreshFiles() {
    window.location.reload();
}

function showDeleteModal(filename) {
    currentFileToDelete = filename;
    document.getElementById('delete-message').textContent = 
        `¿Estás seguro de que quieres eliminar "${filename}"? Esta acción no se puede deshacer.`;
    
    // Configurar formulario
    const form = document.getElementById('delete-form');
    form.action = `/file-share/{{ $category }}/${filename}`;
    
    document.getElementById('delete-modal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    currentFileToDelete = '';
}

// Búsqueda en tiempo real
document.getElementById('search-files').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const filename = row.querySelector('.text-sm.font-medium').textContent.toLowerCase();
        if (filename.includes(searchTerm)) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideDeleteModal();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>

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
@endsection