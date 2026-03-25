@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ isset($doc) ? '✏️ Editar Documentación' : '📝 Nueva Documentación' }}
                </h2>
                
                <form action="{{ isset($doc) ? route('docs.update', $doc) : route('docs.store') }}" method="POST">
                    @csrf
                    @if(isset($doc))
                        @method('PUT')
                    @endif
                    
                    <div class="space-y-6">
                        <!-- Título -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título *
                            </label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', $doc->title ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Categoría -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría *
                            </label>
                            <select name="category" id="category" 
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Seleccionar categoría...</option>
                                <option value="installation" {{ old('category', $doc->category ?? '') == 'installation' ? 'selected' : '' }}>Instalación</option>
                                <option value="configuration" {{ old('category', $doc->category ?? '') == 'configuration' ? 'selected' : '' }}>Configuración</option>
                                <option value="api" {{ old('category', $doc->category ?? '') == 'api' ? 'selected' : '' }}>API</option>
                                <option value="agents" {{ old('category', $doc->category ?? '') == 'agents' ? 'selected' : '' }}>Agentes</option>
                                <option value="troubleshooting" {{ old('category', $doc->category ?? '') == 'troubleshooting' ? 'selected' : '' }}>Solución de Problemas</option>
                                <option value="general" {{ old('category', $doc->category ?? '') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Versión -->
                        <div>
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                                Versión
                            </label>
                            <input type="text" name="version" id="version" 
                                   value="{{ old('version', $doc->version ?? '1.0') }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="1.0">
                        </div>
                        
                        <!-- Contenido (Markdown) -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Contenido (Markdown) *
                            </label>
                            <textarea name="content" id="content" rows="15"
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                      required>{{ old('content', $doc->content ?? '') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 text-sm text-gray-500">
                                💡 Soporta <a href="https://www.markdownguide.org/basic-syntax/" target="_blank" class="text-blue-500 hover:text-blue-700">Markdown</a>
                            </div>
                        </div>
                        
                        <!-- Estado -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                       {{ old('is_active', $doc->is_active ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Documentación activa</span>
                            </label>
                        </div>
                        
                        <!-- Botones -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('docs.index') }}" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg font-medium">
                                {{ isset($doc) ? 'Actualizar' : 'Crear' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SimpleMDE Editor -->
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var simplemde = new SimpleMDE({ 
            element: document.getElementById("content"),
            spellChecker: false,
            toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "guide"]
        });
    });
</script>
@endpush
@endsection
