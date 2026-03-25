<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimpleApiController extends Controller
{
    /**
     * API status
     */
    public function status()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'OpenClaw Portal API is running',
            'version' => '1.0.0',
            'timestamp' => now()->toDateTimeString(),
            'environment' => app()->environment(),
            'endpoints' => [
                'GET /api/status' => 'API status',
                'GET /api/stats' => 'System statistics',
                'GET /api/agents' => 'List agents',
                'POST /api/auth/login' => 'Authenticate',
                'GET /api/auth/verify' => 'Verify token'
            ]
        ]);
    }
    
    /**
     * System statistics
     */
    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'system' => [
                    'uptime' => '24 hours',
                    'memory_usage' => '45%',
                    'cpu_usage' => '12%',
                    'disk_usage' => '65%'
                ],
                'agents' => [
                    'total' => 8,
                    'active' => 6,
                    'inactive' => 1,
                    'busy' => 1
                ],
                'tasks' => [
                    'total' => 45,
                    'completed' => 189,
                    'pending' => 12,
                    'in_progress' => 33
                ],
                'projects' => [
                    'total' => 3,
                    'active' => 2,
                    'completed' => 1
                ]
            ]
        ]);
    }
    
    /**
     * List agents
     */
    public function agents()
    {
        $agents = [
            [
                'id' => 1,
                'name' => 'Backend Specialist',
                'type' => 'backend',
                'status' => 'active',
                'task_count' => 12,
                'completed_tasks' => 45,
                'capabilities' => ['laravel', 'api', 'database', 'testing'],
                'last_active' => now()->subMinutes(30)->toDateTimeString(),
                'efficiency' => 0.85
            ],
            [
                'id' => 2,
                'name' => 'Frontend Developer',
                'type' => 'frontend',
                'status' => 'active',
                'task_count' => 8,
                'completed_tasks' => 38,
                'capabilities' => ['vue', 'react', 'tailwind', 'javascript'],
                'last_active' => now()->subMinutes(15)->toDateTimeString(),
                'efficiency' => 0.79
            ],
            [
                'id' => 3,
                'name' => 'DevOps Engineer',
                'type' => 'devops',
                'status' => 'busy',
                'task_count' => 5,
                'completed_tasks' => 52,
                'capabilities' => ['docker', 'kubernetes', 'ci-cd', 'monitoring'],
                'last_active' => now()->subMinutes(5)->toDateTimeString(),
                'efficiency' => 0.92
            ]
        ];
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'agents' => $agents,
                'total' => count($agents),
                'page' => 1,
                'per_page' => 10
            ]
        ]);
    }
    
    /**
     * Protected endpoint example
     */
    public function protectedEndpoint(Request $request)
    {
        // En una implementación real, verificaríamos el token aquí
        return response()->json([
            'status' => 'success',
            'message' => 'This is a protected endpoint',
            'user' => [
                'id' => 1,
                'name' => 'API User',
                'access_level' => 'admin'
            ],
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}
