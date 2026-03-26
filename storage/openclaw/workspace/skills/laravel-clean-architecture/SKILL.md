---
name: laravel-clean-architecture
description: "Implementar arquitectura limpia en proyectos Laravel con patrones Service-Repository, DTOs, Actions y separación clara de responsabilidades. Use cuando: (1) Refactorizando proyectos Laravel existentes, (2) Creando nuevos módulos con arquitectura limpia, (3) Mejorando testabilidad y mantenibilidad, (4) Implementando CI/CD para validación de arquitectura, (5) Estableciendo estándares de código consistentes en equipos Laravel."
---

# Laravel Clean Architecture

## Overview

Skill especializada para implementar arquitectura limpia en proyectos Laravel, basada en patrones probados como Service Layer, Repository Pattern, DTOs y Action Classes. Proporciona flujos de trabajo, plantillas y herramientas para transformar proyectos Laravel en aplicaciones mantenibles, testables y escalables.

## Workflow Decision Tree

### ¿Qué necesitas hacer?

1. **Refactorizar modelo existente** → Ve a [Refactorización](#refactorización-de-modelos)
2. **Crear nuevo módulo con arquitectura limpia** → Ve a [Nuevo Módulo](#creación-de-nuevos-módulos)
3. **Configurar CI/CD para validación** → Ve a [CI/CD](#cicd-y-validación)
4. **Crear tests para arquitectura limpia** → Ve a [Testing](#testing)
5. **Validar arquitectura existente** → Ve a [Validación](#validación-de-arquitectura)

## Refactorización de Modelos

### Patrón Service-Repository (basado en nuestro trabajo en Condominio Management)

**Estructura objetivo:**
```
app/
├── Services/ExpenseService.php      # Lógica de negocio
├── Repositories/ExpenseRepository.php # Acceso a datos
└── Models/Expense.php               # Modelo con métodos delegados
```

### Paso 1: Crear Service

**Plantilla básica:** Ver [references/service-template.md](references/service-template.md)

```php
<?php
namespace App\Services;

use App\Repositories\ExpenseRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(protected ExpenseRepository $repository) {}
    
    public function create(array $data, User $createdBy)
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Lógica de negocio aquí
            return $this->repository->create($data);
        });
    }
    
    // Más métodos de negocio...
}
```

### Paso 2: Crear Repository

**Plantilla básica:** Ver [references/repository-template.md](references/repository-template.md)

```php
<?php
namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseRepository
{
    public function __construct(protected Expense $model) {}
    
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'provider']);
        
        // Aplicar filtros...
        return $query->paginate();
    }
    
    // Más métodos de acceso a datos...
}
```

### Paso 3: Actualizar Modelo

**Plantilla básica:** Ver [references/model-template.md](references/model-template.md)

```php
<?php
namespace App\Models;

use App\Services\ExpenseService;
use App\Repositories\ExpenseRepository;

class Expense extends Model
{
    // Propiedades y relaciones normales...
    
    /**
     * Métodos delegados al servicio
     */
    public static function service(): ExpenseService
    {
        return app(ExpenseService::class);
    }
    
    public static function repository(): ExpenseRepository
    {
        return app(ExpenseRepository::class);
    }
    
    public function approve(User $approvedBy, ?string $notes = null)
    {
        return static::service()->approve($this, $approvedBy, $notes);
    }
}
```

### Scripts de ayuda:
- `scripts/generate-service.php` - Genera Service + Repository + Modelo actualizado
- `scripts/refactor-model.sh` - Refactoriza modelo existente automáticamente

## Creación de Nuevos Módulos

### Estructura completa de módulo

```
app/
├── Actions/ModuleName/
│   ├── CreateModuleAction.php
│   ├── UpdateModuleAction.php
│   └── DeleteModuleAction.php
├── Services/ModuleNameService.php
├── Repositories/ModuleNameRepository.php
├── DTOs/
│   ├── CreateModuleDTO.php
│   └── UpdateModuleDTO.php
├── ValueObjects/
│   └── ModuleStatus.php
├── Contracts/
│   └── ModuleRepositoryInterface.php
└── Models/ModuleName.php
```

### Generar módulo completo

Usar script: `scripts/generate-module.php`

```bash
php scripts/generate-module.php ModuleName --with-dto --with-actions --with-contracts
```

**Opciones:**
- `--with-dto`: Incluye DTOs para entrada/salida
- `--with-actions`: Incluye Action Classes
- `--with-contracts`: Incluye interfaces/contratos
- `--with-tests`: Genera tests completos

## Testing

### Estructura de tests por capa

```
tests/
├── Feature/
│   ├── ModuleName/
│   │   ├── ModuleNameTest.php           # Tests de API/controladores
│   │   ├── ModuleNameServiceTest.php    # Tests de servicio
│   │   └── ModuleNameRepositoryTest.php # Tests de repositorio
└── Unit/
    ├── Actions/ModuleNameActionTest.php
    └── DTOs/ModuleDTOTest.php
```

### Plantillas de tests:
- Ver [references/test-service-template.md](references/test-service-template.md)
- Ver [references/test-repository-template.md](references/test-repository-template.md)
- Ver [references/test-action-template.md](references/test-action-template.md)

### Configurar PHPUnit para arquitectura limpia

```xml
<!-- phpunit.xml -->
<testsuites>
    <testsuite name="Services">
        <directory>tests/Feature/*/ServiceTest.php</directory>
    </testsuite>
    <testsuite name="Repositories">
        <directory>tests/Feature/*/RepositoryTest.php</directory>
    </testsuite>
    <testsuite name="Actions">
        <directory>tests/Unit/Actions</directory>
    </testsuite>
</testsuites>
```

## CI/CD y Validación

### GitHub Actions para arquitectura limpia

**Workflow:** Ver [assets/github/workflows/laravel-clean-arch.yml](assets/github/workflows/laravel-clean-arch.yml)

**Validaciones automáticas:**
1. **Validación de dependencias**: Verifica que Services no dependan directamente de Models
2. **Validación de capas**: Verifica separación Service-Repository-Model
3. **Cobertura de tests**: Requiere tests para cada capa
4. **Análisis estático**: PHPStan con reglas específicas de arquitectura limpia

### Configurar PHPStan

**Reglas específicas:** Ver [references/phpstan-rules.md](references/phpstan-rules.md)

```neon
# phpstan.neon
rules:
  - LaravelCleanArchitecture\Rules\ServiceDependsOnRepository
  - LaravelCleanArchitecture\Rules\ModelNoBusinessLogic
  - LaravelCleanArchitecture\Rules\RepositoryInterfaceImplementation
```

### Scripts de validación:
- `scripts/validate-architecture.php` - Valida dependencias entre capas
- `scripts/generate-architecture-diagram.php` - Genera diagrama de dependencias
- `scripts/check-layer-violations.php` - Detecta violaciones de arquitectura

## Validación de Arquitectura

### Reglas de arquitectura limpia

1. **Models**: Solo propiedades, relaciones, scopes y accessors/mutators
2. **Repositories**: Solo acceso a datos (CRUD, queries, filtros)
3. **Services**: Lógica de negocio, validaciones, reglas de negocio
4. **Actions**: Casos de uso específicos (una acción = una responsabilidad)
5. **DTOs**: Transferencia de datos entre capas (inmutables)

### Métricas de calidad

**Script:** `scripts/calculate-architecture-metrics.php`

```bash
php scripts/calculate-architecture-metrics.php
```

**Métricas calculadas:**
- **Acoplamiento entre capas** (debe ser bajo)
- **Cohesión por módulo** (debe ser alta)
- **Complejidad ciclomática por servicio**
- **Tasa de violaciones de arquitectura**

### Reporte de arquitectura

**Generar reporte:** `scripts/generate-architecture-report.php`

```bash
php scripts/generate-architecture-report.php --format=html --output=architecture-report.html
```

**Incluye:**
- Diagrama de dependencias
- Métricas por módulo
- Violaciones detectadas
- Recomendaciones de mejora

## Recursos Disponibles

### Scripts (`scripts/`)
- `generate-service.php` - Genera Service + Repository
- `generate-module.php` - Genera módulo completo
- `refactor-model.sh` - Refactoriza modelo existente
- `validate-architecture.php` - Valida arquitectura
- `calculate-architecture-metrics.php` - Calcula métricas
- `generate-architecture-report.php` - Genera reporte

### Referencias (`references/`)
- `service-template.md` - Plantilla completa de Service
- `repository-template.md` - Plantilla completa de Repository
- `model-template.md` - Plantilla de Modelo con delegación
- `dto-template.md` - Plantilla de DTO
- `action-template.md` - Plantilla de Action Class
- `test-service-template.md` - Plantilla de test para Service
- `test-repository-template.md` - Plantilla de test para Repository
- `phpstan-rules.md` - Reglas PHPStan para arquitectura limpia
- `workflow-examples.md` - Ejemplos de flujos de trabajo completos

### Assets (`assets/`)
- `github/workflows/laravel-clean-arch.yml` - GitHub Actions workflow
- `docker/laravel-clean-arch.dockerfile` - Dockerfile optimizado
- `templates/module-structure/` - Estructura completa de módulo
- `diagrams/` - Diagramas de arquitectura (plantillas)

## Ejemplos de Uso

### Ejemplo 1: Refactorizar Expense (como hicimos)
```bash
# Generar Service y Repository
php scripts/generate-service.php Expense --refactor

# Actualizar modelo Expense
php scripts/refactor-model.sh Expense

# Validar resultado
php scripts/validate-architecture.php --model=Expense
```

### Ejemplo 2: Crear nuevo módulo "Invoice"
```bash
# Generar módulo completo
php scripts/generate-module.php Invoice \
  --with-dto \
  --with-actions \
  --with-tests \
  --with-contracts

# Ejecutar validación
php scripts/validate-architecture.php --module=Invoice

# Generar reporte
php scripts/generate-architecture-report.php --module=Invoice
```

### Ejemplo 3: Configurar CI/CD para proyecto existente
```bash
# Copiar workflow de GitHub Actions
cp assets/github/workflows/laravel-clean-arch.yml .github/workflows/

# Configurar PHPStan con reglas de arquitectura
cp references/phpstan-rules.md phpstan.architecture.neon

# Ejecutar validación inicial
php scripts/validate-architecture.php --full-project
```

## Mejores Prácticas

### 1. Inyección de Dependencias
- Services inyectan Repositories
- Repositories inyectan Models
- Controllers inyectan Services o Actions
- Nunca: Service → Model directo, Controller → Repository directo

### 2. Manejo de Transacciones
- Transacciones solo en Services (capa de negocio)
- Repositories no manejan transacciones
- Models nunca manejan transacciones

### 3. Validaciones
- Validaciones de formato: Form Requests
- Validaciones de negocio: Services
- Validaciones de datos: Repositories (unicidad, existencia)

### 4. Testing
- Tests de Repository: Base de datos real o mocks
- Tests de Service: Mock de Repository
- Tests de Action: Mock de Service/Repository
- Tests de Controller: Mock de Service/Action

## Solución de Problemas Comunes

### Problema: "Circular dependency detected"
**Solución:** Revisar [references/circular-dependencies.md](references/circular-dependencies.md)

### Problema: "Service too large (> 300 lines)"
**Solución:** Dividir en múltiples Services o usar Action Classes

### Problema: "Repository doing business logic"
**Solución:** Mover lógica a Service, Repository solo queries

### Problema: "Model with business methods"
**Solución:** Refactorizar usando `scripts/refactor-model.sh`

## Actualización y Mantenimiento

### Actualizar skill
```bash
# Desde el directorio de la skill
scripts/update-skill.sh
```

### Reportar problemas
Ver [references/troubleshooting.md](references/troubleshooting.md)

### Contribuir mejoras
La skill está diseñada para ser extensible. Agregar:
- Nuevas plantillas en `references/`
- Nuevos scripts en `scripts/`
- Nuevos workflows en `assets/github/workflows/`

---

**Nota:** Esta skill está basada en patrones probados en proyectos reales como Condominio Management. Los ejemplos y plantillas reflejan implementaciones que funcionan en producción.