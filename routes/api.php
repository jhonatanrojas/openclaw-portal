<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rutas públicas (sin autenticación)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', [AuthController::class, 'test']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Aquí irían otras rutas protegidas de la API
    Route::get('/agents', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Agents API (protected)',
            'data' => [
                'agents' => [
                    ['id' => 1, 'name' => 'Backend Specialist', 'status' => 'active'],
                    ['id' => 2, 'name' => 'Frontend Developer', 'status' => 'active'],
                    ['id' => 3, 'name' => 'DevOps Engineer', 'status' => 'busy'],
                ]
            ]
        ]);
    });
});
