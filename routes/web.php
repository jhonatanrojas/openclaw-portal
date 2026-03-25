<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

// Ruta de prueba simple
Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Test route working',
        'session_id' => session_id(),
        'csrf_token' => csrf_token(),
        'app_url' => config('app.url'),
        'session_domain' => config('session.domain'),
    ]);
});

// Ruta de login de prueba (sin CSRF)
Route::post('/test-login', function () {
    $credentials = request()->only('email', 'password');
    
    if ($credentials['email'] === 'admin@openclaw.test' && $credentials['password'] === 'password') {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'redirect' => '/simple-dashboard',
            'user' => [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@openclaw.test'
            ]
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials'
    ], 401);
});

// Login simple (vista)
Route::get('/simple-login', function () {
    return view('simple-login');
});

// Dashboard simple (vista)
Route::get('/simple-dashboard', function () {
    return view('simple-dashboard', [
        'user' => (object)[
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@openclaw.test'
        ]
    ]);
});

// Comentar temporalmente las rutas de autenticación de Breeze
// require __DIR__.'/auth.php';

// Nueva ruta para gestión de agentes - Versión de prueba
Route::get('/agents-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Ruta de agentes funcionando',
        'data' => [
            'agents_count' => 6,
            'endpoint' => '/agents'
        ]
    ]);
});

// Nueva ruta para gestión de agentes - Vista simplificada
Route::get('/agents', function () {
    // Datos de ejemplo para la vista
    $agents = [
        (object)[
            'id' => 1,
            'name' => 'Backend Specialist',
            'type' => 'backend',
            'status' => 'active',
            'task_count' => 12,
            'completed_tasks' => 45
        ],
        (object)[
            'id' => 2,
            'name' => 'Frontend Developer',
            'type' => 'frontend',
            'status' => 'active',
            'task_count' => 8,
            'completed_tasks' => 38
        ],
        (object)[
            'id' => 3,
            'name' => 'DevOps Engineer',
            'type' => 'devops',
            'status' => 'busy',
            'task_count' => 5,
            'completed_tasks' => 52
        ],
        (object)[
            'id' => 4,
            'name' => 'Documentation Writer',
            'type' => 'documentation',
            'status' => 'active',
            'task_count' => 3,
            'completed_tasks' => 28
        ],
        (object)[
            'id' => 5,
            'name' => 'General Assistant',
            'type' => 'general',
            'status' => 'inactive',
            'task_count' => 0,
            'completed_tasks' => 15
        ],
        (object)[
            'id' => 6,
            'name' => 'API Specialist',
            'type' => 'backend',
            'status' => 'active',
            'task_count' => 7,
            'completed_tasks' => 31
        ]
    ];

    $stats = [
        'total' => count($agents),
        'active' => count(array_filter($agents, fn($a) => $a->status === 'active')),
        'busy' => count(array_filter($agents, fn($a) => $a->status === 'busy')),
        'active_tasks' => array_sum(array_column($agents, 'task_count'))
    ];

    return view('agents.index', [
        'agents' => $agents,
        'stats' => $stats,
        'title' => 'Gestión de Agentes'
    ]);
})->name('agents.index');

// Endpoint de login SIN CSRF para pruebas
Route::post('/api/login-test', function () {
    $credentials = request()->only('email', 'password');
    
    if ($credentials['email'] === 'admin@openclaw.test' && $credentials['password'] === 'password') {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful (API test)',
            'user' => [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@openclaw.test'
            ],
            'session_id' => session_id(),
            'csrf_token' => csrf_token()
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials'
    ], 401);
});

// Rutas de prueba CSRF
Route::get('/test-csrf', function () {
    return file_get_contents(storage_path('openclaw/csrf-test.php'));
});

Route::post('/test-csrf', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF test passed!',
        'data' => request()->all(),
        'session_id' => session_id(),
        'csrf_token' => csrf_token()
    ]);
});

Route::get('/test-csrf-result', function () {
    return response()->json([
        'status' => 'info',
        'message' => 'CSRF test endpoint',
        'session_active' => session_status() === PHP_SESSION_ACTIVE,
        'session_id' => session_id(),
        'csrf_token' => csrf_token(),
        'headers' => getallheaders()
    ]);
});

// Sistema de autenticación simple (sin CSRF issues)
Route::prefix('simple-auth')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'showLoginForm'])->name('simple.login');
    Route::post('/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'login'])->name('simple.login.post');
    Route::get('/dashboard', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'dashboard'])->name('simple.dashboard');
    Route::get('/logout', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'logout'])->name('simple.logout');
    Route::post('/api/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'apiLogin'])->name('simple.api.login');
});

// Redirección para login simple
Route::get('/simple-login', function () {
    return redirect('/simple-auth/login');
});

Route::get('/simple-dashboard', function () {
    return redirect('/simple-auth/dashboard');
});

// Ruta de prueba CSRF funcional
Route::get('/csrf-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF test endpoint',
        'csrf_token' => csrf_token(),
        'session_id' => session_id(),
        'app_env' => config('app.env'),
        'session_domain' => config('session.domain'),
        'session_driver' => config('session.driver'),
    ]);
});

Route::post('/csrf-test-post', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF POST successful!',
        'received_data' => request()->all(),
        'session_id' => session_id(),
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Ruta de prueba SIN middleware web
Route::post('/no-csrf-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'POST sin CSRF middleware!',
        'received_data' => request()->all(),
        'timestamp' => now()->toDateTimeString(),
    ]);
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Ruta de prueba SIN NINGÚN middleware
Route::post('/no-middleware-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'POST sin NINGÚN middleware!',
        'received_data' => request()->all(),
        'timestamp' => now()->toDateTimeString(),
        'session_id' => session_id(),
    ]);
})->withoutMiddleware(\App\Http\Middleware\EncryptCookies::class)
  ->withoutMiddleware(\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class)
  ->withoutMiddleware(\Illuminate\Session\Middleware\StartSession::class)
  ->withoutMiddleware(\Illuminate\View\Middleware\ShareErrorsFromSession::class)
  ->withoutMiddleware(\App\Http\Middleware\NoCsrfMiddleware::class)
  ->withoutMiddleware(\Illuminate\Routing\Middleware\SubstituteBindings::class);

// Ruta para verificar middleware
Route::get('/check-middleware', function () {
    $middleware = app('router')->getCurrentRoute()->gatherMiddleware();
    return response()->json([
        'status' => 'success',
        'middleware' => $middleware,
        'session_id' => session_id(),
        'csrf_token' => csrf_token(),
        'app_env' => config('app.env'),
    ]);
});

// Ruta de login alternativa SIN middleware web
Route::post('/api/v2/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    // Credenciales de prueba
    $testCredentials = [
        'admin@openclaw.test' => 'password',
        'user@openclaw.test' => 'password123',
    ];
    
    // Verificar credenciales de prueba
    if (isset($testCredentials[$credentials['email']]) && 
        $credentials['password'] === $testCredentials[$credentials['email']]) {
        
        // Generar token simple
        $token = 'simple-token-' . md5($credentials['email'] . time());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful (v2)',
            'token' => $token,
            'user' => [
                'id' => 1,
                'name' => $credentials['email'] === 'admin@openclaw.test' ? 'Administrator' : 'User',
                'email' => $credentials['email'],
                'role' => 'admin'
            ],
            'redirect' => '/dashboard'
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials'
    ], 401);
})->withoutMiddleware(['web']);

// Dashboard routes
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

// Simple auth routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'login'])->name('auth.login.post');
    Route::post('/api/login', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'apiLogin'])->name('auth.api.login');
    Route::get('/verify', [\App\Http\Controllers\Auth\SimpleAuthController::class, 'verifyToken'])->name('auth.verify');
});

// Documentation routes (TASK-007)
Route::resource('documentation', \App\Http\Controllers\DocumentationController::class);
Route::get('/documentation/category/{category}', [\App\Http\Controllers\DocumentationController::class, 'byCategory'])
    ->name('documentation.by-category');
Route::get('/documentation/search', [\App\Http\Controllers\DocumentationController::class, 'search'])
    ->name('documentation.search');

// API routes (stateless)
Route::prefix('api')->group(function () {
    Route::get('/status', [\App\Http\Controllers\Api\SimpleApiController::class, 'status']);
    Route::get('/stats', [\App\Http\Controllers\Api\SimpleApiController::class, 'stats']);
    Route::get('/agents', [\App\Http\Controllers\Api\SimpleApiController::class, 'agents']);
    Route::get('/protected', [\App\Http\Controllers\Api\SimpleApiController::class, 'protectedEndpoint']);
});

// OpenClaw Files Management (TASK-013)
Route::prefix('openclaw-files')->group(function () {
    Route::get('/', [\App\Http\Controllers\OpenClawFileController::class, 'index'])->name('openclaw-files.index');
    Route::get('/stats', [\App\Http\Controllers\OpenClawFileController::class, 'stats'])->name('openclaw-files.stats');
    Route::get('/backups', [\App\Http\Controllers\OpenClawFileController::class, 'backups'])->name('openclaw-files.backups');
    
    // File operations
    Route::get('/{fileId}', [\App\Http\Controllers\OpenClawFileController::class, 'show'])->name('openclaw-files.show');
    Route::get('/{fileId}/edit', [\App\Http\Controllers\OpenClawFileController::class, 'edit'])->name('openclaw-files.edit');
    Route::put('/{fileId}', [\App\Http\Controllers\OpenClawFileController::class, 'update'])->name('openclaw-files.update');
    Route::get('/{fileId}/history', [\App\Http\Controllers\OpenClawFileController::class, 'history'])->name('openclaw-files.history');
});

// API for OpenClaw Files
Route::prefix('api/openclaw-files')->group(function () {
    Route::get('/', [\App\Http\Controllers\OpenClawFileController::class, 'apiIndex']);
    Route::get('/stats', [\App\Http\Controllers\OpenClawFileController::class, 'apiStats']);
    Route::get('/{fileId}', [\App\Http\Controllers\OpenClawFileController::class, 'apiRead']);
    Route::put('/{fileId}', [\App\Http\Controllers\OpenClawFileController::class, 'apiSave']);
    Route::get('/{fileId}/history', [\App\Http\Controllers\OpenClawFileController::class, 'apiHistory']);
    Route::post('/{fileId}/validate', [\App\Http\Controllers\OpenClawFileController::class, 'apiValidate']);
});

// Dev Squad Dashboard (multi-agent)
Route::prefix('devsquad')->group(function () {
    Route::get('/', [\App\Http\Controllers\DevSquadController::class, 'index'])->name('devsquad.index');
    Route::get('/health', [\App\Http\Controllers\DevSquadController::class, 'health'])->name('devsquad.health');
    Route::get('/api/stream', [\App\Http\Controllers\DevSquadController::class, 'stream'])->name('devsquad.stream');
    Route::get('/api/files', [\App\Http\Controllers\DevSquadController::class, 'files'])->name('devsquad.files');
    Route::get('/files/view', [\App\Http\Controllers\DevSquadController::class, 'viewFile'])->name('devsquad.files.view');
    Route::get('/files/download', [\App\Http\Controllers\DevSquadController::class, 'downloadFile'])->name('devsquad.files.download');
    Route::get('/api/{path}', [\App\Http\Controllers\DevSquadController::class, 'proxyGet'])->where('path', '.*');
    Route::post('/api/{path}', [\App\Http\Controllers\DevSquadController::class, 'proxyPost'])->where('path', '.*');
    Route::put('/api/{path}', [\App\Http\Controllers\DevSquadController::class, 'proxyPut'])->where('path', '.*');
});

// File Share Routes (Archivos que OpenClaw genera y comparte)
Route::prefix('file-share')->group(function () {
    Route::get('/', [\App\Http\Controllers\FileShareController::class, 'index'])->name('file-share.index');
    Route::get('/{category}', [\App\Http\Controllers\FileShareController::class, 'category'])->name('file-share.category');
    Route::get('/{category}/{filename}', [\App\Http\Controllers\FileShareController::class, 'show'])->name('file-share.show');
    Route::get('/{category}/{filename}/download', [\App\Http\Controllers\FileShareController::class, 'download'])->name('file-share.download');
    
    // Upload routes
    Route::get('/{category}/upload', [\App\Http\Controllers\FileShareController::class, 'create'])->name('file-share.create');
    Route::post('/{category}/upload', [\App\Http\Controllers\FileShareController::class, 'store'])->name('file-share.store');
    
    // Delete route
    Route::delete('/{category}/{filename}', [\App\Http\Controllers\FileShareController::class, 'destroy'])->name('file-share.destroy');
});

// API for File Share
Route::prefix('api/file-share')->group(function () {
    Route::get('/categories', [\App\Http\Controllers\FileShareController::class, 'apiCategories']);
    Route::get('/stats', [\App\Http\Controllers\FileShareController::class, 'apiStats']);
    Route::get('/{category}/files', [\App\Http\Controllers\FileShareController::class, 'apiFiles']);
    Route::get('/{category}/{filename}', [\App\Http\Controllers\FileShareController::class, 'apiFileDetails']);
    Route::post('/{category}/upload', [\App\Http\Controllers\FileShareController::class, 'apiUpload']);
    Route::delete('/{category}/{filename}', [\App\Http\Controllers\FileShareController::class, 'apiDelete']);
});

// Task Management System Routes
Route::prefix('tasks')->group(function () {
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::get('/create', [\App\Http\Controllers\TaskController::class, 'create'])->name('tasks.create');
    Route::post('/', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{task}', [\App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
    Route::get('/{task}/edit', [\App\Http\Controllers\TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/{task}/auto-assign', [\App\Http\Controllers\TaskController::class, 'autoAssign'])->name('tasks.auto-assign');
    Route::get('/my-tasks', [\App\Http\Controllers\TaskController::class, 'myTasks'])->name('tasks.my-tasks');
    Route::get('/stats', [\App\Http\Controllers\TaskController::class, 'stats'])->name('tasks.stats');
});

// Task Assignment Routes
Route::prefix('task-assignments')->group(function () {
    Route::get('/', [\App\Http\Controllers\TaskAssignmentController::class, 'index'])->name('task-assignments.index');
    Route::post('/', [\App\Http\Controllers\TaskAssignmentController::class, 'store'])->name('task-assignments.store');
    Route::get('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'show'])->name('task-assignments.show');
    Route::put('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'update'])->name('task-assignments.update');
    Route::delete('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'destroy'])->name('task-assignments.destroy');
    Route::post('/{assignment}/accept', [\App\Http\Controllers\TaskAssignmentController::class, 'accept'])->name('task-assignments.accept');
    Route::post('/{assignment}/reject', [\App\Http\Controllers\TaskAssignmentController::class, 'reject'])->name('task-assignments.reject');
    Route::post('/{assignment}/complete', [\App\Http\Controllers\TaskAssignmentController::class, 'complete'])->name('task-assignments.complete');
    Route::get('/my-assignments', [\App\Http\Controllers\TaskAssignmentController::class, 'myAssignments'])->name('task-assignments.my-assignments');
    Route::get('/stats', [\App\Http\Controllers\TaskAssignmentController::class, 'stats'])->name('task-assignments.stats');
});

// API Routes for Task Management
Route::prefix('api/tasks')->group(function () {
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\TaskController::class, 'store']);
    Route::get('/{task}', [\App\Http\Controllers\TaskController::class, 'show']);
    Route::put('/{task}', [\App\Http\Controllers\TaskController::class, 'update']);
    Route::delete('/{task}', [\App\Http\Controllers\TaskController::class, 'destroy']);
    Route::get('/{task}/auto-assign', [\App\Http\Controllers\TaskController::class, 'autoAssign']);
    Route::get('/my-tasks', [\App\Http\Controllers\TaskController::class, 'myTasks']);
    Route::get('/stats', [\App\Http\Controllers\TaskController::class, 'stats']);
});

// API Routes for Task Assignments
Route::prefix('api/task-assignments')->group(function () {
    Route::get('/', [\App\Http\Controllers\TaskAssignmentController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\TaskAssignmentController::class, 'store']);
    Route::get('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'show']);
    Route::put('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'update']);
    Route::delete('/{assignment}', [\App\Http\Controllers\TaskAssignmentController::class, 'destroy']);
    Route::post('/{assignment}/accept', [\App\Http\Controllers\TaskAssignmentController::class, 'accept']);
    Route::post('/{assignment}/reject', [\App\Http\Controllers\TaskAssignmentController::class, 'reject']);
    Route::post('/{assignment}/complete', [\App\Http\Controllers\TaskAssignmentController::class, 'complete']);
    Route::get('/my-assignments', [\App\Http\Controllers\TaskAssignmentController::class, 'myAssignments']);
    Route::get('/stats', [\App\Http\Controllers\TaskAssignmentController::class, 'stats']);
});
