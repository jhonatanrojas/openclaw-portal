<?php

namespace App\Services;

class FileShareService
{
    /**
     * Directorio base del file share
     */
    protected string $basePath = '/var/www/openclaw.deploymatrix.com/public_html';

    /**
     * Categorías de archivos compartidos
     */
    protected array $categories = [
        'screenshots' => [
            'name' => 'Screenshots',
            'description' => 'Capturas de pantalla de proyectos y procesos',
            'icon' => 'fas fa-camera',
            'color' => 'blue',
            'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp'],
            'max_size' => 10 * 1024 * 1024, // 10MB
        ],
        'docs' => [
            'name' => 'Documentación',
            'description' => 'Manuales, guías y documentación técnica',
            'icon' => 'fas fa-book',
            'color' => 'green',
            'extensions' => ['md', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'max_size' => 50 * 1024 * 1024, // 50MB
        ],
        'tasks' => [
            'name' => 'Tareas',
            'description' => 'Listas de tareas y seguimiento de proyectos',
            'icon' => 'fas fa-tasks',
            'color' => 'amber',
            'extensions' => ['json', 'csv', 'txt', 'md'],
            'max_size' => 5 * 1024 * 1024, // 5MB
        ],
        'reports' => [
            'name' => 'Reportes',
            'description' => 'Reportes generados automáticamente por el sistema',
            'icon' => 'fas fa-chart-bar',
            'color' => 'purple',
            'extensions' => ['pdf', 'csv', 'json', 'txt', 'md'],
            'max_size' => 20 * 1024 * 1024, // 20MB
        ],
        'exports' => [
            'name' => 'Exportaciones',
            'description' => 'Datos exportados en varios formatos',
            'icon' => 'fas fa-file-export',
            'color' => 'indigo',
            'extensions' => ['json', 'csv', 'xml', 'yaml', 'yml'],
            'max_size' => 100 * 1024 * 1024, // 100MB
        ],
        'shared' => [
            'name' => 'Compartidos',
            'description' => 'Archivos intercambiados entre agentes',
            'icon' => 'fas fa-share-alt',
            'color' => 'pink',
            'extensions' => ['*'], // Todos los tipos
            'max_size' => 100 * 1024 * 1024, // 100MB
        ],
        'templates' => [
            'name' => 'Plantillas',
            'description' => 'Plantillas reutilizables para diferentes propósitos',
            'icon' => 'fas fa-layer-group',
            'color' => 'teal',
            'extensions' => ['json', 'yaml', 'yml', 'txt', 'md'],
            'max_size' => 5 * 1024 * 1024, // 5MB
        ],
    ];

    /**
     * Obtener todas las categorías
     */
    public function getCategories(): array
    {
        $categories = [];

        foreach ($this->categories as $key => $category) {
            $path = $this->basePath . '/' . $key;
            $exists = file_exists($path) && is_dir($path);
            
            $categories[$key] = [
                'id' => $key,
                'name' => $category['name'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'path' => '/' . $key . '/',
                'full_path' => $path,
                'exists' => $exists,
                'file_count' => $exists ? count($this->getFilesInDirectory($path)) : 0,
                'total_size' => $exists ? $this->getDirectorySize($path) : 0,
                'extensions' => $category['extensions'],
                'max_size' => $category['max_size'],
                'max_size_mb' => round($category['max_size'] / 1024 / 1024, 2),
            ];
        }

        return $categories;
    }

    /**
     * Obtener estadísticas generales
     */
    public function getStats(): array
    {
        $stats = [
            'total_categories' => count($this->categories),
            'total_files' => 0,
            'total_size' => 0,
            'total_size_formatted' => '0 B',
        ];

        foreach ($this->categories as $key => $category) {
            $path = $this->basePath . '/' . $key;
            
            if (file_exists($path) && is_dir($path)) {
                $files = $this->getFilesInDirectory($path);
                $stats['total_files'] += count($files);
                $stats['total_size'] += $this->getDirectorySize($path);
            }
        }

        $stats['total_size_formatted'] = $this->formatBytes($stats['total_size']);

        return $stats;
    }

    /**
     * Obtener archivos de una categoría
     */
    public function getFiles(string $category, bool $includeDetails = false): array
    {
        if (!isset($this->categories[$category])) {
            return [];
        }

        $path = $this->basePath . '/' . $category;
        
        if (!file_exists($path) || !is_dir($path)) {
            return [];
        }

        $files = [];
        $items = $this->getFilesInDirectory($path);

        foreach ($items as $item) {
            $fileInfo = [
                'name' => $item->getFilename(),
                'path' => $item->getPathname(),
                'relative_path' => '/' . $category . '/' . $item->getFilename(),
                'url' => 'http://openclaw.deploymatrix.com/' . $category . '/' . $item->getFilename(),
                'size' => $item->getSize(),
                'size_formatted' => $this->formatBytes($item->getSize()),
                'extension' => $item->getExtension(),
                'last_modified' => date('Y-m-d H:i:s', $item->getMTime()),
                'last_modified_relative' => $this->relativeTime($item->getMTime()),
                'mime_type' => $this->getMimeType($item->getPathname()),
                'is_text' => $this->isTextFile($item->getPathname()),
                'is_image' => $this->isImageFile($item->getPathname()),
                'is_document' => $this->isDocumentFile($item->getExtension()),
            ];

            if ($includeDetails) {
                $fileInfo['details'] = $this->getFileDetails($item->getPathname());
            }

            $files[] = $fileInfo;
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($files, function ($a, $b) {
            return strtotime($b['last_modified']) - strtotime($a['last_modified']);
        });

        return $files;
    }

    /**
     * Obtener detalles de un archivo específico
     */
    public function getFile(string $category, string $filename): ?array
    {
        $path = $this->basePath . '/' . $category . '/' . $filename;
        
        if (!file_exists($path)) {
            return null;
        }

        $fileInfo = [
            'name' => $filename,
            'category' => $category,
            'path' => $path,
            'relative_path' => '/' . $category . '/' . $filename,
            'url' => 'http://openclaw.deploymatrix.com/' . $category . '/' . $filename,
            'size' => filesize($path),
            'size_formatted' => $this->formatBytes(filesize($path)),
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
            'last_modified' => date('Y-m-d H:i:s', filemtime($path)),
            'last_modified_relative' => $this->relativeTime(filemtime($path)),
            'mime_type' => $this->getMimeType($path),
            'is_text' => $this->isTextFile($path),
            'is_image' => $this->isImageFile($path),
            'is_document' => $this->isDocumentFile(pathinfo($path, PATHINFO_EXTENSION)),
            'details' => $this->getFileDetails($path),
        ];

        return $fileInfo;
    }

    /**
     * Subir archivo a una categoría
     */
    public function uploadFile(string $category, $file, array $metadata = []): array
    {
        if (!isset($this->categories[$category])) {
            return ['success' => false, 'message' => 'Categoría no válida'];
        }

        // Validar extensión
        $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        if (!$this->isExtensionAllowed($category, $extension)) {
            return ['success' => false, 'message' => 'Extensión no permitida para esta categoría'];
        }

        // Validar tamaño
        if ($file->getSize() > $this->categories[$category]['max_size']) {
            $maxSizeMB = round($this->categories[$category]['max_size'] / 1024 / 1024, 2);
            return ['success' => false, 'message' => "El archivo excede el tamaño máximo de {$maxSizeMB}MB"];
        }

        $directory = $this->basePath . '/' . $category;
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $this->generateSafeFilename($file->getClientOriginalName());
        $targetPath = $directory . '/' . $filename;

        // Mover archivo
        if (move_uploaded_file($file->getPathname(), $targetPath)) {
            // Guardar metadatos
            $this->saveFileMetadata($category, $filename, array_merge($metadata, [
                'uploaded_at' => date('Y-m-d H:i:s'),
                'uploaded_by' => $metadata['uploaded_by'] ?? 'system',
                'original_name' => $file->getClientOriginalName(),
            ]));

            return [
                'success' => true,
                'message' => 'Archivo subido exitosamente',
                'file' => $this->getFile($category, $filename),
            ];
        }

        return ['success' => false, 'message' => 'Error al subir el archivo'];
    }

    /**
     * Eliminar archivo
     */
    public function deleteFile(string $category, string $filename): array
    {
        $path = $this->basePath . '/' . $category . '/' . $filename;
        
        if (!file_exists($path)) {
            return ['success' => false, 'message' => 'Archivo no encontrado'];
        }

        if (unlink($path)) {
            // Eliminar metadatos
            $metadataFile = $this->basePath . '/' . $category . '/.metadata/' . $filename . '.json';
            if (file_exists($metadataFile)) {
                unlink($metadataFile);
            }

            return ['success' => true, 'message' => 'Archivo eliminado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar el archivo'];
    }

    /**
     * Obtener metadatos de un archivo
     */
    public function getFileMetadata(string $category, string $filename): ?array
    {
        $metadataFile = $this->basePath . '/' . $category . '/.metadata/' . $filename . '.json';
        
        if (file_exists($metadataFile)) {
            $content = file_get_contents($metadataFile);
            return json_decode($content, true);
        }

        return null;
    }

    /**
     * Buscar archivos
     */
    public function searchFiles(string $query, string $category = null): array
    {
        $results = [];
        $categories = $category ? [$category => $this->categories[$category]] : $this->categories;

        foreach ($categories as $catKey => $cat) {
            $path = $this->basePath . '/' . $catKey;
            
            if (!file_exists($path) || !is_dir($path)) {
                continue;
            }

            $files = $this->getAllFilesInDirectory($path);
            
            foreach ($files as $file) {
                if (stripos($file->getFilename(), $query) !== false) {
                    $results[] = [
                        'name' => $file->getFilename(),
                        'category' => $catKey,
                        'path' => $file->getPathname(),
                        'relative_path' => '/' . $catKey . '/' . $file->getFilename(),
                        'url' => 'http://openclaw.deploymatrix.com/' . $catKey . '/' . $file->getFilename(),
                        'size' => $file->getSize(),
                        'size_formatted' => $this->formatBytes($file->getSize()),
                        'last_modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * ============================================
     * MÉTODOS PRIVADOS / HELPERS
     * ============================================
     */

    /**
     * Obtener archivos en un directorio (sin recursividad)
     */
    private function getFilesInDirectory(string $path): array
    {
        if (!file_exists($path) || !is_dir($path)) {
            return [];
        }

        $files = [];
        $items = scandir($path);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $fullPath = $path . '/' . $item;
            if (is_file($fullPath)) {
                $files[] = new \SplFileInfo($fullPath);
            }
        }

        return $files;
    }

    /**
     * Obtener todos los archivos en un directorio (con recursividad)
     */
    private function getAllFilesInDirectory(string $path): array
    {
        if (!file_exists($path) || !is_dir($path)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $files[] = $item;
            }
        }

        return $files;
    }

    /**
     * Obtener tamaño de directorio
     */
    private function getDirectorySize(string $path): int
    {
        if (!file_exists($path) || !is_dir($path)) {
            return 0;
        }

        $size = 0;
        $files = $this->getAllFilesInDirectory($path);
        
        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Formatear bytes a tamaño legible
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Tiempo relativo (hace X tiempo)
     */
    private function relativeTime(int $timestamp): string
    {
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'hace ' . $diff . ' segundos';
        } elseif ($diff < 3600) {
            return 'hace ' . floor($diff / 60) . ' minutos';
        } elseif ($diff < 86400) {
            return 'hace ' . floor($diff / 3600) . ' horas';
        } elseif ($diff < 2592000) {
            return 'hace ' . floor($diff / 86400) . ' días';
        } else {
            return 'hace ' . floor($diff / 2592000) . ' meses';
        }
    }

    /**
     * Obtener MIME type de un archivo
     */
    private function getMimeType(string $path): string
    {
        if (function_exists('mime_content_type')) {
            return mime_content_type($path) ?: 'application/octet-stream';
        }
        
        if (class_exists('finfo')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path);
            finfo_close($finfo);
            return $mime ?: 'application/octet-stream';
        }
        
        // Fallback basado en extensión
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeMap = [
            'txt' => 'text/plain',
            'md' => 'text/markdown',
            'json' => 'application/json',
            'csv' => 'text/csv',
            'xml' => 'application/xml',
            'yaml' => 'application/x-yaml',
            'yml' => 'application/x-yaml',
            'js' => 'application/javascript',
            'css' => 'text/css',
            'html' => 'text/html',
            'php' => 'application/x-httpd-php',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip',
            'tar' => 'application/x-tar',
            'gz' => 'application/gzip',
        ];
        
        return $mimeMap[$extension] ?? 'application/octet-stream';
    }

    /**
     * Verificar si es archivo de texto
     */
    private function isTextFile(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $textExtensions = ['txt', 'md', 'json', 'csv', 'xml', 'yaml', 'yml', 'js', 'css', 'html', 'php'];
        
        return in_array($extension, $textExtensions);
    }

    /**
     * Verificar si es archivo de imagen
     */
    private function isImageFile(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        
        return in_array($extension, $imageExtensions);
    }

    /**
     * Verificar si es archivo de documento
     */
    private function isDocumentFile(string $extension): bool
    {
        $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        
        return in_array(strtolower($extension), $docExtensions);
    }

    /**
     * Verificar si extensión está permitida
     */
    private function isExtensionAllowed(string $category, string $extension): bool
    {
        $allowed = $this->categories[$category]['extensions'];
        
        if (in_array('*', $allowed)) {
            return true;
        }
        
        return in_array(strtolower($extension), $allowed);
    }

    /**
     * Generar nombre de archivo seguro
     */
    private function generateSafeFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Limpiar nombre
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $name = substr($name, 0, 100);
        
        // Agregar timestamp para unicidad
        $timestamp = time();
        
        return $name . '_' . $timestamp . '.' . $extension;
    }

    /**
     * Guardar metadatos de archivo
     */
    private function saveFileMetadata(string $category, string $filename, array $metadata): void
    {
        $metadataDir = $this->basePath . '/' . $category . '/.metadata';
        
        if (!file_exists($metadataDir)) {
            mkdir($metadataDir, 0755, true);
        }
        
        $metadataFile = $metadataDir . '/' . $filename . '.json';
        file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT));
    }

    /**
     * Obtener detalles adicionales de un archivo
     */
    private function getFileDetails(string $path): array
    {
        $details = [
            'lines' => 0,
            'words' => 0,
            'characters' => 0,
            'encoding' => 'unknown',
        ];

        if ($this->isTextFile($path)) {
            $content = file_get_contents($path);
            $details['lines'] = substr_count($content, "\n") + 1;
            $details['words'] = str_word_count($content);
            $details['characters'] = strlen($content);
            $details['encoding'] = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'ASCII'], true) ?: 'unknown';
        }

        if ($this->isImageFile($path)) {
            $imageInfo = @getimagesize($path);
            if ($imageInfo) {
                $details['dimensions'] = $imageInfo[0] . 'x' . $imageInfo[1];
                $details['image_type'] = $imageInfo[2]; // IMAGETYPE_XXX constant
            }
        }

        return $details;
    }
}