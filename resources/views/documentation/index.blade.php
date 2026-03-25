@extends('layouts.simple-app')

@section('title', 'Documentación - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Documentación</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Manuales, guías y referencia para OpenClaw
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('documentation.create') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Nuevo Documento
                        </a>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                    @foreach($categoryStats as $category => $count)
                    <a href="{{ route('documentation.by-category', $category) }}" 
                       class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-600">
                        <div class="text-2xl font-bold">{{ $count }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 capitalize">
                            {{ $category }}
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Search and Filters -->
                <div class="mb-6">
                    <form action="{{ route('documentation.search') }}" method="GET" class="flex space-x-2">
                        <div class="flex-1">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Buscar en documentación..." 
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800"
                                   value="{{ request('search', '') }}">
                        </div>
                        <button type="submit" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <div class="flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('documentation.index') }}" 
                           class="px-3 py-1 rounded-full {{ !request('category') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            Todos
                        </a>
                        @foreach(['installation', 'configuration', 'api', 'agents', 'troubleshooting', 'general'] as $cat)
                        <a href="{{ route('documentation.index', ['category' => $cat]) }}" 
                           class="px-3 py-1 rounded-full {{ request('category') == $cat ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($cat) }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Documentation List -->
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
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
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
                                <p class="mt-2 text-gray-700 dark:text-gray-300 line-clamp-2">
                                    {{ Str::limit(strip_tags($doc->content), 150) }}
                                </p>
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
                    <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No hay documentación</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Aún no se ha creado ninguna documentación.
                    </p>
                    <a href="{{ route('documentation.create') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Crear primer documento
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection