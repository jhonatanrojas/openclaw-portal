# 🏢 Ejemplos de Uso de Modelos para Proyecto Condominio

## 📋 **CONFIGURACIÓN ACTIVA**

**Modelo principal:** `zai/glm-5` (GLM-5)
**Modelo testing:** `moonshot/kimi-k2.5` (Kimi K2.5)
**Modelo reasoning:** `moonshot/kimi-k2-thinking` (Kimi K2 Thinking)

## 🚀 **EJEMPLOS PRÁCTICOS**

### **1. Desarrollo con GLM-5 (Principal)**

```bash
# GLM-5 se usará automáticamente para desarrollo normal
# Ejemplo: Crear modelo de Recibo
```

**Prompt:**
```
Crea un modelo Laravel para Recibos de condominio con:
- Relación con Residentes (belongsTo)
- Campos: monto, fecha_emision, fecha_vencimiento, estado (pagado/pendiente)
- Método para calcular intereses moratorios (2% mensual)
- Scope para recibos pendientes
```

**GLM-5 generará:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Recibo extends Model
{
    protected $fillable = [
        'residente_id',
        'monto',
        'fecha_emision',
        'fecha_vencimiento',
        'estado',
        'descripcion'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'monto' => 'decimal:2'
    ];

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADO = 'pagado';

    // Relación con Residentes
    public function residente(): BelongsTo
    {
        return $this->belongsTo(Residente::class);
    }

    // Calcular intereses moratorios
    public function calcularInteresesMoratorios(): float
    {
        if ($this->estado === self::ESTADO_PAGADO) {
            return 0;
        }

        $hoy = Carbon::now();
        $fechaVencimiento = Carbon::parse($this->fecha_vencimiento);

        if ($hoy <= $fechaVencimiento) {
            return 0;
        }

        $mesesAtraso = $hoy->diffInMonths($fechaVencimiento);
        $interesMensual = 0.02; // 2% mensual
        
        return $this->monto * $interesMensual * $mesesAtraso;
    }

    // Scope para recibos pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    // Scope para recibos vencidos
    public function scopeVencidos($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE)
                    ->whereDate('fecha_vencimiento', '<', Carbon::now());
    }
}
```

### **2. Testing con Kimi K2.5**

```bash
# Especificar 'kimi:' al inicio para usar Kimi K2.5
```

**Prompt:**
```
kimi: Crea tests PHPUnit para el modelo Recibo que acabo de crear.
Incluye tests para:
1. Relación con Residentes
2. Cálculo de intereses moratorios
3. Scopes (pendientes, vencidos)
4. Validación de campos
Ejecuta los tests en modo Agent Swarm si es posible.
```

**Kimi K2.5 generará (con Agent Swarm):**
```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Recibo;
use App\Models\Residente;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReciboTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pertenece_a_un_residente()
    {
        $residente = Residente::factory()->create();
        $recibo = Recibo::factory()->create(['residente_id' => $residente->id]);

        $this->assertInstanceOf(Residente::class, $recibo->residente);
        $this->assertEquals($residente->id, $recibo->residente->id);
    }

    /** @test */
    public function calcula_intereses_moratorios_correctamente()
    {
        $recibo = Recibo::factory()->create([
            'monto' => 1000,
            'fecha_vencimiento' => Carbon::now()->subMonths(3),
            'estado' => Recibo::ESTADO_PENDIENTE
        ]);

        // 3 meses * 2% mensual * 1000 = 60
        $intereses = $recibo->calcularInteresesMoratorios();
        $this->assertEquals(60, $intereses);
    }

    /** @test */
    public function no_calcula_intereses_si_esta_pagado()
    {
        $recibo = Recibo::factory()->create([
            'monto' => 1000,
            'fecha_vencimiento' => Carbon::now()->subMonths(3),
            'estado' => Recibo::ESTADO_PAGADO
        ]);

        $this->assertEquals(0, $recibo->calcularInteresesMoratorios());
    }

    /** @test */
    public function scope_pendientes_funciona_correctamente()
    {
        Recibo::factory()->count(3)->create(['estado' => Recibo::ESTADO_PENDIENTE]);
        Recibo::factory()->count(2)->create(['estado' => Recibo::ESTADO_PAGADO]);

        $pendientes = Recibo::pendientes()->get();
        
        $this->assertCount(3, $pendientes);
        $this->assertTrue($pendientes->every(fn($r) => $r->estado === Recibo::ESTADO_PENDIENTE));
    }

    // ... más tests generados en paralelo por Agent Swarm
}
```

### **3. Reasoning complejo con Kimi K2 Thinking**

```bash
# Usar 'kimi-thinking:' para reasoning profundo
```

**Prompt:**
```
kimi-thinking: Analiza este error en el sistema de condominios:

"Error: Integrity constraint violation: 1452 Cannot add or update a child row: 
a foreign key constraint fails (`condominio_db`.`recibos`, CONSTRAINT 
`recibos_residente_id_foreign` FOREIGN KEY (`residente_id`) REFERENCES 
`residentes` (`id`))"

El contexto:
- Tabla 'residentes' tiene 1000 registros
- Tabla 'recibos' tiene 5000 registros
- Se está ejecutando un batch job para generar recibos mensuales
- El error ocurre en el registro #2345 del batch

Analiza:
1. Posibles causas raíz
2. Cómo debuggear eficientemente
3. Soluciones inmediatas y a largo plazo
4. Cómo prevenir en futuros batch jobs
```

**Kimi K2 Thinking analizará:**
```
ANÁLISIS DEL ERROR:

1. CAUSA RAÍZ PROBABLE:
   - El residente_id #XXXX no existe en la tabla 'residentes'
   - Posible eliminación de residente sin cascade delete
   - Data corruption o sync issue

2. DEBUGGING PASO A PASO:
   a) Identificar el residente_id problemático:
      SELECT * FROM recibos WHERE id = [id_del_error];
   
   b) Verificar existencia del residente:
      SELECT * FROM residentes WHERE id = [residente_id_problematico];
   
   c) Revisar logs de eliminación:
      SELECT * FROM audit_logs WHERE table_name = 'residentes' 
      AND record_id = [residente_id_problematico];

3. SOLUCIONES INMEDIATAS:
   a) Para el batch actual:
      - Skip el registro problemático y continuar
      - Loggear el error para revisión manual
   
   b) Corrección de datos:
      - Si el residente fue eliminado, marcar recibo como "residente_eliminado"
      - O asignar a un residente genérico de administración

4. PREVENCIÓN A LARGO PLAZO:
   a) Modificar foreign key constraint:
      ALTER TABLE recibos 
      ADD FOREIGN KEY (residente_id) 
      REFERENCES residentes(id) 
      ON DELETE SET NULL;
   
   b) Validación pre-batch:
      - Verificar que todos los residentes existen antes del batch
      - Usar transacciones con rollback on error
   
   c) Monitoring:
      - Alertas cuando residentes son eliminados con recibos pendientes
      - Dashboard de integridad referencial
```

### **4. Comandos OpenClaw específicos**

```bash
# Verificar modelos configurados
openclaw models list

# Ver estado de modelos
openclaw models status

# Cambiar modelo temporalmente (para una sesión)
openclaw agent --model moonshot/kimi-k2.5 --message "Analiza este código"

# Usar modelo específico en conversación
## En tu mensaje, incluir prefijo:
"kimi: [tu pregunta]"  # Usará Kimi K2.5
"kimi-thinking: [tu pregunta]"  # Usará Kimi K2 Thinking
## Sin prefijo: Usará GLM-5 automáticamente

# Ver costos y uso
openclaw models status --json | jq '.usage'
```

### **5. Workflow recomendado para el proyecto**

**Día a día:**
```bash
# Mañana: Desarrollo con GLM-5
"GLM-5: Crea migration para tabla gastos_comunes"

# Tarde: Testing con Kimi K2.5  
"kimi: Genera tests para el módulo de gastos"

# Noche: Análisis con Kimi Thinking
"kimi-thinking: Optimiza esta query de reportes mensuales"
```

**Para features complejas:**
1. **GLM-5** diseña la arquitectura
2. **GLM-5** implementa el código base
3. **Kimi K2.5** genera tests en paralelo
4. **Kimi K2 Thinking** analiza edge cases
5. **GLM-5** revisa y refina

### **6. Scripts útiles**

**`usar_glm5.sh`:**
```bash
#!/bin/bash
# Forzar uso de GLM-5
openclaw agent --model zai/glm-5 --message "$1"
```

**`usar_kimi_testing.sh`:**
```bash
#!/bin/bash
# Usar Kimi para testing
openclaw agent --model moonshot/kimi-k2.5 --message "kimi: Genera tests para: $1"
```

**`analizar_error.sh`:**
```bash
#!/bin/bash
# Análisis profundo con Kimi Thinking
openclaw agent --model moonshot/kimi-k2-thinking --message "kimi-thinking: Analiza este error: $1"
```

## 🎯 **MEJORES PRÁCTICAS**

### **Cuándo usar cada modelo:**

| Tarea | Modelo Recomendado | Razón |
|-------|-------------------|-------|
| **Diseño arquitectura** | GLM-5 | Mejor en SWE-bench, entiende Laravel |
| **Código PHP/Laravel** | GLM-5 | Especializado en código empresarial |
| **Generación de tests** | Kimi K2.5 | Agent Swarm para tests paralelos |
| **Debugging complejo** | Kimi K2 Thinking | Reasoning profundo |
| **Frontend Vue** | GLM-5 o DeepSeek | Ambos son competentes |
| **Cálculos financieros** | Kimi K2.5 | Excelente en matemáticas (95.63% AIME) |
| **Optimización DB** | Kimi K2 Thinking | Análisis profundo de queries |

### **Patrones de prompts efectivos:**

**Para GLM-5:**
```
[Contexto claro] + [Requisitos específicos] + [Ejemplo si es necesario]
```

**Para Kimi K2.5:**
```
kimi: [Tarea concreta] + [Criterios de éxito] + [Formato esperado]
```

**Para Kimi K2 Thinking:**
```
kimi-thinking: [Problema complejo] + [Contexto completo] + [Preguntas específicas]
```

## 📊 **MONITOREO DE COSTOS**

```bash
# Estimación de costos mensuales
GLM-5:    ~$50-100/mes  (desarrollo principal)
Kimi K2.5: ~$30-60/mes   (testing)
Total:    ~$80-160/mes

# Vs alternativas:
Claude Opus: ~$400-800/mes  (5x más caro)
GPT-5:       ~$300-600/mes  (4x más caro)
```

## 🚨 **SOLUCIÓN DE PROBLEMAS**

### **Si GLM-5 no responde:**
```bash
# Verificar API key
echo $ZAI_API_KEY

# Probar conexión
curl -X POST https://open.bigmodel.cn/api/paas/v4/chat/completions \
  -H "Authorization: Bearer $ZAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "glm-5", "messages": [{"role": "user", "content": "test"}]}'
```

### **Si Kimi K2.5 falla:**
```bash
# Cambiar a fallback automáticamente
# OpenClaw usará DeepSeek o Nemotron
```

### **Para máxima eficiencia:**
1. **GLM-5** para 80% del trabajo
2. **Kimi K2.5** para 15% (testing/complejo)
3. **Kimi Thinking** para 5% (debugging crítico)

---

**¡Listo para desarrollar tu proyecto de condominios con la mejor combinación de modelos!** 🏢🚀