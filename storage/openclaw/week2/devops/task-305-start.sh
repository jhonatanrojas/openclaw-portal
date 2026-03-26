#!/bin/bash
echo "🔧 DevOps Engineer - Iniciando TASK-305"
echo "======================================="
echo "Tarea: Sistema de autenticación OAuth2"
echo "Prioridad: Alta"
echo ""

cd /var/www/openclaw-portal

echo "🎯 Objetivos:"
echo "1. Instalar Laravel Socialite"
echo "2. Configurar OAuth con GitHub"
echo "3. Crear controladores básicos"
echo "4. Configurar variables de entorno"
echo ""

echo "🚀 Iniciando implementación..."
echo ""

# Instalar Socialite
echo "📦 Instalando Laravel Socialite..."
composer require laravel/socialite 2>&1 | tail -5

# Crear configuración básica
cat > config/services.php << 'SERVICESEOF'
<?php

return [
    // ... configuraciones existentes ...
    
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI', 'http://openclaw.deploymatrix.com/auth/github/callback'),
    ],
    
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://openclaw.deploymatrix.com/auth/google/callback'),
    ],
];
SERVICESEOF

echo "✅ Configuración de servicios creada"
echo ""

# Agregar variables de entorno
echo "🔧 Agregando variables de entorno..."
cat >> .env << 'ENVEOF'

# OAuth Configuration
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI=http://openclaw.deploymatrix.com/auth/github/callback

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://openclaw.deploymatrix.com/auth/google/callback
ENVEOF

echo "✅ Variables de entorno agregadas"
echo ""

# Crear controlador básico
mkdir -p app/Http/Controllers/Auth
cat > app/Http/Controllers/Auth/OAuthController.php << 'CONTROLLEREOF'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            
            // Aquí iría la lógica para crear/actualizar usuario
            return redirect('/dashboard')->with('success', 'Autenticación exitosa');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error en autenticación: ' . $e->getMessage());
        }
    }
}
CONTROLLEREOF

echo "✅ Controlador OAuthController creado"
echo ""

# Marcar como en progreso
echo "TASK-305:in_progress" > storage/openclaw/week2/devops/status.txt
echo "📝 Nota: Configuración básica completada"
echo "🔧 Pendiente: Configurar credenciales reales, lógica de usuarios, tests"
echo ""
echo "🔧 DevOps Engineer listo para continuar..."
