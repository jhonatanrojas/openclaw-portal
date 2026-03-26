# 🛠️ PLAN DE CORRECCIÓN PARA WORKFLOWS FALLIDOS

**Fecha:** 2026-03-24  
**PR:** #2 - condominio-management  
**Estado actual:** Todos los workflows fallando

---

## 📋 RESUMEN DE ERRORES:

### **1. Laravel CI/CD (Run #18 - FAILED)**
- **Error:** Tests fallaron durante ejecución
- **Causa probable:** Problemas con TestingRolesSeeder o factories faltantes
- **Impacto:** Bloquea todo el CI/CD

### **2. PHPStan Analysis (Run #20 - FAILED)**
- **Error:** Análisis estático falló
- **Causa probable:** Errores de tipo o sintaxis después de correcciones
- **Impacto:** Validación de calidad de código fallida

### **3. Clean Architecture Validation (Run #20 - FAILED)**
- **Error:** Validación de arquitectura falló
- **Causa probable:** Estructura no cumple con patrones definidos
- **Impacto:** Validación de arquitectura limpia fallida

---

## 🎯 PROBLEMA RAÍZ IDENTIFICADO:

### **TestingRolesSeeder y entorno de testing:**
1. **Seeders complejos** que intentan crear permisos que no existen
2. **Factories faltantes** para modelos como Currency, ReceivableType
3. **Constraints de DB** que causan errores en testing
4. **Configuración de roles** inconsistente entre desarrollo y testing

### **85 tests creados tienen problemas con:**
- ✅ Factories para Currency (resuelto)
- ✅ Factories para ReceivableType (resuelto)
- ❌ Constraints NOT NULL en tablas
- ❌ Roles no creados en DB testing
- ❌ Dependencias entre seeders

---

## 🛠️ PLAN DE CORRECCIÓN PASO A PASO:

### **PASO 1: Corregir TestingRolesSeeder (CRÍTICO)**
```php
// database/seeders/TestingRolesSeeder.php - VERSIÓN CORREGIDA
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class TestingRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Solo crear roles básicos para testing
        // NO intentar crear permisos que no existen
        $roles = ['admin', 'resident', 'manager', 'treasurer'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
```

### **PASO 2: Crear trait para tests de autenticación**
```php
// tests/Traits/CreatesTestingRoles.php
<?php

namespace Tests\Traits;

use App\Models\Role;

trait CreatesTestingRoles
{
    protected function setUpTestingRoles(): void
    {
        // Crear roles mínimos necesarios para tests
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'resident']);
    }
}
```

### **PASO 3: Actualizar phpunit.xml para entorno testing**
```xml
<!-- Asegurar que se use SQLite en memoria correctamente -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="BROADCAST_CONNECTION" value="log"/>
<env name="CACHE_DRIVER" value="array"/>
<env name="SESSION_DRIVER" value="array"/>
<env name="QUEUE_CONNECTION" value="sync"/>
```

### **PASO 4: Simplificar DatabaseSeeder para testing**
```php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    if (app()->environment('testing')) {
        $this->call(TestingRolesSeeder::class);
        // Solo seeders esenciales para testing
    } else {
        // Seeders completos para desarrollo/producción
        $this->call([
            RolesAndPermissionsSeeder::class,
            // otros seeders...
        ]);
    }
}
```

### **PASO 5: Corregir factories faltantes**
```php
// database/factories/CurrencyFactory.php (si no existe)
public function definition(): array
{
    return [
        'code' => $this->faker->currencyCode(),
        'name' => $this->faker->currencyCode(),
        'symbol' => '$',
        'exchange_rate' => 1.0,
    ];
}

// database/factories/ReceivableTypeFactory.php (si no existe)
public function definition(): array
{
    return [
        'name' => $this->faker->word(),
        'description' => $this->faker->sentence(),
    ];
}
```

---

## 🔄 FLUJO DE EJECUCIÓN:

### **Secuencia recomendada:**
1. **Commit 1:** TestingRolesSeeder corregido + trait
2. **Commit 2:** phpunit.xml actualizado para testing
3. **Commit 3:** DatabaseSeeder condicional por entorno
4. **Commit 4:** Factories faltantes creadas
5. **Commit 5:** Tests actualizados para usar trait

### **Verificación después de cada commit:**
```bash
# 1. Verificar sintaxis
php -l database/seeders/TestingRolesSeeder.php

# 2. Ejecutar tests específicos
php artisan test --testsuite=Unit --filter=AuthTest

# 3. Verificar PHPStan
./vendor/bin/phpstan analyse --memory-limit=2G

# 4. Verificar arquitectura
php artisan architecture:validate
```

---

## 📊 ESTRATEGIA DE PRUEBAS:

### **Fase 1: Tests mínimos**
```bash
# Solo tests que no dependen de roles complejos
php artisan test --testsuite=Unit --exclude-group=auth,roles
```

### **Fase 2: Tests con roles básicos**
```bash
# Tests que usan TestingRolesSeeder simplificado
php artisan test --testsuite=Feature --group=auth-basic
```

### **Fase 3: Todos los tests**
```bash
# Ejecución completa después de correcciones
php artisan test
```

---

## 🚨 MANEJO DE ERRORES ESPERADOS:

### **Error 1: "Class Role not found"**
- **Solución:** Asegurar que modelo Role existe y está en namespace correcto
- **Verificación:** `php artisan tinker` → `App\Models\Role::count()`

### **Error 2: "Table roles doesn't exist"**
- **Solución:** Ejecutar migraciones antes de seeders
- **Comando:** `php artisan migrate:fresh --seed`

### **Error 3: "Constraint violation"**
- **Solución:** Revisar factories para campos NOT NULL
- **Debug:** `dd($model->getAttributes())` antes de save

### **Error 4: "Permission denied"**
- **Solución:** Usar `firstOrCreate` en lugar de `create`
- **Alternativa:** Deshabilitar validación de unicidad en testing

---

## 📈 MÉTRICAS DE ÉXITO:

### **Criterios de aceptación:**
1. ✅ **Laravel CI/CD pasa** - Todos los tests ejecutados exitosamente
2. ✅ **PHPStan Analysis pasa** - 0 errores de análisis estático
3. ✅ **Clean Architecture Validation pasa** - Estructura validada
4. ✅ **Coverage > 70%** - Cobertura de tests aceptable
5. ✅ **PR mergeable** - Todos los checks en verde

### **Tiempos estimados:**
- **Corrección TestingRolesSeeder:** 15 minutos
- **Creación de trait y factories:** 30 minutos
- **Actualización de tests:** 45 minutos
- **Verificación y debugging:** 60 minutos
- **Total estimado:** 2.5 horas

---

## 🆘 CONTINGENCIAS:

### **Si los problemas persisten:**
1. **Opción A:** Deshabilitar temporalmente tests problemáticos con `@group skip`
2. **Opción B:** Crear entorno de testing más simple sin roles complejos
3. **Opción C:** Separar PR - Primero mergear código, luego tests

### **Si PHPStan sigue fallando:**
1. **Baseline:** Crear archivo `phpstan-baseline.neon` con errores aceptados
2. **Exclusiones:** Excluir archivos específicos del análisis
3. **Nivel:** Reducir nivel de strictness temporalmente

### **Si Clean Architecture falla:**
1. **Excepciones:** Definir excepciones en `architecture.php`
2. **Patrones:** Ajustar patrones permitidos
3. **Validación:** Deshabilitar validaciones específicas temporalmente

---

## 📝 DOCUMENTACIÓN A ACTUALIZAR:

### **Archivos a actualizar después de correcciones:**
1. **README.md** - Instrucciones de testing actualizadas
2. **CONTRIBUTING.md** - Guía para ejecutar tests localmente
3. **.github/workflows/ci.yml** - Mejoras en configuración
4. **tests/README.md** - Documentación de tests

### **Checklist final:**
- [ ] TestingRolesSeeder funciona en CI/CD
- [ ] Todos los tests pasan localmente
- [ ] PHPStan pasa sin errores
- [ ] Arquitectura validada correctamente
- [ ] Coverage report generado
- [ ] PR checks todos en verde

---

**Prioridad:** 🔴 **ALTA** - El PR #2 contiene trabajo significativo que necesita CI/CD funcionando  
**Riesgo:** 🟡 **MEDIO** - Problemas son conocidos y soluciones están identificadas  
**Esfuerzo:** ⏱️ **2-3 horas** - Correcciones específicas y testing  

**Siguiente paso:** Implementar PASO 1 (TestingRolesSeeder corregido) y verificar 🐾