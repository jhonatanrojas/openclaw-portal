<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - OpenClaw Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                OpenClaw Portal
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Sistema de gestión de agentes
            </p>
        </div>
        
        <form id="loginForm" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Email address" value="admin@openclaw.test">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Password" value="password">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
            
            <div class="text-sm text-center">
                <p class="text-gray-600">Credenciales de prueba:</p>
                <p class="text-gray-800 font-mono">admin@openclaw.test / password</p>
            </div>
        </form>
        
        <div id="message" class="hidden p-4 rounded-md"></div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            const messageDiv = document.getElementById('message');
            messageDiv.className = 'hidden';
            
            try {
                const response = await fetch('/api/v2/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    messageDiv.className = 'p-4 rounded-md bg-green-100 text-green-800';
                    messageDiv.textContent = '✓ ' + result.message;
                    
                    // Redirigir después de 1 segundo
                    setTimeout(() => {
                        window.location.href = result.redirect || '/dashboard';
                    }, 1000);
                } else {
                    messageDiv.className = 'p-4 rounded-md bg-red-100 text-red-800';
                    messageDiv.textContent = '✗ ' + result.message;
                }
            } catch (error) {
                messageDiv.className = 'p-4 rounded-md bg-red-100 text-red-800';
                messageDiv.textContent = '✗ Error de conexión: ' + error.message;
            }
        });
    </script>
</body>
</html>
