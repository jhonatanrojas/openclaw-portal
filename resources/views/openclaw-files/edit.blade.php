@extends('layouts.simple-app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-edit mr-2"></i> {{ $title }}
                </h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                    <span class="flex items-center">
                        <i class="fas fa-file-alt mr-1"></i> {{ $fileInfo['name'] }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas {{ $fileInfo['editable'] ? 'fa-edit text-green-500' : 'fa-eye text-blue-500' }} mr-1"></i>
                        {{ $fileInfo['editable'] ? 'Editable' : 'Solo lectura' }}
                    </span>
                    @if($exists)
                    <span class="flex items-center">
                        <i class="fas fa-database mr-1"></i> {{ number_format($size / 1024, 2) }} KB
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-clock mr-1"></i> {{ $lastModified }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('openclaw-files.show', $fileId) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <i class="fas fa-eye mr-2"></i> Vista Previa
                </a>
                <a href="{{ route('openclaw-files.history', $fileId) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i> Historial
                </a>
                <a href="{{ route('openclaw-files.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
        
        @if(!$exists)
        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 dark:text-yellow-300"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        <strong>Nota:</strong> Este archivo no existe en el sistema. Se creará cuando guardes los cambios.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Editor Form -->
    <form action="{{ route('openclaw-files.update', $fileId) }}" method="POST" id="fileForm">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <!-- Editor Header -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            <i class="fas fa-code mr-2"></i> Editor de Contenido
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Edita el contenido del archivo. Se creará un backup automático antes de guardar.
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center">
                            <input id="create_backup" name="create_backup" type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   checked>
                            <label for="create_backup" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Crear backup
                            </label>
                        </div>
                        <button type="button" onclick="validateContent()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                            <i class="fas fa-check-circle mr-2"></i> Validar
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>

            <!-- Editor Area -->
            <div class="px-4 py-5 sm:p-6">
                <!-- Validation Messages -->
                <div id="validationMessages" class="mb-4 hidden"></div>

                <!-- Editor -->
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contenido del archivo <span class="text-gray-500">({{ $fileInfo['description'] }})</span>
                    </label>
                    <div class="relative">
                        <textarea id="content" name="content" rows="25" 
                                  class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:text-gray-100 font-mono text-sm"
                                  placeholder="Escribe el contenido del archivo aquí...">{{ old('content', $content) }}</textarea>
                        
                        <!-- Editor Stats -->
                        <div class="absolute bottom-2 right-2 bg-gray-800 text-gray-100 text-xs px-2 py-1 rounded opacity-75">
                            <span id="lineCount">0</span> líneas | <span id="charCount">0</span> caracteres
                        </div>
                    </div>
                    @error('content')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Toggle -->
                <div class="mb-4">
                    <button type="button" onclick="togglePreview()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i class="fas fa-eye mr-2"></i> <span id="previewToggleText">Mostrar Vista Previa</span>
                    </button>
                </div>

                <!-- Preview Area (Hidden by default) -->
                <div id="previewArea" class="hidden mb-4">
                    <div class="border border-gray-300 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-900">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">
                            <i class="fas fa-search mr-2"></i> Vista Previa
                        </h4>
                        <div id="previewContent" class="prose dark:prose-invert max-w-none">
                            <!-- Preview will be rendered here -->
                        </div>
                    </div>
                </div>

                <!-- File Info -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-info-circle mr-2"></i> Información del Archivo
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Ruta:</p>
                            <p class="font-mono text-gray-900 dark:text-gray-100 truncate" title="{{ $fileInfo['path'] }}">
                                {{ $fileInfo['path'] }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Categoría:</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $fileInfo['category'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Estado:</p>
                            <p class="text-gray-900 dark:text-gray-100">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $exists ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                    {{ $exists ? 'Existe' : 'No existe' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Tamaño:</p>
                            <p class="text-gray-900 dark:text-gray-100">{{ number_format($size / 1024, 2) }} KB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-exclamation-circle mr-1"></i> Los cambios se guardarán en el archivo original.
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="resetForm()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <i class="fas fa-undo mr-2"></i> Restablecer
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Quick Help -->
    <div class="mt-8 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 dark:border-blue-600 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-400 dark:text-blue-300"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                    Consejos para editar {{ $fileInfo['name'] }}
                </h4>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <ul class="list-disc pl-5 space-y-1">
                        @if($fileId === 'agents')
                        <li>Mantén la estructura de secciones con encabezados <code>##</code></li>
                        <li>Usa listas para organizar información de agentes</li>
                        <li>Incluye ejemplos de comandos en bloques de código</li>
                        @elseif($fileId === 'soul')
                        <li>Define claramente la personalidad y comportamiento</li>
                        <li>Incluye ejemplos de interacciones esperadas</li>
                        <li>Especifica límites y restricciones importantes</li>
                        @elseif($fileId === 'tools')
                        <li>Organiza por categorías de herramientas</li>
                        <li>Incluye configuraciones específicas y ejemplos</li>
                        <li>Documenta dependencias y requisitos</li>
                        @endif
                        <li>Guarda frecuentemente para evitar pérdida de datos</li>
                        <li>Revisa la vista previa antes de guardar cambios importantes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update character and line counts
function updateStats() {
    const content = document.getElementById('content').value;
    const lineCount = content.split('\n').length;
    const charCount = content.length;
    
    document.getElementById('lineCount').textContent = lineCount;
    document.getElementById('charCount').textContent = charCount;
}

// Toggle preview
function togglePreview() {
    const previewArea = document.getElementById('previewArea');
    const previewToggleText = document.getElementById('previewToggleText');
    const previewContent = document.getElementById('previewContent');
    
    if (previewArea.classList.contains('hidden')) {
        // Show preview
        const content = document.getElementById('content').value;
        
        // Simple markdown preview (basic implementation)
        let html = content
            .replace(/^# (.*$)/gm, '<h1 class="text-2xl font-bold mt-4 mb-2">$1</h1>')
            .replace(/^## (.*$)/gm, '<h2 class="text-xl font-bold mt-3 mb-2">$1</h2>')
            .replace(/^### (.*$)/gm, '<h3 class="text-lg font-bold mt-2 mb-1">$1</h3>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$1</code>')
            .replace(/```([\s\S]*?)```/g, '<pre class="bg-gray-800 text-gray-100 p-3 rounded overflow-x-auto my-2"><code>$1</code></pre>')
            .replace(/- (.*$)/gm, '<li>$1</li>')
            .replace(/^\s*$/gm, '<br>');
        
        // Wrap lists
        html = html.replace(/<li>[\s\S]*?<\/li>/g, function(match) {
            return '<ul class="list-disc pl-5 my-2">' + match + '</ul>';
        });
        
        previewContent.innerHTML = html || '<p class="text-gray-500 dark:text-gray-400 italic">No hay contenido para previsualizar.</p>';
        previewArea.classList.remove('hidden');
        previewToggleText.textContent = 'Ocultar Vista Previa';
    } else {
        // Hide preview
        previewArea.classList.add('hidden');
        previewToggleText.textContent = 'Mostrar Vista Previa';
    }
}

// Validate content via API
function validateContent() {
    const content = document.getElementById('content').value;
    const fileId = '{{ $fileId }}';
    const messagesDiv = document.getElementById('validationMessages');
    
    fetch(`/api/openclaw-files/${fileId}/validate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        messagesDiv.innerHTML = '';
        messagesDiv.classList.remove('hidden');
        
        if (data.success) {
            let html = '<div class="rounded-md bg-green-50 dark:bg-green-900 p-4">';
            html += '<div class="flex"><div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400 dark:text-green-300"></i></div>';
            html += '<div class="ml-3"><h3 class="text-sm font-medium text-green-800 dark:text-green-200">Validación exitosa</h3>';
            
            if (data.data.warnings && data.data.warnings.length > 0) {
                html += '<div class="mt-2 text-sm text-green-700 dark:text-green-300"><ul class="list-disc pl-5 space-y-1">';
                data.data.warnings.forEach(warning => {
                    html += `<li>${warning}</li>`;
                });
                html += '</ul></div>';
            } else {
                html += '<div class="mt-2 text-sm text-green-700 dark:text-green-300">El contenido es válido y listo para guardar.</div>';
            }
            
