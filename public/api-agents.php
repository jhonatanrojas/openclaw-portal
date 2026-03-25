<?php
// API para agentes - Endpoint directo (sin Laravel middleware issues)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Simular base de datos de agentes
$agents = [
    [
        'id' => 1,
        'name' => 'Backend Specialist',
        'type' => 'backend',
        'status' => 'active',
        'task_count' => 12,
        'completed_tasks' => 45,
        'capabilities' => ['laravel', 'api', 'database', 'testing'],
        'last_active' => date('c', time() - 1800), // 30 minutos atrás
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
        'last_active' => date('c', time() - 900), // 15 minutos atrás
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
        'last_active' => date('c', time() - 300), // 5 minutos atrás
        'efficiency' => 0.92
    ],
    [
        'id' => 4,
        'name' => 'Documentation Expert',
        'type' => 'documentation',
        'status' => 'active',
        'task_count' => 3,
        'completed_tasks' => 28,
        'capabilities' => ['technical-writing', 'markdown', 'api-docs', 'tutorials'],
        'last_active' => date('c', time() - 3600), // 1 hora atrás
        'efficiency' => 0.75
    ],
    [
        'id' => 5,
        'name' => 'General Assistant',
        'type' => 'general',
        'status' => 'inactive',
        'task_count' => 0,
        'completed_tasks' => 15,
        'capabilities' => ['support', 'monitoring', 'reports'],
        'last_active' => date('c', time() - 172800), // 2 días atrás
        'efficiency' => 0.60
    ],
    [
        'id' => 6,
        'name' => 'API Specialist',
        'type' => 'backend',
        'status' => 'active',
        'task_count' => 7,
        'completed_tasks' => 31,
        'capabilities' => ['rest-api', 'graphql', 'authentication', 'performance'],
        'last_active' => date('c', time() - 2700), // 45 minutos atrás
        'efficiency' => 0.82
    ],
    [
        'id' => 7,
        'name' => 'UI/UX Designer',
        'type' => 'frontend',
        'status' => 'active',
        'task_count' => 4,
        'completed_tasks' => 22,
        'capabilities' => ['figma', 'prototyping', 'user-research', 'design-system'],
        'last_active' => date('c', time() - 1200), // 20 minutos atrás
        'efficiency' => 0.88
    ],
    [
        'id' => 8,
        'name' => 'Security Analyst',
        'type' => 'devops',
        'status' => 'active',
        'task_count' => 6,
        'completed_tasks' => 18,
        'capabilities' => ['security', 'auditing', 'compliance', 'penetration-testing'],
        'last_active' => date('c', time() - 2400), // 40 minutos atrás
        'efficiency' => 0.70
    ]
];

// Calcular estadísticas
$stats = [
    'total' => count($agents),
    'active' => count(array_filter($agents, fn($a) => $a['status'] === 'active')),
    'busy' => count(array_filter($agents, fn($a) => $a['status'] === 'busy')),
    'inactive' => count(array_filter($agents, fn($a) => $a['status'] === 'inactive')),
    'active_tasks' => array_sum(array_column($agents, 'task_count')),
    'completed_tasks' => array_sum(array_column($agents, 'completed_tasks')),
    'by_type' => [
        'backend' => count(array_filter($agents, fn($a) => $a['type'] === 'backend')),
        'frontend' => count(array_filter($agents, fn($a) => $a['type'] === 'frontend')),
        'devops' => count(array_filter($agents, fn($a) => $a['type'] === 'devops')),
        'documentation' => count(array_filter($agents, fn($a) => $a['type'] === 'documentation')),
        'general' => count(array_filter($agents, fn($a) => $a['type'] === 'general')),
    ]
];

// Manejar diferentes endpoints
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (end($path_parts) === 'api-agents.php' || (count($path_parts) >= 2 && $path_parts[count($path_parts)-2] === 'api')) {
        // Endpoint principal de agentes
        $response = [
            'status' => 'success',
            'message' => 'Agents retrieved successfully',
            'data' => [
                'agents' => $agents,
                'stats' => $stats,
                'pagination' => [
                    'total' => count($agents),
                    'per_page' => 10,
                    'current_page' => 1,
                    'last_page' => 1
                ]
            ],
            'meta' => [
                'timestamp' => date('c'),
                'version' => '1.0.0',
                'endpoint' => '/api-agents.php'
            ]
        ];
        
        echo json_encode($response, JSON_PRETTY_PRINT);
    } elseif (is_numeric(end($path_parts))) {
        // Endpoint individual de agente
        $agent_id = (int)end($path_parts);
        $agent = array_filter($agents, fn($a) => $a['id'] === $agent_id);
        
        if (!empty($agent)) {
            $agent = reset($agent);
            echo json_encode([
                'status' => 'success',
                'message' => 'Agent retrieved successfully',
                'data' => $agent,
                'meta' => ['timestamp' => date('c')]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Agent not found',
                'agent_id' => $agent_id
            ]);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear nuevo agente
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON input'
        ]);
        exit;
    }
    
    // Validar datos mínimos
    if (empty($input['name']) || empty($input['type'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Name and type are required'
        ]);
        exit;
    }
    
    // Crear nuevo agente
    $new_agent = [
        'id' => max(array_column($agents, 'id')) + 1,
        'name' => $input['name'],
        'type' => $input['type'],
        'status' => $input['status'] ?? 'active',
        'task_count' => $input['task_count'] ?? 0,
        'completed_tasks' => $input['completed_tasks'] ?? 0,
        'capabilities' => $input['capabilities'] ?? [],
        'last_active' => date('c'),
        'efficiency' => $input['efficiency'] ?? 0.5
    ];
    
    // En un sistema real, aquí se guardaría en la base de datos
    // $agents[] = $new_agent;
    
    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'Agent created successfully',
        'data' => $new_agent,
        'meta' => [
            'timestamp' => date('c'),
            'created' => true
        ]
    ], JSON_PRETTY_PRINT);
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed',
        'allowed_methods' => ['GET', 'POST', 'OPTIONS']
    ]);
}
?>