# Plantilla: Service Layer

## Estructura básica de Service

```php
<?php

namespace App\Services;

use App\Repositories\{{ModelName}}Repository;
use App\Models\User;
use App\DTOs\Create{{ModelName}}DTO;
use App\DTOs\Update{{ModelName}}DTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class {{ModelName}}Service
{
    public function __construct(
        protected {{ModelName}}Repository $repository
    ) {}
    
    /**
     * Crear nuevo registro
     */
    public function create(Create{{ModelName}}DTO $dto, User $createdBy)
    {
        return DB::transaction(function () use ($dto, $createdBy) {
            // Validaciones de negocio
            $this->validateBusinessRules($dto);
            
            // Preparar datos para creación
            $data = array_merge($dto->toArray(), [
                'created_by' => $createdBy->id,
                'created_at' => now(),
            ]);
            
            // Crear registro
            $model = $this->repository->create($data);
            
            // Eventos posteriores a la creación
            $this->afterCreate($model, $dto);
            
            return $model;
        });
    }
    
    /**
     * Actualizar registro existente
     */
    public function update($id, Update{{ModelName}}DTO $dto, User $updatedBy)
    {
        return DB::transaction(function () use ($id, $dto, $updatedBy) {
            // Obtener registro
            $model = $this->repository->findOrFail($id);
            
            // Validar permisos de actualización
            $this->validateUpdatePermissions($model, $updatedBy);
            
            // Validaciones de negocio
            $this->validateBusinessRules($dto, $model);
            
            // Preparar datos para actualización
            $data = array_merge($dto->toArray(), [
                'updated_by' => $updatedBy->id,
                'updated_at' => now(),
            ]);
            
            // Actualizar registro
            $updatedModel = $this->repository->update($model, $data);
            
            // Eventos posteriores a la actualización
            $this->afterUpdate($updatedModel, $dto);
            
            return $updatedModel;
        });
    }
    
    /**
     * Eliminar registro (soft delete)
     */
    public function delete($id, User $deletedBy, string $reason = null)
    {
        return DB::transaction(function () use ($id, $deletedBy, $reason) {
            // Obtener registro
            $model = $this->repository->findOrFail($id);
            
            // Validar permisos de eliminación
            $this->validateDeletePermissions($model, $deletedBy);
            
            // Validar que se puede eliminar
            $this->validateDeletion($model);
            
            // Marcar como eliminado
            $model = $this->repository->delete($model, [
                'deleted_by' => $deletedBy->id,
                'deleted_at' => now(),
                'deletion_reason' => $reason,
            ]);
            
            // Eventos posteriores a la eliminación
            $this->afterDelete($model);
            
            return $model;
        });
    }
    
    /**
     * Obtener registro por ID
     */
    public function find($id, array $with = [])
    {
        return $this->repository->find($id, $with);
    }
    
    /**
     * Listar registros con filtros
     */
    public function getAll(array $filters = [], int $perPage = 15)
    {
        return $this->repository->getAll($filters, $perPage);
    }
    
    /**
     * Validaciones específicas de negocio
     */
    protected function validateBusinessRules($dto, $existingModel = null): void
    {
        // Implementar validaciones específicas del dominio
        // Ejemplo: verificar límites, restricciones, reglas de negocio
        
        if ($dto->amount > 10000 && !$existingModel) {
            throw new \InvalidArgumentException('Monto excede límite para nuevos registros');
        }
    }
    
    /**
     * Validar permisos de actualización
     */
    protected function validateUpdatePermissions($model, User $user): void
    {
        // Implementar lógica de permisos
        // Ejemplo: solo el creador o administradores pueden actualizar
        
        if ($model->created_by !== $user->id && !$user->hasRole('admin')) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'No tienes permisos para actualizar este registro'
            );
        }
    }
    
    /**
     * Validar permisos de eliminación
     */
    protected function validateDeletePermissions($model, User $user): void
    {
        // Implementar lógica de permisos
        // Ejemplo: solo administradores pueden eliminar
        
        if (!$user->hasRole('admin')) {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Solo administradores pueden eliminar registros'
            );
        }
    }
    
    /**
     * Validar que el registro se puede eliminar
     */
    protected function validateDeletion($model): void
    {
        // Implementar validaciones de eliminación
        // Ejemplo: verificar dependencias, estado, etc.
        
        if ($model->hasDependencies()) {
            throw new \InvalidArgumentException(
                'No se puede eliminar el registro porque tiene dependencias'
            );
        }
    }
    
    /**
     * Hook: después de crear
     */
    protected function afterCreate($model, $dto): void
    {
        // Ejecutar acciones posteriores a la creación
        // Ejemplo: enviar notificaciones, crear registros relacionados, etc.
        
        // Log::info('Registro creado', ['id' => $model->id]);
        
        // Event::dispatch(new {{ModelName}}Created($model, $dto));
    }
    
    /**
     * Hook: después de actualizar
     */
    protected function afterUpdate($model, $dto): void
    {
        // Ejecutar acciones posteriores a la actualización
        
        // Log::info('Registro actualizado', ['id' => $model->id]);
        
        // Event::dispatch(new {{ModelName}}Updated($model, $dto));
    }
    
    /**
     * Hook: después de eliminar
     */
    protected function afterDelete($model): void
    {
        // Ejecutar acciones posteriores a la eliminación
        
        // Log::info('Registro eliminado', ['id' => $model->id]);
        
        // Event::dispatch(new {{ModelName}}Deleted($model));
    }
    
    /**
     * Métodos específicos de negocio
     */
    
    public function approve($id, User $approvedBy, ?string $notes = null)
    {
        return DB::transaction(function () use ($id, $approvedBy, $notes) {
            $model = $this->repository->findOrFail($id);
            
            // Lógica específica de aprobación
            $model->status = 'approved';
            $model->approved_by = $approvedBy->id;
            $model->approved_at = now();
            $model->approval_notes = $notes;
            
            return $this->repository->update($model, $model->toArray());
        });
    }
    
    public function reject($id, User $rejectedBy, string $reason)
    {
        return DB::transaction(function () use ($id, $rejectedBy, $reason) {
            $model = $this->repository->findOrFail($id);
            
            // Lógica específica de rechazo
            $model->status = 'rejected';
            $model->rejected_by = $rejectedBy->id;
            $model->rejected_at = now();
            $model->rejection_reason = $reason;
            
            return $this->repository->update($model, $model->toArray());
        });
    }
    
    /**
     * Métodos de reportes y estadísticas
     */
    
    public function getStatistics(array $filters = []): array
    {
        return [
            'total' => $this->repository->count($filters),
            'by_status' => $this->repository->countByStatus($filters),
            'by_period' => $this->repository->sumByPeriod($filters),
            // ... más estadísticas
        ];
    }
    
    public function generateReport(string $period, array $options = []): array
    {
        $data = $this->repository->getForReport($period, $options);
        
        return [
            'period' => $period,
            'summary' => $this->calculateReportSummary($data),
            'details' => $data,
            'generated_at' => now(),
        ];
    }
    
    protected function calculateReportSummary($data): array
    {
        // Lógica para calcular resumen del reporte
        return [
            'total' => $data->sum('amount'),
            'count' => $data->count(),
            'average' => $data->avg('amount'),
        ];
    }
}
```

## Convenciones y mejores prácticas

### 1. Responsabilidades del Service
- **Lógica de negocio** (reglas, validaciones, cálculos)
- **Coordinación** entre repositorios y otros servicios
- **Transacciones** de base de datos
- **Eventos** y notificaciones
- **Autorización** a nivel de negocio

### 2. No debe contener
- **Acceso directo a base de datos** (usa Repository)
- **Lógica de presentación** (usa DTOs o View Models)
- **Configuración HTTP** (usa Controllers o Form Requests)
- **Validaciones de formato** (usa Form Requests o DTOs)

### 3. Patrones comunes

#### Patrón: Service con múltiples repositorios
```php
class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository,
        protected UserRepository $userRepository
    ) {}
    
    public function createOrder(CreateOrderDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            // Validar productos
            $products = $this->productRepository->validateStock($dto->productIds);
            
            // Validar usuario
            $user = $this->userRepository->findOrFail($dto->userId);
            
            // Crear orden
            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'total' => $products->sum('price'),
            ]);
            
            // Crear items de orden
            foreach ($products as $product) {
                $this->orderRepository->createItem($order, $product);
            }
            
            return $order;
        });
    }
}
```

#### Patrón: Service con eventos
```php
class InvoiceService
{
    public function generateInvoice($orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $invoice = $this->repository->createInvoice($orderId);
            
            // Disparar eventos
            Event::dispatch(new InvoiceGenerated($invoice));
            
            // Enviar notificaciones
            Notification::send($invoice->user, new InvoiceCreatedNotification($invoice));
            
            return $invoice;
        });
    }
}
```

### 4. Testing del Service

```php
// tests/Feature/Services/{{ModelName}}ServiceTest.php
class {{ModelName}}ServiceTest extends TestCase
{
    protected {{ModelName}}Service $service;
    protected MockInterface $repositoryMock;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repositoryMock = Mockery::mock({{ModelName}}Repository::class);
        $this->service = new {{ModelName}}Service($this->repositoryMock);
    }
    
    /** @test */
    public function it_creates_a_record()
    {
        // Arrange
        $dto = new Create{{ModelName}}DTO([...]);
        $user = User::factory()->create();
        
        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::subset(['created_by' => $user->id]))
            ->andReturn(new {{ModelName}}([...]));
        
        // Act
        $result = $this->service->create($dto, $user);
        
        // Assert
        $this->assertInstanceOf({{ModelName}}::class, $result);
    }
}
```

### 5. Inyección de dependencias

**Service Provider recomendado:**
```php
// app/Providers/{{ModelName}}ServiceProvider.php
class {{ModelName}}ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton({{ModelName}}Service::class, function ($app) {
            return new {{ModelName}}Service(
                $app->make({{ModelName}}Repository::class)
            );
        });
    }
}
```

### 6. Manejo de errores

```php
class {{ModelName}}Service
{
    public function performCriticalOperation($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                // Operación crítica
            });
        } catch (\Exception $e) {
            Log::error('Error en operación crítica', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-lanzar excepción específica del dominio
            throw new {{ModelName}}OperationFailedException(
                'No se pudo completar la operación',
                previous: $e
            );
        }
    }
}
```

## Variables de plantilla

- `{{ModelName}}`: Nombre del modelo (ej: Expense, User, Invoice)
- `{{modelName}}`: nombre del modelo en camelCase (ej: expense, user, invoice)
- `{{MODEL_NAME}}`: nombre del modelo en mayúsculas (ej: EXPENSE, USER, INVOICE)

## Uso con generador

```bash
# Generar Service desde plantilla
php scripts/generate-from-template.php \
  --template=references/service-template.md \
  --model=Expense \
  --output=app/Services/ExpenseService.php
```