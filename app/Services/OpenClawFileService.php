<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OpenClawFileService
{
    /**
     * Directorio base del file share de OpenClaw
     */
    protected string $basePath = '/var/www/openclaw.deploymatrix.com/public_html';

    /**
     * Directorios disponibles en el file share
     */
    protected array $availableDirectories = [
        'screenshots' => [
            'name' => 'Screenshots',
            'description' => 'Capturas de pantalla de proyectos y procesos',
            'path' => '/screenshots/',
            'editable' => true,
            'category' => 'media',
            'allowed_extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp'],
            'max_size_mb' => 10,
        ],
        'docs' => [
            'name' => 'Documentación',
            'description' => 'Documentación técnica y manuales',
            'path' => '/docs/',
            'editable' => true,
            'category' => 'documentation',
            'allowed_extensions' => ['md', 'txt', 'pdf', 'doc', 'docx'],
            'max_size_mb' => 20,
        ],
        'logs' => [
            'name' => 'Logs',
            'description' => 'Registros del sistema y actividades',
            'path' => '/logs/',
            'editable' => true,
            'category' => 'system',
            'allowed_extensions' => ['log', 'txt', 'json'],
            'max_size_mb' => 50,
        ],
        'backups' => [
            'name' => 'Backups',
            'description' => 'Copias de seguridad automáticas',
            'path' => '/backups/',
            'editable' => false, // Solo lectura - generados automáticamente
            'category' => 'backup',
            'allowed_extensions' => ['zip', 'tar', 'gz', 'sql'],
            'max_size_mb' => 100,
        ],
        'temp' => [
            'name' => 'Temporales',
            'description' => 'Archivos temporales (se limpian automáticamente)',
            'path' => '/temp/',
            'editable' => true,
            'category' => 'temporary',
            'allowed_extensions' => ['tmp', 'temp', '*'],
            'max_size_mb' => 5,
        ],
        'uploads' => [
            'name' => 'Uploads',
            'description' => 'Archivos subidos por usuarios/agentes',
            'path' => '/uploads/',
            'editable' => true,
            'category' => 'user',
            'allowed_extensions' => ['*'], // Todos los tipos
            'max_size_mb' => 50,
        ],
        'tasks' => [
            'name' => 'Tareas',
            'description' => 'Listas de tareas y seguimiento de proyectos',
            'path' => '/tasks/',
            'editable' => true,
            'category' => 'tasks',
            'allowed_extensions' => ['md', 'txt', 'json', 'csv'],
            'max_size_mb' => 5,
        ],
        'reports' => [
            'name' => 'Reportes',
            'description' => 'Reportes generados automáticamente',
            'path' => '/reports/',
            'editable' => false, // Solo lectura - generados por sistema
            'category' => 'reports',
            'allowed_extensions' => ['html', 'pdf', 'json', 'csv'],
            'max_size_mb' => 10,
        ],
    ];

    /**
     * Obtener lista de directorios disponibles
     */
    public function getAvailableDirectories(): array
    {
        $directories = [];

        foreach ($this->availableDirectories as $key => $dir) {
            $fullPath = $this->basePath . $dir['path'];
            $files = $this->getFilesInDirectory($fullPath);
            
            $directories[$key] = [
                'id' => $key,
                'name' => $dir['name'],
                'description' => $dir['description'],
                'editable' => $dir['editable'],
                'category' => $dir['category'],
                'path' => $dir['path'],
                'full_path' => $fullPath,
                'exists' => File::exists($fullPath),
                'file_count' => count($files),
                'total_size' => $this->getDirectorySize($fullPath),
                'allowed_extensions' => $dir['allowed_extensions'],
                'max_size_mb' => $dir['max_size_mb'],
                'files' => array_slice($files, 0, 10), // Primeros 10 archivos
            ];
        }

        return $directories;
    }

    /**
     * Obtener archivos en un directorio
     */
    public function getFilesInDirectory(string $directoryPath, bool $recursive = false): array
    {
        if (!File::exists($directoryPath)) {
            return [];
        }

        $files = [];
        $items = File::files($directoryPath);

        foreach ($items as $item) {
            $filePath = $item->getPathname();
            $relativePath = str_replace($this->basePath, '', $filePath);
            
            $files[] = [
                'name' => $item->getFilename(),
                'path' => $filePath,
                'relative_path' => $relativePath,
                'size' => $item->getSize(),
                'extension' => $item->getExtension(),
                'last_modified' => date('Y-m-d H:i:s', $item->getMTime()),
                'is_file' => true,
                'is_directory' => false,
                'mime_type' => $item->getMimeType(),
                'readable' => $item->isReadable(),
                'writable' => $item->isWritable(),
            ];
        }

        // Si es recursivo, también obtener subdirectorios
        if ($recursive) {
            $directories = File::directories($directoryPath);
            foreach ($directories as $dir) {
                $dirPath = $dir;
                $relativePath = str_replace($this->basePath, '', $dirPath);
                
                $files[] = [
                    'name' => basename($dir),
                    'path' => $dirPath,
                    'relative_path' => $relativePath,
                    'size' => $this->getDirectorySize($dirPath),
                    'extension' => '',
                    'last_modified' => date('Y-m-d H:i:s', filemtime($dirPath)),
                    'is_file' => false,
                    'is_directory' => true,
                    'mime_type' => 'directory',
                    'readable' => is_readable($dirPath),
                    'writable' => is_writable($dirPath),
                ];
            }
        }

        // Ordenar por nombre
        usort($files, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $files;
    }

    /**
     * Obtener tamaño de un directorio
     */
    public function getDirectorySize(string $directoryPath): int
    {
        if (!File::exists($directoryPath)) {
            return 0;
        }

        $size = 0;
        $files = File::allFiles($directoryPath);
        
        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Leer contenido de un archivo
     */
    public function readFile(string $fileId): array
    {
        if (!isset($this->availableFiles[$fileId])) {
            throw new \InvalidArgumentException("Archivo no disponible: {$fileId}");
        }

        $file = $this->availableFiles[$fileId];
        $path = $file['path'];

        if (!$this->fileExists($path)) {
            return [
                'content' => '',
                'exists' => false,
                'message' => "El archivo {$file['name']} no existe. Se creará al guardar.",
            ];
        }

        $content = File::get($path);

        return [
            'content' => $content,
            'exists' => true,
            'size' => $this->getFileSize($path),
            'last_modified' => $this->getLastModified($path),
            'encoding' => mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'ASCII'], true),
            'lines' => substr_count($content, "\n") + 1,
        ];
    }

    /**
     * Guardar contenido en un archivo
     */
    public function saveFile(string $fileId, string $content, bool $createBackup = true): array
    {
        if (!isset($this->availableFiles[$fileId])) {
            throw new \InvalidArgumentException("Archivo no disponible: {$fileId}");
        }

        $file = $this->availableFiles[$fileId];
        
        if (!$file['editable']) {
            throw new \RuntimeException("El archivo {$file['name']} es de solo lectura");
        }

        $path = $file['path'];
        $backupPath = null;

        // Crear backup si el archivo existe y se solicita
        if ($createBackup && $this->fileExists($path)) {
            $backupPath = $this->createBackup($path);
        }

        // Asegurar que el directorio existe
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Guardar el archivo
        File::put($path, $content);

        // Registrar el cambio
        $this->logChange($fileId, $path, $backupPath);

        return [
            'success' => true,
            'message' => "Archivo {$file['name']} guardado exitosamente",
            'backup_created' => $backupPath !== null,
            'backup_path' => $backupPath,
            'size' => strlen($content),
            'lines' => substr_count($content, "\n") + 1,
            'path' => $path,
        ];
    }

    /**
     * Crear backup de un archivo
     */
    public function createBackup(string $filePath): string
    {
        if (!$this->fileExists($filePath)) {
            throw new \RuntimeException("No se puede crear backup: archivo no existe");
        }

        $backupDir = storage_path('openclaw/backups/files');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = basename($filePath);
        $backupFilename = "{$filename}.backup.{$timestamp}";
        $backupPath = "{$backupDir}/{$backupFilename}";

        File::copy($filePath, $backupPath);

        return $backupPath;
    }

    /**
     * Obtener historial de cambios de un archivo
     */
    public function getChangeHistory(string $fileId, int $limit = 10): array
    {
        $logFile = storage_path('openclaw/logs/file-changes.log');

        if (!File::exists($logFile)) {
            return [];
        }

        $lines = File::lines($logFile);
        $history = [];
        $count = 0;

        foreach ($lines as $line) {
            if ($count >= $limit) break;

            $data = json_decode($line, true);
            if ($data && isset($data['file_id']) && $data['file_id'] === $fileId) {
                $history[] = $data;
                $count++;
            }
        }

        return array_reverse($history); // Más reciente primero
    }

    /**
     * Validar contenido del archivo
     */
    public function validateContent(string $fileId, string $content): array
    {
        $errors = [];
        $warnings = [];

        // Validaciones básicas
        if (empty(trim($content))) {
            $warnings[] = 'El archivo está vacío';
        }

        // Validaciones específicas por tipo de archivo
        switch ($fileId) {
            case 'agents':
                if (!Str::contains($content, '# AGENTS.md')) {
                    $warnings[] = 'Posible falta de encabezado estándar AGENTS.md';
                }
                break;
            case 'soul':
                if (!Str::contains($content, '# SOUL.md')) {
                    $warnings[] = 'Posible falta de encabezado estándar SOUL.md';
                }
                break;
            case 'memory':
                // MEMORY.md debería tener formato específico
                if (!Str::contains($content, '## ')) {
                    $warnings[] = 'MEMORY.md podría no seguir formato de secciones';
                }
                break;
        }

        // Validar encoding
        if (!mb_check_encoding($content, 'UTF-8')) {
            $errors[] = 'El contenido contiene caracteres no válidos para UTF-8';
        }

        // Validar tamaño máximo (10MB)
        if (strlen($content) > 10 * 1024 * 1024) {
            $errors[] = 'El archivo excede el tamaño máximo de 10MB';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Obtener estadísticas de archivos
     */
    public function getStats(): array
    {
        $stats = [
            'total_files' => count($this->availableFiles),
            'editable_files' => 0,
            'readonly_files' => 0,
            'existing_files' => 0,
            'total_size' => 0,
            'by_category' => [],
        ];

        foreach ($this->availableFiles as $key => $file) {
            $exists = $this->fileExists($file['path']);
            
            if ($file['editable']) {
                $stats['editable_files']++;
            } else {
                $stats['readonly_files']++;
            }

            if ($exists) {
                $stats['existing_files']++;
                $stats['total_size'] += $this->getFileSize($file['path']);
            }

            // Estadísticas por categoría
            $category = $file['category'];
            if (!isset($stats['by_category'][$category])) {
                $stats['by_category'][$category] = [
                    'count' => 0,
                    'editable' => 0,
                    'exists' => 0,
                ];
            }

            $stats['by_category'][$category]['count']++;
            if ($file['editable']) {
                $stats['by_category'][$category]['editable']++;
            }
            if ($exists) {
                $stats['by_category'][$category]['exists']++;
            }
        }

        return $stats;
    }

    /**
     * Verificar si un archivo existe
     */
    private function fileExists(string $path): bool
    {
        return File::exists($path);
    }

    /**
     * Obtener tamaño del archivo
     */
    private function getFileSize(string $path): int
    {
        if (!$this->fileExists($path)) {
            return 0;
        }

        return File::size($path);
    }

    /**
     * Obtener fecha de última modificación
     */
    private function getLastModified(string $path): ?string
    {
        if (!$this->fileExists($path)) {
            return null;
        }

        return date('Y-m-d H:i:s', File::lastModified($path));
    }

    /**
     * Registrar cambio en archivo
     */
    private function logChange(string $fileId, string $filePath, ?string $backupPath): void
    {
        $logDir = storage_path('openclaw/logs');
        if (!File::exists($logDir)) {
            File::makeDirectory($logDir, 0755, true);
        }

        $logFile = "{$logDir}/file-changes.log";

        $logEntry = [
            'timestamp' => now()->toISOString(),
            'file_id' => $fileId,
            'file_name' => basename($filePath),
            'file_path' => $filePath,
            'backup_path' => $backupPath,
            'action' => 'save',
            'user_agent' => request()->userAgent() ?? 'system',
            'ip_address' => request()->ip() ?? '127.0.0.1',
        ];

        File::append($logFile, json_encode($logEntry) . PHP_EOL);
    }

    /**
     * Restaurar desde backup
     */
    public function restoreFromBackup(string $backupPath, string $originalPath): bool
    {
        if (!File::exists($backupPath)) {
            throw new \RuntimeException("El archivo de backup no existe: {$backupPath}");
        }

        // Crear backup del estado actual antes de restaurar
        if (File::exists($originalPath)) {
            $currentBackup = $this->createBackup($originalPath);
        }

        // Restaurar desde backup
        File::copy($backupPath, $originalPath);

        // Registrar la restauración
        $logDir = storage_path('openclaw/logs');
        $logFile = "{$logDir}/file-changes.log";

        $logEntry = [
            'timestamp' => now()->toISOString(),
            'file_id' => $this->getFileIdFromPath($originalPath),
            'file_name' => basename($originalPath),
            'file_path' => $originalPath,
            'backup_path' => $backupPath,
            'action' => 'restore',
            'user_agent' => request()->userAgent() ?? 'system',
            'ip_address' => request()->ip() ?? '127.0.0.1',
        ];

        File::append($logFile, json_encode($logEntry) . PHP_EOL);

        return true;
    }

    /**
     * Obtener ID de archivo desde ruta
     */
    private function getFileIdFromPath(string $path): ?string
    {
        foreach ($this->availableFiles as $id => $file) {
            if ($file['path'] === $path) {
                return $id;
            }
        }

        return null;
    }
}