@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold">OpenClaw Portal Dashboard</h2>
                    <div class="text-sm text-gray-500">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">Online</span>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 dark:bg-blue-800 rounded-lg mr-4">
                                <span class="text-blue-600 dark:text-blue-300">👥</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Agentes Activos</p>
                                <p class="text-2xl font-bold">4</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 dark:bg-green-800 rounded-lg mr-4">
                                <span class="text-green-600 dark:text-green-300">📋</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tareas Activas</p>
                                <p class="text-2xl font-bold">60</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 dark:bg-purple-800 rounded-lg mr-4">
                                <span class="text-purple-600 dark:text-purple-300">📚</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Documentos</p>
                                <p class="text-2xl font-bold">15</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-800 rounded-lg mr-4">
                                <span class="text-yellow-600 dark:text-yellow-300">🚀</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Fase Actual</p>
                                <p class="text-2xl font-bold">2</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Acciones Rápidas</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="/docs" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                            📚 Ver Documentación
                        </a>
                        <a href="/agents" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition">
                            👥 Gestionar Agentes
                        </a>
                        <a href="/tasks" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition">
                            📋 Ver Tareas
                        </a>
                        <a href="/settings" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                            ⚙️ Configuración
                        </a>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Actividad Reciente</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Fase 2 iniciada</p>
                                <p class="text-sm text-gray-500">60 tareas asignadas a 4 agentes</p>
                            </div>
                            <div class="ml-auto text-sm text-gray-500">Hace 5 min</div>
                        </div>
                        
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Proxy configurado</p>
                                <p class="text-sm text-gray-500">Apache proxy funcionando en openclaw.deploymatrix.com</p>
                            </div>
                            <div class="ml-auto text-sm text-gray-500">Hace 1 hora</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
