#!/usr/bin/env php
<?php

/**
 * Script completo de generación de Service + Repository
 * Versión simplificada para demostración
 */

// Configuración básica
$modelName = $argv[1] ?? null;

if (!$modelName) {
    echo "Uso: php generate-service.php <ModelName>\n";
    echo "Ejemplo: php generate-service.php Expense\n";
    exit(1);
}

// Crear Service
$serviceTemplate = <<<PHP
<?php

namespace App\Services;

use App\Repositories\\{$modelName}Repository;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class {$modelName}Service
{
    public function __construct(
        protected {$modelName}Repository \$repository
    ) {}
    
    public function create(array \$data, User \$createdBy)
    {
        return DB::transaction(function () use (\$data, \$createdBy) {
            \$data['created_by'] = \$createdBy->id;
            return \$this->repository->create(\$data);
        });
    }
    
    public function update(\$id, array \$data, User \$updatedBy)
    {
        return DB::transaction(function () use (\$id, \$data, \$updatedBy) {
            \$model = \$this->repository->findOrFail(\$id);
            \$data['updated_by'] = \$updatedBy->id;
            return \$this->repository->update(\$model, \$data);
        });
    }
    
    public function delete(\$id, User \$deletedBy, ?string \$reason = null)
    {
        return DB::transaction(function () use (\$id, \$deletedBy, \$reason) {
            \$model = \$this->repository->findOrFail(\$id);
            return \$this->repository->delete(\$model, [
                'deleted_by' => \$deletedBy->id,
                'deletion_reason' => \$reason,
            ]);
        });
    }
    
    public function find(\$id, array \$with = [])
    {
        return \$this->repository->find(\$id, \$with);
    }
    
    public function getAll(array \$filters = [], int \$perPage = 15)
    {
        return \$this->repository->getAll(\$filters, \$perPage);
    }
}
PHP;

// Crear Repository
$repositoryTemplate = <<<PHP
<?php

namespace App\Repositories;

use App\Models\\{$modelName};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class {$modelName}Repository
{
    public function __construct(protected {$modelName} \$model) {}
    
    public function getAll(array \$filters = [], int \$perPage = 15): LengthAwarePaginator
    {
        \$query = \$this->model->newQuery();
        
        foreach (\$filters as \$key => \$value) {
            if (\$value !== null && \$value !== '') {
                \$query->where(\$key, \$value);
            }
        }
        
        return \$query->paginate(\$perPage);
    }
    
    public function find(\$id, array \$with = []): ?{$modelName}
    {
        \$query = \$this->model;
        
        if (!empty(\$with)) {
            \$query = \$query->with(\$with);
        }
        
        return \$query->find(\$id);
    }
    
    public function findOrFail(\$id, array \$with = []): {$modelName}
    {
        \$query = \$this->model;
        
        if (!empty(\$with)) {
            \$query = \$query->with(\$with);
        }
        
        return \$query->findOrFail(\$id);
    }
    
    public function create(array \$data): {$modelName}
    {
        return \$this->model->create(\$data);
    }
    
    public function update({$modelName} \$model, array \$data): {$modelName}
    {
        \$model->update(\$data);
        return \$model->fresh();
    }
    
    public function delete({$modelName} \$model, array \$data = []): {$modelName}
    {
        if (!empty(\$data)) {
            \$model->update(\$data);
        }
        
        \$model->delete();
        return \$model->fresh();
    }
}
PHP;

// Crear archivos
$serviceDir = __DIR__ . '/../../../../app/Services';
$repositoryDir = __DIR__ . '/../../../../app/Repositories';

// Crear directorios si no existen
if (!is_dir($serviceDir)) {
    mkdir($serviceDir, 0755, true);
    echo "✓ Directorio creado: $serviceDir\n";
}

if (!is_dir($repositoryDir)) {
    mkdir($repositoryDir, 0755, true);
    echo "✓ Directorio creado: $repositoryDir\n";
}

// Escribir archivos
file_put_contents("$serviceDir/{$modelName}Service.php", $serviceTemplate);
file_put_contents("$repositoryDir/{$modelName}Repository.php", $repositoryTemplate);

echo "✓ Service generado: $serviceDir/{$modelName}Service.php\n";
echo "✓ Repository generado: $repositoryDir/{$modelName}Repository.php\n";
echo "\n✅ Generación completada para modelo: $modelName\n";

// Sugerir próximos pasos
echo "\n📝 Próximos pasos sugeridos:\n";
echo "1. Actualizar modelo {$modelName} para usar Service/Repository\n";
echo "2. Registrar Service en Service Provider\n";
echo "3. Crear tests para Service y Repository\n";
echo "4. Actualizar controladores para usar Service\n";