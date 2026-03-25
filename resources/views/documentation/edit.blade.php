@extends('layouts.simple-app')

@section('title', 'Editar: ' . $documentation->title . ' - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Editar Documentación</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Actualiza la documentación: {{ $documentation->title }}
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('documentation.update', $documentation->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium mb-2">
                                Título *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $documentation->title) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 @error('title') border-red-500 @enderror"
                                   placeholder="Ej: Instalación de OpenClaw en Ubuntu">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium mb-2">
                                Categoría *
                            </label>
                            <select id="category" 
                                    name="category" 
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 @error('category') border-red-500 @enderror">
                                <option value="">Seleccionar categoría</option>
                                <option value="installation" {{ (old('category', $documentation->category) == 'installation') ? 'selected' : '' }}>
                                    Instalación
                                </option>
                                <option value="configuration" {{ (old('category', $documentation->category) == 'configuration') ? 'selected' : '' }}>
                                    Configuración
                                </option>
                                <option value="api" {{ (old('category', $documentation->category) == 'api') ? 'selected' : '' }}>
                                    API
                                </option>
                                <option value="agents" {{ (old('category', $documentation->category) == 'agents') ? 'selected' : '' }}>
                                    Agentes
                                </option>
                                <option value="troubleshooting" {{ (old('category', $documentation->category) == 'troubleshooting') ? 'selected' : '' }}>
                                    Solución de Problemas
                                </option>
                                <option value="general" {{ (old('category', $documentation->category) == 'general') ? 'selected' : '' }}>
                                    General
                                </option>
                            </select>
                            @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Version -->
                        <div>
                            <label for="version" class="block text-sm font-medium mb-2">
                                Versión
                            </label>
                            <input type="text" 
                                   id="version" 
                                   name="version" 
                                   value="{{ old('version', $documentation->version) }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 @error('version') border-red-500 @enderror"
                                   placeholder="1.0">
                            @error('version')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium mb-2">
                                Contenido *
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="15"
                                      required
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 font-mono text-sm @error('content') border-red-500 @enderror"
                                      placeholder="Escribe tu documentación en Markdown...">{{ old('content', $documentation->content) }}</textarea>
                            @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <p>Usa <a href="https://www.markdownguide.org/cheat-sheet/" target="_blank" class="text-blue-600 hover:text-blue-800">Markdown</a> para formatear tu documentación.</p>
                                <p class="mt-1">Ejemplos: <code># Título</code>, <code>## Subtítulo</code>, <code>**negrita**</code>, <code>`código`</code></p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $documentation->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600">
                            <label for="is_active" class="ml-2 text-sm">
                                Documento activo (visible para usuarios)
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <a href="{{ route('documentation.show', $documentation->slug) }}" 
                                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                <a href="{{ route('documentation.index') }}" 
                                   class="ml-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Cancelar
                                </a>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-save mr-1"></i>Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simple character counter
const contentTextarea = document.getElementById('content');
const charCountDisplay = document.createElement('div');
charCountDisplay.className = 'text-sm text-gray-600 dark:text-gray-400 mt-1';
contentTextarea.parentNode.appendChild(charCountDisplay);

function updateCharCount() {
    const charCount = contentTextarea.value.length;
    const wordCount = contentTextarea.value.trim().split(/\s+/).filter(word => word.length > 0).length;
    charCountDisplay.textContent = `${charCount} caracteres, ${wordCount} palabras`;
}

contentTextarea.addEventListener('input', updateCharCount);
updateCharCount(); // Initial count
</script>
@endpush
@endsection