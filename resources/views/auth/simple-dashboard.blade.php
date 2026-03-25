<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OpenClaw Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">OpenClaw Portal</h1>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-4">Welcome, {{ $user['name'] }}</span>
                    <a href="/simple-auth/logout" 
                       class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Agentes Activos</h3>
                            <p class="text-3xl font-bold text-blue-600">6</p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Tareas Pendientes</h3>
                            <p class="text-3xl font-bold text-green-600">24</p>
                        </div>
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800">Proyectos Activos</h3>
                            <p class="text-3xl font-bold text-purple-600">3</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Acciones Rápidas</h3>
                        <div class="flex space-x-4">
                            <a href="/agents" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Ver Agentes
                            </a>
                            <a href="/test" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                                Test API
                            </a>
                            <a href="/agents-test" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Agents API
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-4">Información del Usuario</h3>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p><strong>Email:</strong> {{ $user['email'] }}</p>
                            <p><strong>Rol:</strong> {{ $user['role'] }}</p>
                            <p><strong>ID:</strong> {{ $user['id'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
