@extends('layouts.simple-app')

@section('title', 'Nueva Documentación - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Nueva Documentación</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Crea un nuevo documento para la documentación de OpenClaw
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('documentation.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium mb-2">
                                Título *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
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
                                <option value="installation" {{ old('category') == 'installation' ? 'selected' : '' }}>
                                    Instalación
                                </option>
                                <option value="configuration" {{ old('category') == 'configuration' ? 'selected' : '' }}>
                                    Configuración
                                </option>
                                <option value="api" {{ old('category') == 'api' ? 'selected' : '' }}>
                                    API
                                </option>
                                <option value="agents" {{ old('category') == 'agents' ? 'selected' : '' }}>
                                    Agentes
                                </option>
                                <option value="troubleshooting" {{ old('category') == 'troubleshooting' ? 'selected' : '' }}>
                                    Solución de Problemas
                                </option>
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>
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
                                   value="{{ old('version', '1.0') }}"
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
                                      placeholder="Escribe tu documentación en Markdown...">{{ old('content') }}</textarea>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600">
                            <label for="is_active" class="ml-2 text-sm">
                                Documento activo (visible para usuarios)
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('documentation.index') }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Crear Documentación
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Simple Markdown preview (podría mejorarse con un editor completo después)
document.getElementById('content').addEventListener('input', function(e) {
    // Aquí podríamos agregar un preview en tiempo real
    // Por ahora solo contamos caracteres
    const charCount = e.target.value.length;
    console.log(`Caracteres: ${charCount}`);
});
</script>
@endpush
@endsection