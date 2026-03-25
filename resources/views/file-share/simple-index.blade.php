@extends('layouts.simple-app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-share-alt mr-2"></i> OpenClaw File Share
        </h1>
        <p class="page-subtitle">
            Archivos que OpenClaw genera y comparte contigo.
        </p>
    </div>

    <div class="alert alert-info">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium">Sistema en construcción</h4>
                <p class="text-sm mt-1">
                    El File Share está siendo configurado. Pronto podrás acceder a:
                    <strong>screenshots, tareas, documentación, reportes y más.</strong>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                <i class="fas fa-camera text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Screenshots
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Capturas de pantalla de proyectos y procesos.
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900 flex items-center justify-center mb-4">
                <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Tareas
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Listas de tareas y seguimiento de proyectos.
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900 flex items-center justify-center mb-4">
                <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Reportes
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                Reportes generados automáticamente por el sistema.
            </p>
        </div>
    </div>

    <div class="mt-8 text-center">
        <p class="text-gray-600 dark:text-gray-400 mb-4">
            Próximamente disponible...
        </p>
        <a href="{{ url('/') }}" class="btn-primary inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Inicio
        </a>
    </div>
</div>
@endsection