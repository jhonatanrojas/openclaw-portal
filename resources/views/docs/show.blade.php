@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $doc->category == 'installation' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $doc->category == 'configuration' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $doc->category == 'api' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $doc->category == 'agents' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $doc->category == 'troubleshooting' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $doc->category == 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($doc->category) }}
                        </span>
                        <span class="ml-2 px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">
                            v{{ $doc->version }}
                        </span>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('docs.edit', $doc) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-sm">
                            ✏️ Editar
                        </a>
                        <a href="{{ route('docs.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                            ← Volver
                        </a>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $doc->title }}</h1>
                
                <div class="prose max-w-none mb-8">
                    {!! Str::markdown($doc->content) !!}
                </div>
                
                <div class="border-t pt-6 mt-8">
                    <div class="flex justify-between text-sm text-gray-500">
                        <div>
                            <strong>📅 Creado:</strong> {{ $doc->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>🔄 Actualizado:</strong> {{ $doc->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
