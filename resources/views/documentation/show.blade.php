@extends('layouts.simple-app')

@section('title', $documentation->title . ' - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold">{{ $documentation->title }}</h2>
                            <div class="flex items-center mt-2 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded capitalize">
                                    {{ $documentation->category }}
                                </span>
                                <span>v{{ $documentation->version }}</span>
                                <span>Creado: {{ $documentation->created_at->format('d/m/Y H:i') }}</span>
                                <span>Actualizado: {{ $documentation->updated_at->format('d/m/Y H:i') }}</span>
                                @if(!$documentation->is_active)
                                <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded">
                                    Inactivo
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('documentation.edit', $documentation->slug) }}" 
                               class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </a>
                            <a href="{{ route('documentation.index') }}" 
                               class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="prose dark:prose-invert max-w-none">
                    {!! Str::markdown($documentation->content) !!}
                </div>

                <!-- Related Documentation -->
                @if($related->count() > 0)
                <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4">Documentación Relacionada</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($related as $doc)
                        <a href="{{ route('documentation.show', $doc->slug) }}" 
                           class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <h4 class="font-semibold">{{ $doc->title }}</h4>
                            <div class="flex items-center mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                    {{ $doc->category }}
                                </span>
                                <span class="ml-2">v{{ $doc->version }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between">
                        <div>
                            @if($documentation->created_at != $documentation->updated_at)
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Última actualización: {{ $documentation->updated_at->diffForHumans() }}
                            </p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('documentation.destroy', $documentation->slug) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta documentación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.prose {
    color: inherit;
}
.prose h1, .prose h2, .prose h3, .prose h4 {
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: 600;
}
.prose h1 { font-size: 2em; }
.prose h2 { font-size: 1.5em; }
.prose h3 { font-size: 1.25em; }
.prose p {
    margin-bottom: 1em;
    line-height: 1.6;
}
.prose ul, .prose ol {
    margin-bottom: 1em;
    padding-left: 1.5em;
}
.prose code {
    background-color: rgba(0,0,0,0.05);
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.9em;
}
.prose pre {
    background-color: rgba(0,0,0,0.05);
    padding: 1em;
    border-radius: 5px;
    overflow-x: auto;
    margin-bottom: 1em;
}
.prose pre code {
    background: none;
    padding: 0;
}
.prose blockquote {
    border-left: 4px solid #ddd;
    padding-left: 1em;
    margin-left: 0;
    font-style: italic;
}
.prose table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1em;
}
.prose th, .prose td {
    border: 1px solid #ddd;
    padding: 0.5em;
    text-align: left;
}
.prose th {
    background-color: #f5f5f5;
}

/* Dark mode styles */
.dark .prose code {
    background-color: rgba(255,255,255,0.1);
}
.dark .prose pre {
    background-color: rgba(255,255,255,0.05);
}
.dark .prose blockquote {
    border-left-color: #444;
}
.dark .prose th, .dark .prose td {
    border-color: #444;
}
.dark .prose th {
    background-color: rgba(255,255,255,0.05);
}
</style>
@endpush
@endsection