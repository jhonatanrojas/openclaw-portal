@extends('layouts.simple-app')

@section('title', 'Documentación: ' . $categoryName . ' - OpenClaw Portal')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold">Documentación: {{ $categoryName }}</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                {{ $documentations->total() }} documentos en esta categoría
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('documentation.create') }}?category={{ $category }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Nuevo en {{ $categoryName }}
                            </a>
                            <a href="{{ route('documentation.index') }}" 
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i>Todas las categorías
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Category Description -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <h3 class="font-semibold mb-2">Sobre {{ $categoryName }}</h3>
                    @if($category == 'installation')
                    <p>Guías de instalación, requisitos del sistema, y primeros pasos con OpenClaw.</p>
                    @elseif($category == 'configuration')
                    <p>Configuración del sistema, variables de entorno, y personalización de OpenClaw.</p>
                    @elseif($category == 'api')
                    <p>Documentación de la API REST, endpoints, autenticación, y ejemplos de uso.</p>
                    @elseif($category == 'agents')
                    <p>Gestión de agentes, creación, configuración, y monitoreo de sesiones.</p>
                    @elseif($category == 'troubleshooting')
                    <p>Solución de problemas comunes, errores frecuentes, y preguntas frecuentes.</p>
                    @else
                    <p>Documentación general sobre OpenClaw y su ecosistema.</p>
                    @endif
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
                    <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No hay documentación en esta categoría</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Aún no se ha creado documentación en la categoría "{{ $categoryName }}".
                    </p>
                    <a href="{{ route('documentation.create') }}?category={{ $category }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Crear primer documento en {{ $categoryName }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection