<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OpenClaw Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS with Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- OpenClaw Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/openclaw-styles.css') }}">
    
    <!-- Additional Styles -->
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .prose {
            color: #374151;
        }
        
        .prose h1, .prose h2, .prose h3, .prose h4 {
            color: #111827;
            font-weight: 600;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
        }
        
        .prose p {
            margin-top: 1em;
            margin-bottom: 1em;
        }
        
        .prose ul, .prose ol {
            margin-top: 1em;
            margin-bottom: 1em;
            padding-left: 1.5em;
        }
        
        .prose code {
            background-color: #f3f4f6;
            padding: 0.2em 0.4em;
            border-radius: 0.25em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.875em;
        }
        
        .prose pre {
            background-color: #1f2937;
            color: #f9fafb;
            padding: 1em;
            border-radius: 0.5em;
            overflow-x: auto;
            margin-top: 1em;
            margin-bottom: 1em;
        }
        
        .prose pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }
        
        /* Dark mode styles */
        .dark .prose {
            color: #d1d5db;
        }
        
        .dark .prose h1, .dark .prose h2, .dark .prose h3, .dark .prose h4 {
            color: #f9fafb;
        }
        
        .dark .prose code {
            background-color: #374151;
        }
        
        .dark .prose pre {
            background-color: #111827;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Simple Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <a href="{{ url('/') }}" class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span class="ml-2 text-xl font-bold text-gray-800 dark:text-gray-200">OpenClaw Portal</span>
                        </a>

                        <!-- Navigation Links -->
                        <div class="hidden md:flex space-x-2 ml-10">
                            <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('/') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-home mr-1"></i> Inicio
                            </a>
                            <a href="{{ route('file-share.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('file-share*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-share-alt mr-1"></i> File Share
                            </a>
                            <a href="{{ route('documentation.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('documentation*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-book mr-1"></i> Documentación
                            </a>
                            <a href="{{ url('/dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('dashboard*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-chart-bar mr-1"></i> Dashboard
                            </a>
                            <a href="{{ url('/agents') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('agents*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-robot mr-1"></i> Agentes
                            </a>
                            <a href="{{ url('/tasks') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('tasks*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-tasks mr-1"></i> Tasks
                            </a>
                            <a href="https://openclaw.deploymatrix.com/devsquad" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" target="_blank">
                                <i class="fas fa-users mr-1"></i> DevSquad
                            </a>
                            <a href="{{ url('/api/status') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('api*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <i class="fas fa-code mr-1"></i> API
                            </a>
                        </div>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center">
                        <a href="{{ url('/auth/login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('/') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                    <a href="{{ route('file-share.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('file-share*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-share-alt mr-2"></i> File Share
                    </a>
                    <a href="{{ route('documentation.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('documentation*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-book mr-2"></i> Documentación
                    </a>
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('dashboard*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-chart-bar mr-2"></i> Dashboard
                    </a>
                    <a href="{{ url('/agents') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('agents*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-robot mr-2"></i> Agentes
                    </a>
                    <a href="{{ url('/tasks') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('tasks*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-tasks mr-2"></i> Tasks
                    </a>
                    <a href="https://openclaw.deploymatrix.com/devsquad" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" target="_blank">
                        <i class="fas fa-users mr-2"></i> DevSquad
                    </a>
                    <a href="{{ url('/api/status') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('api*') ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-code mr-2"></i> API
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            🐾 OpenClaw Portal v1.0.0 • 
                            <a href="https://openclaw.ai" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                openclaw.ai
                            </a>
                        </p>
                    </div>
                    <div class="flex space-x-6">
                        <a href="{{ route('documentation.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                            <i class="fas fa-book mr-1"></i> Documentación
                        </a>
                        <a href="{{ url('/api/status') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                            <i class="fas fa-code mr-1"></i> API
                        </a>
                        <a href="https://github.com/jhonatanrojas/openclaw-portal" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                            <i class="fab fa-github mr-1"></i> GitHub
                        </a>
                        <a href="mailto:support@openclaw.ai" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                            <i class="fas fa-envelope mr-1"></i> Soporte
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
</body>
</html>