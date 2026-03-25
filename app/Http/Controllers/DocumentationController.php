<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the documentation.
     */
    public function index(Request $request)
    {
        $query = Documentation::query();
        
        // Filtrar por categoría si se especifica
        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }
        
        // Filtrar por búsqueda si se especifica
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Solo documentos activos por defecto
        if (!$request->has('show_inactive')) {
            $query->active();
        }
        
        $documentations = $query->latest()->paginate(15);
        
        // Estadísticas por categoría
        $categoryStats = Documentation::active()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();
        
        return view('documentation.index', compact('documentations', 'categoryStats'));
    }
    
    /**
     * Display documentation by category.
     */
    public function byCategory($category)
    {
        $documentations = Documentation::active()
            ->byCategory($category)
            ->latest()
            ->paginate(15);
        
        return view('documentation.category', [
            'documentations' => $documentations,
            'category' => $category,
            'categoryName' => $this->getCategoryName($category)
        ]);
    }
    
    /**
     * Search documentation.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);
        
        $searchTerm = $request->q;
        $documentations = Documentation::active()
            ->search($searchTerm)
            ->latest()
            ->paginate(15);
        
        return view('documentation.search', [
            'documentations' => $documentations,
            'searchTerm' => $searchTerm,
            'resultsCount' => $documentations->total()
        ]);
    }
    
    /**
     * Show the form for creating a new documentation.
     */
    public function create()
    {
        $categories = $this->getCategories();
        
        return view('documentation.create', compact('categories'));
    }
    
    /**
     * Store a newly created documentation in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys($this->getCategories())),
            'version' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $documentation = Documentation::create([
            'title' => $request->title,
            'slug' => Documentation::generateSlug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'version' => $request->version ?? '1.0',
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);
        
        return redirect()->route('documentation.show', $documentation->slug)
            ->with('success', 'Documentación creada exitosamente.');
    }
    
    /**
     * Display the specified documentation.
     */
    public function show(Documentation $documentation)
    {
        // Incrementar contador de vistas (podría implementarse después)
        // $documentation->increment('views');
        
        // Obtener documentos relacionados por categoría
        $related = Documentation::active()
            ->byCategory($documentation->category)
            ->where('id', '!=', $documentation->id)
            ->limit(5)
            ->get();
        
        return view('documentation.show', compact('documentation', 'related'));
    }
    
    /**
     * Show the form for editing the specified documentation.
     */
    public function edit(Documentation $documentation)
    {
        $categories = $this->getCategories();
        
        return view('documentation.edit', compact('documentation', 'categories'));
    }
    
    /**
     * Update the specified documentation in storage.
     */
    public function update(Request $request, Documentation $documentation)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:' . implode(',', array_keys($this->getCategories())),
            'version' => 'nullable|string|max:20',
            'is_active' => 'boolean'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Actualizar slug solo si cambió el título
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'version' => $request->version ?? $documentation->version,
            'is_active' => $request->has('is_active') ? $request->is_active : $documentation->is_active,
        ];
        
        if ($request->title !== $documentation->title) {
            $data['slug'] = Documentation::generateSlug($request->title);
        }
        
        $documentation->update($data);
        
        return redirect()->route('documentation.show', $documentation->slug)
            ->with('success', 'Documentación actualizada exitosamente.');
    }
    
    /**
     * Remove the specified documentation from storage.
     */
    public function destroy(Documentation $documentation)
    {
        $documentation->delete();
        
        return redirect()->route('documentation.index')
            ->with('success', 'Documentación eliminada exitosamente.');
    }
    
    /**
     * Get available categories with their display names.
     */
    private function getCategories()
    {
        return [
            'installation' => 'Instalación',
            'configuration' => 'Configuración',
            'api' => 'API',
            'agents' => 'Agentes',
            'troubleshooting' => 'Solución de Problemas',
            'general' => 'General'
        ];
    }
    
    /**
     * Get category display name.
     */
    private function getCategoryName($category)
    {
        $categories = $this->getCategories();
        return $categories[$category] ?? ucfirst($category);
    }
}
