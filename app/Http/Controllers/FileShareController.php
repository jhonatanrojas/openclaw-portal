<?php

namespace App\Http\Controllers;

use App\Services\FileShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileShareController extends Controller
{
    protected FileShareService $fileShareService;

    public function __construct(FileShareService $fileShareService)
    {
        $this->fileShareService = $fileShareService;
    }

    /**
     * Página principal del file share
     */
    public function index()
    {
        try {
            $categories = $this->fileShareService->getCategories();
            $stats = $this->fileShareService->getStats();

            return view('file-share.index', [
                'categories' => $categories,
                'stats' => $stats,
                'title' => 'OpenClaw File Share',
            ]);
        } catch (\Exception $e) {
            // Fallback a vista simple si hay error
            \Log::error('Error en FileShareController@index: ' . $e->getMessage());
            
            return view('file-share.simple-index', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Ver archivos de una categoría
     */
    public function category(string $category)
    {
        try {
            $categories = $this->fileShareService->getCategories();
            
            if (!isset($categories[$category])) {
                return redirect()->route('file-share.index')
                    ->with('error', 'Categoría no encontrada');
            }

            $files = $this->fileShareService->getFiles($category);
            $categoryInfo = $categories[$category];

            return view('file-share.category', [
                'category' => $category,
                'categoryInfo' => $categoryInfo,
                'files' => $files,
                'title' => "{$categoryInfo['name']} - OpenClaw File Share",
            ]);
        } catch (\Exception $e) {
            return redirect()->route('file-share.index')
                ->with('error', 'Error al cargar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalles de un archivo
     */
    public function show(string $category, string $filename)
    {
        try {
            $details = $this->fileShareService->getFile($category, $filename);
            
            if (!$details) {
                return redirect()->route('file-share.category', $category)
                    ->with('error', 'Archivo no encontrado');
            }
            
            $categories = $this->fileShareService->getCategories();

            return view('file-share.show', [
                'details' => $details,
                'categoryInfo' => $categories[$category] ?? null,
                'title' => "{$filename} - OpenClaw File Share",
            ]);
        } catch (\Exception $e) {
            return redirect()->route('file-share.category', $category)
                ->with('error', 'Error al cargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Subir archivo (formulario)
     */
    public function create(string $category)
    {
        $categories = $this->fileShareService->getCategories();
        
        if (!isset($categories[$category])) {
            return redirect()->route('file-share.index')
                ->with('error', 'Categoría no encontrada');
        }

        return view('file-share.upload', [
            'category' => $category,
            'categoryInfo' => $categories[$category],
            'title' => "Subir archivo - {$categories[$category]['name']}",
        ]);
    }

    /**
     * Procesar subida de archivo
     */
    public function store(Request $request, string $category)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file',
                'description' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $result = $this->fileShareService->uploadFile(
                $category,
                $request->file('file'),
                $request->input('description')
            );

            return redirect()->route('file-share.category', $category)
                ->with('success', $result['message'])
                ->with('uploaded_file', $result['file']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al subir el archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar archivo
     */
    public function destroy(string $category, string $filename)
    {
        try {
            $result = $this->fileShareService->deleteFile($category, $filename);

            return redirect()->route('file-share.category', $category)
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->route('file-share.category', $category)
                ->with('error', 'Error al eliminar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Descargar archivo
     */
    public function download(string $category, string $filename)
    {
        try {
            $details = $this->fileShareService->getFile($category, $filename);
            
            if (!$details) {
                return redirect()->route('file-share.category', $category)
                    ->with('error', 'Archivo no encontrado');
            }

            return response()->download($details['path'], $filename);
        } catch (\Exception $e) {
            return redirect()->route('file-share.category', $category)
                ->with('error', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * API: Obtener categorías
     */
    public function apiCategories()
    {
        $categories = $this->fileShareService->getCategories();
        $stats = $this->fileShareService->getStats();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $categories,
                'stats' => $stats,
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * API: Obtener archivos de categoría
     */
    public function apiFiles(string $category)
    {
        try {
            $files = $this->fileShareService->getFiles($category);
            $categories = $this->fileShareService->getCategories();

            if (!isset($categories[$category])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Categoría no encontrada',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $categories[$category],
                    'files' => $files,
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
     * API: Obtener detalles de archivo
     */
    public function apiFileDetails(string $category, string $filename)
    {
        try {
            $details = $this->fileShareService->getFile($category, $filename);
            
            if (!$details) {
                return response()->json([
                    'success' => false,
                    'error' => 'Archivo no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $details,
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
     * API: Subir archivo
     */
    public function apiUpload(Request $request, string $category)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file',
                'description' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $result = $this->fileShareService->uploadFile(
                $category,
                $request->file('file'),
                $request->input('description')
            );

            return response()->json([
                'success' => true,
                'data' => $result,
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
     * API: Eliminar archivo
     */
    public function apiDelete(string $category, string $filename)
    {
        try {
            $result = $this->fileShareService->deleteFile($category, $filename);

            return response()->json([
                'success' => true,
                'data' => $result,
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
     * API: Estadísticas
     */
    public function apiStats()
    {
        $stats = $this->fileShareService->getStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toISOString(),
        ]);
    }
}