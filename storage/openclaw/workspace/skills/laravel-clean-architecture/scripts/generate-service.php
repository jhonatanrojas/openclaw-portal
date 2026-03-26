#!/usr/bin/env php
<?php

/**
 * Generador de Service + Repository para arquitectura limpia Laravel
 * 
 * Uso: php generate-service.php <ModelName> [opciones]
 * 
 * Ejemplo: php generate-service.php Expense --refactor
 */

// Configuración
$templatesDir = __DIR__ . '/../references';
$outputDir = getcwd();

// Colores para output
define('COLOR_RESET', "\033[0m");
define('COLOR_GREEN', "\033[32m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_RED', "\033[31m");
define('COLOR_BLUE', "\033[34m");
define('COLOR_CYAN', "\033[36m");

// Mostrar banner
function showBanner()
{
    echo COLOR_CYAN . "=========================================\n";
    echo "Laravel Clean Architecture Generator\n";
    echo "=========================================\n" . COLOR_RESET;
}

// Mostrar ayuda
function showHelp()
{
    echo "Uso: php generate-service.php <ModelName> [opciones]\n\n";
    echo "Opciones:\n";
    echo "  --refactor           Refactorizar modelo existente\n";
    echo "  --with-dto           Generar DTOs (Create/Update)\n";
    echo "  --with-tests         Generar tests para Service y Repository\n";
    echo "  --with-contracts     Generar interfaces/contratos\n";
    echo "  --output=DIR         Directorio de salida (default: current)\n";
    echo "  --force              Sobrescribir archivos existentes\n";
    echo "  --help               Mostrar esta ayuda\n\n";
    echo "Ejemplos:\n";
    echo "  php generate-service.php Expense --refactor\n";
    echo "  php generate-service.php Invoice --with-dto --with-tests\n";
    echo "  php generate-service.php User --with-contracts --output=app\n";
}

// Procesar argumentos
function parseArguments($argv)
{
    $options = [
        'model' => null,
        'refactor' => false,
        'with-dto' => false,
        'with-tests' => false,
        'with-contracts' => false,
        'output' => getcwd(),
        'force' => false,
    ];
    
    if (count($argv) < 2) {
        return $options;
    }
    
    // Primer argumento es el nombre del modelo
    $options['model'] = $argv[1];
    
    // Procesar opciones
    for ($i = 2; $i < count($argv); $i++) {
        $arg = $argv[$i];
        
        if ($arg === '--refactor') {
            $options['refactor'] = true;
        } elseif ($arg === '--with-dto') {
            $options['with-dto'] = true;
        } elseif ($arg === '--with-tests') {
            $options['with-tests'] = true;
        } elseif ($arg === '--with-contracts') {
            $options['with-contracts'] = true;
        } elseif ($arg === '--force') {
            $options['force'] = true;
        } elseif ($arg === '--help') {
            showHelp();
            exit(0);
        } elseif (str_starts_with($arg, '--output=')) {
            $options['output'] = substr($arg, 9);
        }
    }
    
    return $options;
}

// Crear directorios si no existen
function ensureDirectories($options)
{
    $dirs = [
        $options['output'] . '/app/Services',
        $options['output'] . '/app/Repositories',
    ];
    
    if ($options['with-dto']) {
        $dirs[] = $options['output'] . '/app/DTOs';
    }
    
    if ($options['with-contracts']) {
        $dirs[] = $options['output'] . '/app/Contracts';
    }
    
    if ($options['with-tests']) {
        $dirs[] = $options['output'] . '/tests/Feature/Services';
        $dirs[] = $options['output'] . '/tests/Feature/Repositories';
    }
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo COLOR_GREEN . "✓ Directorio creado: $dir\n" . COLOR_RESET;
        }
    }
}

// Procesar plantilla
function processTemplate($template, $modelName)
{
    $replacements = [
        '{{ModelName}}' => $modelName,
        '{{modelName}}' => lcfirst($modelName),
        '{{MODEL_NAME}}' => strtoupper($modelName),
    ];
    
    return str_replace(
        array_keys($replacements),
        array_values($replacements),
        $template
    );
}

// Generar archivo desde plantilla
function generateFile($templatePath, $outputPath, $modelName, $force = false)
{
    if (file_exists($outputPath) && !$force) {
        echo COLOR_YELLOW . "⚠ Archivo ya existe: $outputPath (usa --force para sobrescribir)\n" . COLOR_RESET;
        return false;
    }
    
    if (!file_exists($templatePath)) {
        echo COLOR_RED . "✗ Plantilla no encontrada: $templatePath\n" . COLOR_RESET;
        return false;
    }
    
    $template = file_get_contents($templatePath);
    $content = processTemplate($template, $modelName);
    
    file_put_contents($outputPath, $content);
    echo COLOR_GREEN . "✓ Archivo generado: $outputPath\n" . COLOR_RESET;
    
    return true;
}

// Generar Service
function generateService($options)
{
    $modelName = $options['model'];
    $outputDir = $options['output'];
    
    $templatePath = __DIR__ . '/../references/service-template.md';
    $outputPath = $outputDir . "/app/Services/{$modelName}Service.php";
    
    return generateFile($templatePath, $outputPath, $modelName, $options['force']);
}

// Generar Repository
function generateRepository($options)
{
    $modelName = $options['model'];
    $outputDir = $options['output'];
    
    // Leer plantilla de repository
    $template = <<<'TEMPLATE'
<?php

namespace App\Repositories;

use App\Models\{{ModelName}};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class {{ModelName}}Repository
{
    public function __construct(protected {{ModelName}} $model) {}
    
    /**
     * Obtener todos los registros con filtros
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        
        // Aplicar filtros
        foreach ($filters as $key => $value) {
            if ($value !== null && $value !== '') {
                $query->where($key, $value);
            }
        }
        
        return $query->paginate($perPage);
    }
    
    /**
     * Obtener registro por ID
     */
    public function find($id, array $with = []): ?{{ModelName}}
    {
        $query = $this->model;
        
        if (!empty($with)) {
            $query = $query->with($with);
        }
        
        return $query->find($id);
    }
    
    /**
     * Obtener registro por ID o fallar
     */
    public function findOrFail($id, array $with = []): {{ModelName}}
    {
        $query = $this->model;
        
        if (!empty($with)) {
            $query = $query->with($with);
        }
        
        return $query->findOrFail($id);
    }
    
    /**
     * Crear nuevo registro
     */
    public function create(array $data): {{ModelName}}
    {
        return $this->model->create($data);
    }
    
    /**
     * Actualizar registro
     */
    public function update({{ModelName}} $model, array $data): {{ModelName}}
    {
        $model->update($data);
        return $model->fresh();
    }
    
    /**
     * Eliminar registro (soft delete)
     */
    public function delete({{ModelName}} $model, array $data = []): {{ModelName}}
    {
        if (!empty($data)) {
            $model->update($data);
        }
        
        $model->delete();
        return $model->fresh();
    }
    
    /**
     * Contar registros con filtros
     */
    public function count(array $filters = []): int
    {
        $query = $this->model->newQuery();
        
        foreach ($filters as $key => $value) {
            if ($value !== null && $value !== '') {
                $query->where($key, $value);
            }
        }
        
        return $query->count();
    }
    
    /**
     * Contar por estado
     */
    public function countByStatus(array $filters = []): array
    {
        $query = $this->model->newQuery();
        
        foreach ($filters as $key => $value) {
            if ($key !== 'status' && $value !== null && $value !== '') {
                $query->where($key, $value);
            }
        }
        
        return $query->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }
    
    /**
     * Sumar por período
     */
    public function sumByPeriod(array $filters = []): array
    {
        $query = $this->model->newQuery();
        
        foreach ($filters as $key => $value) {
            if (!in_array($key, ['start_date', 'end_date']) && $value !== null && $value !== '') {
                $query->where($key, $value);
            }
        }
        
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
        
        return $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get()
        ->toArray();
    }
    
    /**
     * Obtener datos para reporte
     */
    public function getForReport(string $period, array $options = []): Collection
    {
        [$year, $month] = explode('-', $period);
        
        $query = $this->model->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
        
        if (!empty($options['status'])) {
            $query->where('status', $options['status']);
        }
        
        return $query->get();
    }
}
TEMPLATE;
    
    $outputPath = $outputDir . "/app/Repositories/{$modelName}Repository.php";
    
    if (file_exists($outputPath) && !$options['force']) {
        echo COLOR_YELLOW . "⚠ Archivo ya existe: $outputPath (usa --force para sobrescribir)\n" . COLOR_RESET;
        return false;
    }
    
    $content = processTemplate($template, $modelName);
    file_put_contents($outputPath, $content);
    
    echo COLOR_GREEN . "✓ Repository generado: $outputPath\n" . COLOR_RESET;
    return true;
}

// Generar DTOs
function generateDTOs($options)
{
    $modelName = $options['model'];
    $outputDir = $options['output'];
    
    // Plantilla Create DTO
    $createTemplate = <<<'TEMPLATE'
<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Create{{ModelName}}DTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly float $amount,
        public readonly string $status,
        public readonly ?array $metadata = null
    ) {}
    
    /**
     * Crear DTO desde Request
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'metadata' => ['sometimes', 'array'],
        ]);
        
        return new self(
            title: $validated['title'],
            description: $validated['description'],
            amount: (float) $validated['amount'],
            status: $validated['status'],
            metadata: $validated['metadata'] ?? null
        );
    }
    
    /**
     * Convertir a array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
TEMPLATE;
    
    // Plantilla Update DTO
    $updateTemplate = <<<'TEMPLATE'
<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Update{{ModelName}}DTO
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?float $amount = null,
        public readonly ?string $status = null,
        public readonly ?array $metadata = null
    ) {}
    
    /**
     * Crear DTO desde Request
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'metadata' => ['sometimes', 'array'],
        ]);
        
        return new self(
            title: $validated['title'] ?? null,
            description: $validated['description'] ?? null,
            amount: isset($validated['amount']) ? (float) $validated['amount'] : null,
            status: $validated['status'] ?? null,
            metadata: $validated['metadata'] ?? null
        );
    }
    
    /**
     * Convertir a array (solo campos no nulos)
     */
    public function toArray(): array
    {
        $data = [];
        
        if ($this->title !== null) {
            $data['title'] = $this->title;
        }
        
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        
        if ($this->amount !== null) {
            $data['amount'] = $this->amount;
        }
        
        if ($this->status !== null) {
            $data['status'] = $this->status;
        }
        
        if ($this->metadata !== null) {
            $data['metadata'] = $this->metadata;
        }
        
        return $data;
    }
}
TEMPLATE;
    
    // Generar archivos
    $createPath = $outputDir . "/app/DTOs/Create{$modelName}DTO.php";
    $updatePath = $outputDir . "/app/DTOs/Update{$modelName}DTO.php";
    
    $created = false;
    
    if (!file_exists($createPath) || $options['force']) {
        $content = processTemplate($createTemplate, $modelName);
        file_put_contents($createPath, $content);
        echo COLOR_GREEN . "✓ Create DTO generado: $createPath\n" . COLOR_RESET;
        $created = true;
    }
    
    if (!file_exists($updatePath) || $options['force']) {
        $content = processTemplate($updateTemplate, $modelName);
        file_put_contents($updatePath, $content);
        echo COLOR_GREEN . "✓ Update DTO generado: $updatePath\n" . COLOR_RESET;
        $created = true;
    }
    
    return $created;
}

// Refactorizar modelo existente
function refactorModel($options)
{
    $modelName = $options['model'];
    $outputDir = $options['output'];
    $modelPath = $outputDir . "/app/Models/{$modelName}.php";
    
    if (!file_exists($modelPath)) {
        echo COLOR_RED . "✗ Modelo no encontrado: $modelPath\n" . COLOR_RESET;
        return false;
    }
    
    // Leer contenido del modelo
    $content = file_get_contents($modelPath);
    
    // Agregar métodos delegados al final de la clase
    $delegatedMethods = <<<'METHODS'

    /**
     * Obtener instancia del servicio
     */
    public static function service(): \App\Services\{{ModelName}}Service
    {
        return app(\App\Services\{{ModelName}}Service::class);
    }

    /**
     * Obtener instancia del repositorio
     */
    public static function repository(): \App\Repositories\{{ModelName}}Repository
    {
        return app(\App\Repositories\{{ModelName}}Repository::class);
    }

