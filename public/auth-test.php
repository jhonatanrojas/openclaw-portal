<?php
// Endpoint de autenticación directa (sin Laravel, sin middleware)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON input'
        ]);
        exit;
    }
    
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    
    // Credenciales de prueba
    if ($email === 'admin@openclaw.test' && $password === 'password') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Authentication successful (direct PHP)',
            'user' => [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@openclaw.test',
                'role' => 'admin'
            ],
            'token' => 'direct-token-' . time(),
            'timestamp' => date('c'),
            'server_info' => [
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'request_method' => $_SERVER['REQUEST_METHOD'],
                'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'Not set'
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid credentials',
            'received' => ['email' => $email, 'password_length' => strlen($password)]
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Use POST.',
        'allowed_methods' => ['POST', 'OPTIONS']
    ]);
}
?>