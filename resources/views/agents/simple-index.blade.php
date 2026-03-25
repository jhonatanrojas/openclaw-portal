<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agentes - OpenClaw Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Gestión de Agentes</h1>
                    
                    <div class="mb-6">
                        <p class="text-gray-600">Esta es una vista simplificada mientras se resuelve el error en la vista dinámica.</p>
                        <p class="text-gray-600 mt-2">La API de agentes está funcionando correctamente:</p>
                        <a href="/api-agents.php" class="text-blue-600 hover:text-blue-800 underline">/api-agents.php</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800">Backend Specialist</h3>
                            <p class="text-gray-600">Tareas: 12 activas, 45 completadas</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">backend</span>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800">Frontend Developer</h3>
                            <p class="text-gray-600">Tareas: 8 activas, 38 completadas</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">frontend</span>
                        </div>
                        
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-yellow-800">DevOps Engineer</h3>
                            <p class="text-gray-600">Tareas: 5 activas, 52 completadas</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">devops</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Endpoints disponibles:</h3>
                        <ul class="list-disc pl-5 text-gray-600">
                            <li><a href="/api-agents.php" class="text-blue-600 hover:text-blue-800">/api-agents.php</a> - API de agentes (JSON)</li>
                            <li><a href="/auth-test.php" class="text-blue-600 hover:text-blue-800">/auth-test.php</a> - Autenticación (POST)</li>
                            <li><a href="/test" class="text-blue-600 hover:text-blue-800">/test</a> - Endpoint de prueba</li>
                            <li><a href="/agents-test" class="text-blue-600 hover:text-blue-800">/agents-test</a> - API simple de agentes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>