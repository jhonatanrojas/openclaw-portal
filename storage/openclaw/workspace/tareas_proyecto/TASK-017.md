# 🏢 TASK-017: Nivel de piso editable en generación masiva de unidades

**ID:** TASK-017  
**Fase:** Nueva feature (prioridad media)  
**Tipo:** ✨ Feature  
**Prioridad:** 🟡 Media  
**Estado:** 📝 Por hacer  
**Estimación:** 4 horas  
**Creada:** 2026-03-24  
**Responsable:** rangerdev  
**Dependencias:** Ninguna (puede hacerse en paralelo)  

---

## 🎯 OBJETIVO

Agregar la capacidad de editar el **nivel de piso** en la vista previa de generación masiva de unidades, permitiendo ajustes personalizados antes de crear las unidades.

## 📋 PROBLEMA ACTUAL

En el componente `UnitGeneratorModal.vue`:
- La columna "Nivel/Piso" solo muestra `{{ unit.floor ?? 'Planta Única' }}` (solo lectura)
- No hay input para editar el piso en la vista previa
- Los usuarios no pueden ajustar pisos específicos después de la generación automática

## 📋 SUBTAREAS

### **1. Modificar frontend (Vue Component)**
- [ ] **Actualizar `UnitGeneratorModal.vue`**
  - [ ] Cambiar columna "Nivel/Piso" de texto a input editable
  - [ ] Agregar validación (números enteros, rango razonable)
  - [ ] Mantener valor por defecto del generador automático
  - [ ] Agregar tooltip o ayuda contextual

### **2. Actualizar backend (opcional - validación)**
- [ ] **Verificar `UnitGeneratorService.php`**
  - [ ] Asegurar que el campo `floor` se incluya en `customArrayUnits`
  - [ ] Validar que el piso sea integer en el backend
  - [ ] Mantener compatibilidad con generación automática

### **3. Mejoras de UX**
- [ ] **Agregar funcionalidades útiles:**
  - [ ] Botón "Restaurar pisos automáticos"
  - [ ] Edición masiva de pisos (seleccionar múltiples y cambiar)
  - [ ] Indicador visual de pisos modificados
  - [ ] Filtro por piso en vista previa

### **4. Testing**
- [ ] **Probar funcionalidad:**
  - [ ] Generar unidades con pisos automáticos
  - [ ] Editar pisos individualmente
  - [ ] Guardar y verificar en base de datos
  - [ ] Probar con múltiples edificios

## 📁 ARCHIVOS AFECTADOS

### **Frontend (Vue):**
```
resources/js/Pages/Admin/Units/Partials/UnitGeneratorModal.vue
```

### **Backend (PHP):**
```
app/Services/UnitGeneratorService.php (posible validación)
app/Http/Controllers/Admin/UnitBatchGeneratorController.php
```

### **Base de datos:**
```
app/Models/Unit.php (ya tiene campo `floor`)
```

## 🎨 DISEÑO DE LA SOLUCIÓN

### **Cambio en la tabla (antes):**
```vue
<td class="px-4 py-2 whitespace-nowrap text-sm text-slate-600 font-medium">
    {{ unit.floor ?? 'Planta Única' }}
</td>
```

### **Cambio en la tabla (después):**
```vue
<td class="px-4 py-2 whitespace-nowrap">
    <input 
        type="number" 
        v-model="unit.floor" 
        min="0" 
        max="100"
        class="border-transparent rounded font-medium text-slate-900 bg-transparent hover:bg-slate-100 focus:bg-white focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 py-1 px-2 w-16 text-center transition-colors"
        :placeholder="unit.floor ?? 'Piso'"
    />
    <span v-if="!unit.floor" class="text-xs text-slate-400 ml-1">(auto)</span>
</td>
```

### **Funcionalidades adicionales sugeridas:**
1. **Edición masiva:** Checkbox para seleccionar múltiples filas + input para cambiar todos los pisos seleccionados
2. **Restaurar automático:** Botón para volver a los pisos generados automáticamente
3. **Visualización:** Color diferente para pisos editados manualmente

## 🧪 PRUEBAS REQUERIDAS

### **Pruebas manuales:**
- [ ] Generar unidades con configuración automática
- [ ] Editar piso de una unidad individual
- [ ] Editar múltiples pisos a la vez
- [ ] Guardar y verificar en lista de unidades
- [ ] Probar con valor vacío (debería usar automático)
- [ ] Probar con valores negativos (debería validar)

### **Validaciones:**
- [ ] Piso debe ser número entero
- [ ] Piso debe ser ≥ 0 (planta baja = 0 o 1 según configuración)
- [ ] Piso máximo razonable (ej: 100)
- [ ] Campos vacíos usan valor automático

## ⚠️ RIESGOS

1. **Pisos inconsistentes:** Usuario podría poner piso 10 en edificio de 5 pisos
2. **Performance:** Si hay muchas unidades (1000+), inputs editables podrían afectar rendimiento
3. **Compatibilidad:** Cambios podrían afectar generación automática existente

## 🛡️ MITIGACIÓN DE RIESGOS

1. **Validación en frontend:** `min="0" max="100"` en input
2. **Validación en backend:** Asegurar integer en `UnitGeneratorService`
3. **Lazy loading:** Si hay muchas unidades, considerar paginación en vista previa
4. **Backward compatibility:** Mantener generación automática como fallback

## 📝 NOTAS TÉCNICAS

- El campo `floor` ya existe en modelo `Unit` como integer
- El servicio ya genera pisos automáticamente basado en `initial_floor` y distribución
- Solo necesitamos hacerlo editable en el frontend
- Considerar si queremos permitir decimales (ej: "Planta 2.5" para mezzanine)

## 🔗 DEPENDENCIAS

- **Ninguna:** Puede implementarse independientemente
- **Relacionada con:** TASK-012 (Design System) - podríamos usar componentes unificados

## 📊 MÉTRICAS DE ÉXITO

- [ ] **100% editable:** Todos los pisos editables en vista previa
- [ ] **0 errores:** Validación previene datos inválidos
- [ ] **UX mejorada:** Usuarios pueden ajustar pisos fácilmente
- [ ] **Performance:** No degradación en generación de 100+ unidades

---

## 🗓️ SEGUIMIENTO

| Fecha | Estado | Horas | Comentarios |
|-------|--------|-------|-------------|
| 2026-03-24 | 📝 Por hacer | 0 | Creada la tarea |
| | | | |
| | | | |

## ✅ CRITERIOS DE COMPLETACIÓN

- [ ] Input editable para piso en vista previa
- [ ] Validación frontend (números enteros, rango)
- [ ] Mantener valor por defecto del generador
- [ ] Testing completo manual
- [ ] Commit: `git commit -m "TASK-017: Nivel de piso editable en generación masiva"`
- [ ] Actualizar este archivo con estado ✅ Completado

---

**Última actualización:** 2026-03-24  
**Próxima revisión:** 2026-03-25  
**Relacionado con:** Módulo de unidades, generación masiva