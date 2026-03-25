<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agent::query();
        
        // Filtros
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Ordenar por actividad
        $query->orderBy('last_active_at', 'desc');
        
        // Paginación
        $perPage = $request->get('per_page', 15);
        $agents = $query->paginate($perPage);
        
        // Estadísticas
        $stats = [
            'total' => Agent::count(),
            'active' => Agent::where('status', 'active')->count(),
            'busy' => Agent::where('status', 'busy')->count(),
            'by_type' => Agent::selectRaw('type, count(*) as count')->groupBy('type')->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $agents,
            'stats' => $stats,
            'message' => 'Agents retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:backend,frontend,devops,documentation,general',
            'status' => 'in:active,inactive,busy',
            'capabilities' => 'nullable|array'
        ]);
        
        $agent = Agent::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Agent created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        // Cargar tareas relacionadas
        $agent->load('tasks');
        
        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Agent retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:backend,frontend,devops,documentation,general',
            'status' => 'sometimes|in:active,inactive,busy',
            'capabilities' => 'nullable|array',
            'last_active_at' => 'sometimes|date'
        ]);
        
        $agent->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Agent updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Agent deleted successfully'
        ]);
    }
    
    /**
     * Update agent activity
     */
    public function updateActivity(Agent $agent)
    {
        $agent->update(['last_active_at' => now()]);
        
        return response()->json([
            'success' => true,
            'data' => $agent,
            'message' => 'Agent activity updated successfully'
        ]);
    }
}
