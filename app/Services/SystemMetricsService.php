<?php
namespace App\Services;

class SystemMetricsService
{
    public function getOpenClawStatus()
    {
        return [
            'status' => 'operational',
            'version' => '1.0.0',
            'services' => [
                'web_server' => $this->checkWebServer(),
                'database' => $this->checkDatabase(),
                'file_system' => $this->checkFileSystem(),
            ],
            'timestamp' => now()->toISOString(),
        ];
    }
    
    public function getAgentCount()
    {
        // Por ahora, valor estático
        // TODO: Integrar con sistema real de agentes
        return [
            'active' => 1,
            'inactive' => 0,
            'total' => 1,
        ];
    }
    
    public function getRecentActivity()
    {
        return [
            [
                'type' => 'system',
                'message' => 'OpenClaw Portal instalado',
                'timestamp' => now()->subHours(1)->toISOString(),
            ],
            [
                'type' => 'user',
                'message' => 'Usuario admin autenticado',
                'timestamp' => now()->subMinutes(30)->toISOString(),
            ],
        ];
    }
    
    public function getSystemHealth()
    {
        $diskUsage = disk_free_space('/') / disk_total_space('/') * 100;
        $memoryUsage = $this->getMemoryUsage();
        
        return [
            'disk' => [
                'free_percent' => round($diskUsage, 2),
                'status' => $diskUsage > 20 ? 'healthy' : 'warning',
            ],
            'memory' => [
                'usage_percent' => $memoryUsage,
                'status' => $memoryUsage < 80 ? 'healthy' : 'warning',
            ],
            'load' => $this->getSystemLoad(),
        ];
    }
    
    private function checkWebServer()
    {
        // Verificar si Apache está corriendo
        exec('systemctl is-active apache2', $output, $returnCode);
        return $returnCode === 0 ? 'running' : 'stopped';
    }
    
    private function checkDatabase()
    {
        // Verificar si SQLite es accesible
        $dbPath = database_path('database.sqlite');
        return file_exists($dbPath) && is_writable($dbPath) ? 'connected' : 'disconnected';
    }
    
    private function checkFileSystem()
    {
        $storagePath = storage_path();
        return is_writable($storagePath) ? 'writable' : 'readonly';
    }
    
    private function getMemoryUsage()
    {
        // Obtener uso de memoria en porcentaje
        $free = shell_exec('free | grep Mem | awk \'{print $3/$2 * 100.0}\'');
        return round((float)$free, 2);
    }
    
    private function getSystemLoad()
    {
        // Obtener carga del sistema
        $load = sys_getloadavg();
        return [
            '1min' => $load[0],
            '5min' => $load[1],
            '15min' => $load[2],
        ];
    }
}
