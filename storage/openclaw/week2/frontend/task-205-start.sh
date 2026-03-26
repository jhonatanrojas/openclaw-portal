#!/bin/bash
echo "🎨 Frontend Developer - Iniciando TASK-205"
echo "=========================================="
echo "Tarea: Panel de gestión de agentes"
echo "Prioridad: Alta"
echo ""

cd /var/www/openclaw-portal

echo "🎯 Objetivos:"
echo "1. Crear vista agents/index.blade.php"
echo "2. Implementar filtros básicos"
echo "3. Crear cards para agentes"
echo "4. Agregar estadísticas"
echo ""

echo "🚀 Iniciando implementación..."
echo ""

# Crear vista básica
mkdir -p resources/views/agents
cat > resources/views/agents/index.blade.php << 'VIEWEOF'
@extends('layouts.app')

@section('title', 'Agentes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Gestión de Agentes</h1>
    
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded-lg">
            <div class="text-blue-800 font-semibold">Total Agentes</div>
            <div class="text-2xl font-bold">12</div>
        </div>
        <div class="bg-green-100 p-4 rounded-lg">
            <div class="text-green-800 font-semibold">Activos</div>
            <div class="text-2xl font-bold">8</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded-lg">
            <div class="text-yellow-800 font-semibold">Ocupados</div>
            <div class="text-2xl font-bold">3</div>
        </div>
        <div class="bg-purple-100 p-4 rounded-lg">
            <div class="text-purple-800 font-semibold">Tareas</div>
            <div class="text-2xl font-bold">24</div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="bg-gray-100 p-4 rounded-lg mb-6">
        <div class="flex flex-wrap gap-4">
            <select class="px-4 py-2 rounded border">
                <option>Todos los tipos</option>
                <option>Backend</option>
                <option>Frontend</option>
                <option>DevOps</option>
                <option>Documentación</option>
            </select>
            <select class="px-4 py-2 rounded border">
                <option>Todos los estados</option>
                <option>Activo</option>
                <option>Inactivo</option>
                <option>Ocupado</option>
            </select>
            <input type="text" placeholder="Buscar..." class="px-4 py-2 rounded border flex-grow">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Filtrar
            </button>
        </div>
    </div>
    
    <!-- Lista de agentes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for($i = 1; $i <= 6; $i++)
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-lg">Agente {{ $i }}</h3>
                    <div class="flex items-center mt-1">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                            @if($i % 4 == 0) Backend
                            @elseif($i % 4 == 1) Frontend
                            @elseif($i % 4 == 2) DevOps
                            @else Documentación
                            @endif
                        </span>
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                            Activo
                        </span>
                    </div>
                </div>
                <button class="text-gray-500 hover:text-gray-700">
                    ⋮
                </button>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span>Tareas activas:</span>
                    <span class="font-bold">{{ $i * 2 }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Completadas:</span>
                    <span class="font-bold">{{ $i * 10 }}</span>
                </div>
            </div>
            <div class="mt-4 flex space-x-2">
                <button class="flex-1 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                    Asignar
                </button>
                <button class="flex-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                    Detalles
                </button>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
VIEWEOF

echo "✅ Vista agents/index.blade.php creada"
echo ""

# Marcar como en progreso
echo "TASK-205:in_progress" > storage/openclaw/week2/frontend/status.txt
echo "📝 Nota: Vista básica creada"
echo "🔧 Pendiente: JavaScript, filtros dinámicos, API integration"
echo ""
echo "🎨 Frontend Developer listo para continuar..."
