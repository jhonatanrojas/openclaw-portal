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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
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
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span class="ml-2 text-xl font-bold text-gray-800">OpenClaw Portal</span>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    🏠 Dashboard
                                </x-nav-link>
                                <x-nav-link :href="route('docs.index')" :active="request()->routeIs('docs.*')">
                                    📚 Documentación
                                </x-nav-link>
                                <x-nav-link href="/status.html" target="_blank">
                                    📊 Estado
                                </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        👤 Perfil
                                    </x-dropdown-link>
                                    <x-dropdown-link href="/openclaw" target="_blank">
                                        🌐 OpenClaw File Share
                                    </x-dropdown-link>
                                    <x-dropdown-link href="https://github.com/jhonatanrojas/openclaw-portal" target="_blank">
                                        💻 GitHub
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            🚪 Cerrar Sesión
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            🏠 Dashboard
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('docs.index')" :active="request()->routeIs('docs.*')">
                            📚 Documentación
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="/status.html" target="_blank">
                            📊 Estado
                        </x-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                👤 Perfil
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="/openclaw" target="_blank">
                                🌐 OpenClaw File Share
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="https://github.com/jhonatanrojas/openclaw-portal" target="_blank">
                                💻 GitHub
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    🚪 Cerrar Sesión
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <p class="text-sm text-gray-500">
                                🐾 OpenClaw Portal v1.0.0 • 
                                <a href="https://openclaw.ai" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    openclaw.ai
                                </a>
                            </p>
                        </div>
                        <div class="flex space-x-6">
                            <a href="/docs" class="text-sm text-gray-500 hover:text-gray-700">
                                Documentación
                            </a>
                            <a href="/api" class="text-sm text-gray-500 hover:text-gray-700">
                                API
                            </a>
                            <a href="https://github.com/jhonatanrojas/openclaw-portal" target="_blank" class="text-sm text-gray-500 hover:text-gray-700">
                                GitHub
                            </a>
                            <a href="mailto:support@openclaw.ai" class="text-sm text-gray-500 hover:text-gray-700">
                                Soporte
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        
        @stack('scripts')
    </body>
</html>
