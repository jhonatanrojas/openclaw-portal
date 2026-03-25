@extends('layouts.simple-app')

@section('content')
<div class="min-h-screen flex flex-col">
    <!-- Hero Section -->
    <div class="flex-grow bg-gradient-to-br from-primary-50 to-white dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16 text-center">
            <!-- Logo and Title -->
            <div class="mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-100 dark:bg-primary-900 mb-6">
                    <svg class="w-12 h-12 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    OpenClaw Portal
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    Tu centro de gestión y documentación para el ecosistema OpenClaw. 
                    Configura, monitorea y administra todo desde un solo lugar.
                </p>
            </div>

            <!-- Login Card -->
            <div class="max-w-md mx-auto mb-16">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        <i class="fas fa-sign-in-alt mr-2"></i> Acceso al Portal
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Inicia sesión para acceder a todas las funcionalidades del portal.
                    </p>
                    
                    <div class="space-y-4">
                        <!-- Login Button -->
                        <a href="{{ url('/auth/login') }}" 
                           class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-3"></i>
                            Iniciar Sesión
                        </a>
                        
                        <!-- Demo Access -->
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                ¿Primera vez? Prueba con:
                            </p>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-left">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                    <span class="font-mono text-sm">admin.demo@condominio.test</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-key text-gray-400 mr-2"></i>
                                    <span class="font-mono text-sm">password</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                ¿No tienes cuenta?
                                <a href="#" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium ml-1">
                                    Solicitar acceso
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-file-code text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Gestión de Archivos
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Edita AGENTS.md, SOUL.md, USER.md y otros archivos de configuración directamente desde la web.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-book text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Documentación Interactiva
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Sistema completo de documentación con categorías, búsqueda y editor Markdown integrado.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Dashboard en Tiempo Real
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Monitorea el estado del sistema, agentes activos y métricas importantes con gráficos interactivos.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Section (for logged out users) -->
    <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 text-center mb-8">
                Acceso Rápido (Sin Login)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <a href="{{ route('documentation.index') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-3 mx-auto">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                        Documentación
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Acceso público
                    </p>
                </a>

                <a href="{{ url('/api/status') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mb-3 mx-auto">
                        <i class="fas fa-code text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                        API Status
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Ver estado del sistema
                    </p>
                </a>

                <a href="{{ url('/agents') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center mb-3 mx-auto">
                        <i class="fas fa-robot text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                        Agentes
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Listado básico
                    </p>
                </a>

                <a href="{{ route('openclaw-files.index') }}" 
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center hover:shadow-md transition-shadow duration-200 border border-gray-200 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center mb-3 mx-auto">
                        <i class="fas fa-file-alt text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                        Archivos
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Vista de solo lectura
                    </p>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 text-center mb-8">
                Estadísticas del Portal
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                        7
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Archivos Config
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                        5
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Documentos
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        4
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        APIs Activas
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">
                        24/7
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Disponibilidad
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-primary-600 dark:bg-primary-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">
                ¿Listo para comenzar?
            </h2>
            <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
                Accede a todas las funcionalidades del portal OpenClaw con tu cuenta.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/auth/login') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </a>
                <a href="{{ route('documentation.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-200">
                    <i class="fas fa-book mr-2"></i>
                    Ver Documentación
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for interactive elements -->
<script>
    // Simple animation for stats
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats counters
        const stats = document.querySelectorAll('.text-3xl.font-bold');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent);
            if (!isNaN(target)) {
                animateCounter(stat, target);
            }
        });
        
        // Add hover effects to feature cards
        const featureCards = document.querySelectorAll('.hover\\:shadow-xl');
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-4px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    });
    
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 30);
    }
</script>
@endsection