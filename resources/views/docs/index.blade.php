@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">📚 Documentación</h2>
                    <a href="{{ route('docs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Nueva Documentación
                    </a>
                </div>
                
                <!-- Filtros por categoría -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('docs.index') }}" class="px-4 py-2 rounded {{ request('category') ? 'bg-gray-200' : 'bg-blue-500 text-white' }}">
                            Todas
                        </a>
                        @foreach(['installation', 'configuration', 'api', 'agents', 'troubleshooting', 'general'] as $category)
                        <a href="{{ route('docs.index', ['category' => $category]) }}" 
                           class="px-4 py-2 rounded {{ request('category') == $category ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                            {{ ucfirst($category) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Barra de búsqueda -->
                <div class="mb-6">
                    <form action="{{ route('docs.index') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Buscar documentación..." 
                               class="flex-grow px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg">
                            🔍 Buscar
                        </button>
                    </form>
                </div>
                
                <!-- Lista de documentación -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($docs as $doc)
                    <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 text-xs rounded 
                                {{ $doc->category == 'installation' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $doc->category == 'configuration' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $doc->category == 'api' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $doc->category == 'agents' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $doc->category == 'troubleshooting' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $doc->category == 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($doc->category) }}
                            </span>
                            @if($doc->is_active)
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                Activo
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">
                                Inactivo
                            </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $doc->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($doc->content), 150) }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">v{{ $doc->version }}</span>
                            <div class="flex space-x-2">
                                <a href="{{ route('docs.show', $doc) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                    Ver
                                </a>
                                <a href="{{ route('docs.edit', $doc) }}" class="text-yellow-500 hover:text-yellow-700 text-sm font-medium">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg mb-2">No hay documentación disponible.</p>
                        <p class="text-gray-400 text-sm mb-4">Comienza creando la primera entrada de documentación.</p>
                        <a href="{{ route('docs.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Crear primera documentación
                        </a>
                    </div>
                    @endforelse
                </div>
                
                @if($docs->hasPages())
                <div class="mt-8">
                    {{ $docs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
