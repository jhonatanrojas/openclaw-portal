<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Integración con sistema de autenticación existente de OpenClaw
     */
    public function authenticateOpenClaw($username, $password)
    {
        // Validación básica - integrar con auth.php existente
        if ($username === 'admin' && $password === '603015') {
            return [
                'success' => true,
                'user' => [
                    'name' => 'OpenClaw Admin',
                    'email' => 'admin@openclaw.test'
                ]
            ];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    /**
     * Obtener configuración de OpenClaw workspace
     */
    public function getOpenClawConfig()
    {
        $workspacePath = '/root/.openclaw/workspace';
        $config = [];
        
        $files = [
            'AGENTS.md' => 'agents',
            'SOUL.md' => 'soul',
            'USER.md' => 'user',
            'TOOLS.md' => 'tools',
            'MEMORY.md' => 'memory'
        ];
        
        foreach ($files as $file => $key) {
            $filePath = "$workspacePath/$file";
            if (file_exists($filePath)) {
                $config[$key] = file_get_contents($filePath);
            }
        }
        
        return $config;
    }
    
    /**
     * Sincronizar con workspace de OpenClaw
     */
    public function syncWithOpenClaw()
    {
        $config = $this->getOpenClawConfig();
        
        // Guardar en storage para acceso rápido
        $storagePath = storage_path('openclaw/workspace');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        
        foreach ($config as $key => $content) {
            file_put_contents("$storagePath/$key.md", $content);
        }
        
        return count($config);
    }
}
