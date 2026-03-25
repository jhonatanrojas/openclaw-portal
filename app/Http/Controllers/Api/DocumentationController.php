<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Documentation::query();
        
        // Filtros
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        // Paginación
        $perPage = $request->get('per_page', 15);
        $docs = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $docs,
            'message' => 'Documentation retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:installation,configuration,api,agents,troubleshooting',
            'version' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $doc = Documentation::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $doc,
            'message' => 'Documentation created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documentation $documentation)
    {
        return response()->json([
            'success' => true,
            'data' => $documentation,
            'message' => 'Documentation retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documentation $documentation)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category' => 'sometimes|in:installation,configuration,api,agents,troubleshooting',
            'version' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $documentation->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $documentation,
            'message' => 'Documentation updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documentation $documentation)
    {
        $documentation->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Documentation deleted successfully'
        ]);
    }
}
