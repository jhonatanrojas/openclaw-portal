<?php

namespace App\Http\Controllers;

use App\Services\OpenClawFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpenClawFileController extends Controller
{
    protected OpenClawFileService $fileService;

    public function __construct(OpenClawFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Listar archivos disponibles
     */
    public function index()
    {
        $files = $this->fileService->getAvailableFiles();
        $stats = $this->fileService->getStats();

        return view('openclaw-files.index', [
            'files' => $files,
            'stats' => $stats,
            'title' => 'Gestión de Archivos OpenClaw',
        ]);
    }

    /**
     * Mostrar formulario para editar archivo
     */
    public function edit(string $fileId)
    {
        try {
            $fileData = $this->fileService->readFile($fileId);
            $files = $this->fileService->getAvailableFiles();

            if (!isset($files[$fileId])) {
                return redirect()->route('openclaw-files.index')
                    ->with('error', 'Archivo no encontrado');
            }

            $fileInfo = $files[$fileId];

            return view('openclaw-files.edit', [
                'fileId' => $fileId,
                'fileInfo' => $fileInfo,
                'content' => $fileData['content'],
                'exists' => $fileData['exists'],
                'size' => $fileData['size'] ?? 0,
                'lastModified' => $fileData['last_modified'] ?? null,
                'title' => "Editar {$fileInfo['name']}",
            ]);
        } catch (\Exception $e) {
            return redirect()->route('openclaw-files.index')
                ->with('error', 'Error al cargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar contenido de archivo (solo lectura)
     */
    public function show(string $fileId)
    {
        try {
            $fileData = $this->fileService->readFile($fileId);
            $files = $this->fileService->getAvailableFiles();

            if (!isset($files[$fileId])) {
                return redirect()->route('openclaw-files.index')
                    ->with('error', 'Archivo no encontrado');
            }

            $fileInfo = $files[$fileId];

            return view('openclaw-files.show', [
                'fileId' => $fileId,
                'fileInfo' => $fileInfo,
                'content' => $fileData['content'],
                'exists' => $fileData['exists'],
                'size' => $fileData['size'] ?? 0,
                'lastModified' => $fileData['last_modified'] ?? null,
                'title' => "Ver {$fileInfo['name']}",
            ]);
        } catch (\Exception $e) {
            return redirect()->route('openclaw-files.index')
                ->with('error', 'Error al cargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Guardar cambios en archivo
     */
    public function update(Request $request, string $fileId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'create_backup' => 'boolean',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $content = $request->input('content');
            $createBackup = $request->input('create_backup', true);

            // Validar contenido
            $validation = $this->fileService->validateContent($fileId, $content);
            
            if (!$validation['valid']) {
                return redirect()->back()
                    ->with('error', 'Errores de validación: ' . implode(', ', $validation['errors']))
                    ->with('warnings', $validation['warnings'])
                    ->withInput();
            }

            // Guardar archivo
            $result = $this->fileService->saveFile($fileId, $content, $createBackup);

            // Obtener información actualizada
            $files = $this->fileService->getAvailableFiles();
            $fileInfo = $files[$fileId] ?? null;

            return redirect()->route('openclaw-files.edit', $fileId)
                ->with('success', $result['message'])
                ->with('backup_info', $result['backup_created'] ? 'Se creó un backup antes de guardar.' : null)
                ->with('file_info', $fileInfo);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al guardar el archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtener historial de cambios
     */
    public function history(string $fileId)
    {
        try {
            $history = $this->fileService->getChangeHistory($fileId);
            $files = $this->fileService->getAvailableFiles();

            if (!isset($files[$fileId])) {
                return redirect()->route('openclaw-files.index')
                    ->with('error', 'Archivo no encontrado');
            }

            $fileInfo = $files[$fileId];

            return view('openclaw-files.history', [
                'fileId' => $fileId,
                'fileInfo' => $fileInfo,
                'history' => $history,
                'title' => "Historial de {$fileInfo['name']}",
            ]);
        } catch (\Exception $e) {
            return redirect()->route('openclaw-files.index')
                ->with('error', 'Error al cargar el historial: ' . $e->getMessage());
        }
    }

    /**
     * API: Obtener lista de archivos
     */
    public function apiIndex()
    {
        $files = $this->fileService->getAvailableFiles();
        $stats = $this->fileService->getStats();

        return response()->json([
            'success' => true,
            'data' => [
                'files' => $files,
                'stats' => $stats,
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * API: Leer archivo
     */
    public function apiRead(string $fileId)
    {
        try {
            $fileData = $this->fileService->readFile($fileId);
            $files = $this->fileService->getAvailableFiles();

            if (!isset($files[$fileId])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Archivo no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'file' => $files[$fileId],
                    'content' => $fileData,
                ],
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Guardar archivo
     */
    public function apiSave(Request $request, string $fileId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'create_backup' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $content = $request->input('content');
            $createBackup = $request->input('create_backup', true);

            // Validar contenido
            $validation = $this->fileService->validateContent($fileId, $content);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'errors' => $validation['errors'],
                    'warnings' => $validation['warnings'],
                ], 422);
            }

            // Guardar archivo
            $result = $this->fileService->saveFile($fileId, $content, $createBackup);

            return response()->json([
                'success' => true,
                'data' => $result,
                'warnings' => $validation['warnings'],
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Obtener estadísticas
     */
    public function apiStats()
    {
        $stats = $this->fileService->getStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * API: Validar contenido
     */
    public function apiValidate(Request $request, string $fileId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $content = $request->input('content');
            $validation = $this->fileService->validateContent($fileId, $content);

            return response()->json([
                'success' => true,
                'data' => $validation,
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Obtener historial
     */
    public function apiHistory(string $fileId)
    {
        try {
            $history = $this->fileService->getChangeHistory($fileId);
            $files = $this->fileService->getAvailableFiles();

            if (!isset($files[$fileId])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Archivo no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'file' => $files[$fileId],
                    'history' => $history,
                ],
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vista de estadísticas
     */
    public function stats()
    {
        $stats = $this->fileService->getStats();
        $files = $this->fileService->getAvailableFiles();

        return view('openclaw-files.stats', [
            'stats' => $stats,
            'files' => $files,
            'title' => 'Estadísticas de Archivos OpenClaw',
        ]);
    }

    /**
     * Vista de backup y restauración
     */
    public function backups()
    {
        $backupDir = storage_path('openclaw/backups/files');
        $backups = [];

        if (file_exists($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $backupDir . '/' . $file;
                    $backups[] = [
                        'name' => $file,
                        'path' => $filePath,
                        'size' => filesize($filePath),
                        'modified' => date('Y-m-d H:i:s', filemtime($filePath)),
                    ];
                }
            }

            // Ordenar por fecha (más reciente primero)
            usort($backups, function ($a, $b) {
                return strtotime($b['modified']) - strtotime($a['modified']);
            });
        }

        return view('openclaw-files.backups', [
            'backups' => $backups,
            'backup_dir' => $backupDir,
            'title' => 'Backups de Archivos OpenClaw',
        ]);
    }
}