@extends('layouts.simple-app')

@section('title', 'Buscar: ' . $searchTerm . ' - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold">Resultados de búsqueda</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                {{ $resultsCount }} resultados para "{{ $searchTerm }}"
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('documentation.index') }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i>Volver a documentación
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="mb-6">
                    <form action="{{ route('documentation.search') }}" method="GET" class="flex space-x-2">
                        <div class="flex-1">
                            <input type="text" 
                                   name="q" 
                                   value="{{ $searchTerm }}"
                                   placeholder="Buscar en documentación..." 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                    </form>
                </div>

                <!-- Search Results -->
                @if($documentations->count() > 0)
                <div class="space-y-4">
                    @foreach($documentations as $doc)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <a href="{{ route('documentation.show', $doc->slug) }}" 
                                   class="text-lg font-semibold hover:text-blue-600 dark:hover:text-blue-400">
                                    {{ $doc->title }}
                                </a>
                                <div class="flex items-center mt-2 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded capitalize">
                                        {{ $doc->category }}
                                    </span>
                                    <span>v{{ $doc->version }}</span>
                                    <span>{{ $doc->created_at->format('d/m/Y') }}</span>
                                    @if(!$doc->is_active)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 rounded">
                                        Inactivo
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Highlight search term in content -->
                                @php
                                    $content = strip_tags($doc->content);
                                    $position = stripos($content, $searchTerm);
                                    $snippet = '';
                                    
                                    if ($position !== false) {
                                        $start = max(0, $position - 50);
                                        $end = min(strlen($content), $position + strlen($searchTerm) + 100);
                                        $snippet = substr($content, $start, $end - $start);
                                        
                                        if ($start > 0) {
                                            $snippet = '...' . $snippet;
                                        }
                                        if ($end < strlen($content)) {
                                            $snippet = $snippet . '...';
                                        }
                                        
                                        // Highlight the search term
                                        $snippet = preg_replace(
                                            "/(" . preg_quote($searchTerm, '/') . ")/i",
                                            '<mark class="bg-yellow-200 dark:bg-yellow-800">$1</mark>',
                                            $snippet
                                        );
                                    } else {
                                        $snippet = Str::limit($content, 150);
                                    }
                                @endphp
                                
                                <div class="mt-2 text-gray-700 dark:text-gray-300">
                                    {!! $snippet !!}
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('documentation.edit', $doc->slug) }}" 
                                   class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $documentations->links() }}
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No se encontraron resultados</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        No hay documentación que coincida con "{{ $searchTerm }}".
                    </p>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sugerencias:</p>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 list-disc list-inside">
                            <li>Verifica la ortografía de tu búsqueda</li>
                            <li>Usa términos más generales</li>
                            <li>Prueba con sinónimos</li>
                            <li>Busca en categorías específicas</li>
                        </ul>
                        <div class="pt-4">
                            <a href="{{ route('documentation.create') }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Crear nuevo documento
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
mark {
    padding: 0.1em 0.2em;
    border-radius: 2px;
}
</style>
@endpush
@endsection