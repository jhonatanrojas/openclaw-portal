@extends('layouts.simple-app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-eye mr-2"></i> {{ $title }}
                </h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                    <span class="flex items-center">
                        <i class="fas fa-file-alt mr-1"></i> {{ $fileInfo['name'] }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas {{ $fileInfo['editable'] ? 'fa-edit text-green-500' : 'fa-lock text-blue-500' }} mr-1"></i>
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
                @if($fileInfo['editable'])
                <a href="{{ route('openclaw-files.edit', $fileId) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                @endif
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
                        <strong>Nota:</strong> Este archivo no existe en el sistema.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- File Content -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <!-- Content Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        <i class="fas fa-file-code mr-2"></i> Contenido del Archivo
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Vista de solo lectura del archivo {{ $fileInfo['name'] }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="copyToClipboard()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <i class="fas fa-copy mr-2"></i> Copiar
                    </button>
                    <button type="button" onclick="downloadFile()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i> Descargar
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 py-5 sm:p-6">
            @if($exists)
            <!-- File Stats -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    <i class="fas fa-chart-bar mr-2"></i> Estadísticas del Archivo
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($size) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bytes</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($size / 1024, 2) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">KB</p>
                    </div>
                    <div class="text-center">
                        @php
                            $lines = substr_count($content, "\n") + 1;
                        @endphp
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($lines) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Líneas</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format(strlen($content)) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Caracteres</p>
                    </div>
                </div>
            </div>

            <!-- Content Display Options -->
            <div class="mb-4 flex space-x-4">
                <div class="flex items-center">
                    <input type="radio" id="viewRaw" name="viewMode" value="raw" checked 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                           onchange="changeViewMode('raw')">
                    <label for="viewRaw" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-code mr-1"></i> Código
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="viewFormatted" name="viewMode" value="formatted" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                           onchange="changeViewMode('formatted')">
                    <label for="viewFormatted" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-file-alt mr-1"></i> Formateado
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="viewHex" name="viewMode" value="hex" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                           onchange="changeViewMode('hex')">
                    <label for="viewHex" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        <i class="fas fa-memory mr-1"></i> Hexadecimal
                    </label>
                </div>
            </div>

            <!-- Content Display -->
            <div class="relative">
                <!-- Raw View (Default) -->
                <div id="rawView" class="view-mode">
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm font-mono whitespace-pre-wrap">{{ $content }}</pre>
                </div>

                <!-- Formatted View -->
                <div id="formattedView" class="view-mode hidden">
                    <div class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        @php
                            // Simple markdown rendering
                            $formatted = $content;
                            $formatted = preg_replace('/^# (.*$)/m', '<h1 class="text-3xl font-bold mb-4">$1</h1>', $formatted);
                            $formatted = preg_replace('/^## (.*$)/m', '<h2 class="text-2xl font-bold mb-3">$1</h2>', $formatted);
                            $formatted = preg_replace('/^### (.*$)/m', '<h3 class="text-xl font-bold mb-2">$1</h3>', $formatted);
                            $formatted = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formatted);
                            $formatted = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $formatted);
                            $formatted = preg_replace('/`(.*?)`/', '<code class="bg-gray-200 dark:bg-gray-700 px-1 rounded">$1</code>', $formatted);
                            $formatted = preg_replace('/```([\s\S]*?)```/', '<pre class="bg-gray-800 text-gray-100 p-3 rounded overflow-x-auto my-2"><code>$1</code></pre>', $formatted);
                            $formatted = preg_replace('/- (.*$)/m', '<li>$1</li>', $formatted);
                            $formatted = preg_replace('/(<li>[\s\S]*?<\/li>)/', '<ul class="list-disc pl-5 my-2">$1</ul>', $formatted);
                            $formatted = preg_replace('/^\s*$/m', '<br>', $formatted);
                        @endphp
                        {!! $formatted !!}
                    </div>
                </div>

                <!-- Hex View -->
                <div id="hexView" class="view-mode hidden">
                    <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
                        <div class="font-mono text-sm">
                            @php
                                $hex = bin2hex($content);
                                $hexPairs = str_split($hex, 2);
                                $lines = array_chunk($hexPairs, 16);
                            @endphp
                            @foreach($lines as $lineNumber => $line)
                            <div class="flex">
                                <div class="text-gray-500 w-16 flex-shrink-0">
                                    {{ str_pad(dechex($lineNumber * 16), 8, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="flex-1">
                                    @foreach($line as $pair)
                                    <span class="mx-1">{{ strtoupper($pair) }}</span>
                                    @endforeach
                                    @for($i = count($line); $i < 16; $i++)
                                    <span class="mx-1 text-gray-700">··</span>
                                    @endfor
                                </div>
                                <div class="ml-4 text-gray-400">
                                    @foreach($line as $pair)
                                    @php
                                        $char = chr(hexdec($pair));
                                        $char = preg_match('/[\x20-\x7E]/', $char) ? $char : '.';
                                    @endphp
                                    {{ $char }}
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Copy Notification -->
                <div id="copyNotification" class="hidden absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-md text-sm">
                    <i class="fas fa-check mr-1"></i> Copiado al portapapeles
                </div>
            </div>
            @else
            <!-- File doesn't exist -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Archivo no encontrado</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    El archivo {{ $fileInfo['name'] }} no existe en el sistema.
                </p>
                @if($fileInfo['editable'])
                <div class="mt-6">
                    <a href="{{ route('openclaw-files.edit', $fileId) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i> Crear Archivo
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- File Info Footer -->
        <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Ruta completa:</p>
                    <p class="font-mono text-gray-900 dark:text-gray-100 truncate" title="{{ $fileInfo['path'] }}">
                        {{ $fileInfo['path'] }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Categoría:</p>
                    <p class="text-gray-900 dark:text-gray-100">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $fileInfo['category'] === 'configuration' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : 
                               ($fileInfo['category'] === 'personality' ? 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300' :
                               ($fileInfo['category'] === 'memory' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300' :
                               'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300')) }}">
                            {{ $fileInfo['category'] }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Última modificación:</p>
                    <p class="text-gray-900 dark:text-gray-100">{{ $lastModified ?? 'Nunca' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- File Properties -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-cogs mr-2"></i> Propiedades del Archivo
            </h3>
            <dl class="grid grid-cols-1 gap-4">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Nombre:</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $fileInfo['name'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Descripción:</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $fileInfo['description'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Permisos:</dt>
                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $fileInfo['editable'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                            {{ $fileInfo['editable'] ? 'Lectura/Escritura' : 'Solo lectura