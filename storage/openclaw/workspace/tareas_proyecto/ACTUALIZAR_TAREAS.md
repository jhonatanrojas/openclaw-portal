# 🔄 ACTUALIZACIÓN DEL SISTEMA DE TAREAS

**Fecha:** 2026-03-24  
**Cambio:** Agregada nueva tarea TASK-017  
**Total tareas ahora:** 17  
**Horas totales ahora:** 98 horas  

---

## 🆕 **NUEVA TAREA AGREGADA:**

### **TASK-017: Nivel de piso editable en generación masiva de unidades**
- **Tipo:** ✨ Feature  
- **Prioridad:** 🟡 Media  
- **Estimación:** 4 horas  
- **Ubicación:** `tareas_proyecto/TASK-017.md`  
- **Descripción:** Hacer editable el campo "piso" en la vista previa de generación masiva de unidades

---

## 📊 **RESUMEN ACTUALIZADO:**

| Fase | Tareas | Horas | Estado |
|------|--------|-------|--------|
| **Fase 1: Estabilización** | 6 | 26h | 📝 Por hacer |
| **Fase 2: Consolidación** | 5 | 28h | 📅 Planificado |
| **Fase 3: Optimización** | 5 | 40h | 📅 Planificado |
| **🆕 Nueva Feature** | **1** | **4h** | 📝 Por hacer |
| **TOTAL** | **17** | **98h** | **~2.5 meses** |

---

## 🎯 **RECOMENDACIÓN DE PRIORIDAD:**

La **TASK-017** puede realizarse en **paralelo** con las tareas de Fase 1 porque:

1. **Es independiente:** No depende de otras tareas
2. **Es de frontend:** Solo modifica componente Vue
3. **Es rápida:** 4 horas estimadas
4. **Agrega valor inmediato:** Mejora UX del módulo de unidades

**Sugerencia:** Realizar después de TASK-001 (eliminar legacy) o en paralelo si hay múltiples desarrolladores.

---

## 📁 **ARCHIVOS ACTUALIZADOS:**

1. **`tareas_proyecto/TASK-017.md`** - Nueva tarea creada ✓
2. **`tareas_proyecto/resumen_pendientes.md`** - Actualizar manualmente
3. **`tareas_proyecto/README.md`** - Actualizar contadores

---

## 🔧 **PARA ACTUALIZAR EL SISTEMA:**

```bash
# Actualizar resumen pendientes
cd /root/.openclaw/workspace/tareas_proyecto

# Agregar TASK-017 al resumen (editar manualmente)
# O ejecutar:
echo "17. **TASK-017:** Nivel de piso editable en generación masiva (4h)" >> resumen_pendientes.md

# Actualizar README.md (cambiar 16 → 17)
sed -i 's/Total tareas: 16/Total tareas: 17/' README.md
sed -i 's/| **TOTAL** | **16** |/| **TOTAL** | **17** |/' README.md
```

---

## 🚀 **PRÓXIMOS PASOS RECOMENDADOS:**

### **Opción A (Secuencial):**
1. TASK-001 (2h) - Eliminar legacy auth
2. TASK-017 (4h) - Piso editable en generación masiva  
3. TASK-006 (2h) - Unificar .env
4. Continuar con Fase 1

### **Opción B (Paralelo):**
- **Desarrollador 1:** TASK-001 + TASK-006 (Fase 1)
- **Desarrollador 2:** TASK-017 (Nueva feature)

### **Opción C (Prioridad UX):**
1. TASK-017 (4h) - Mejora inmediata de UX
2. TASK-001 (2h) - Limpieza técnica
3. Continuar con plan original

---

## 💡 **INFORMACIÓN TÉCNICA DE TASK-017:**

### **Archivo a modificar:**
```bash
/home/torreclick/condominio-management/resources/js/Pages/Admin/Units/Partials/UnitGeneratorModal.vue
```

### **Línea específica (aproximadamente):**
```vue
<!-- Línea ~280 en el archivo actual -->
<td class="px-4 py-2 whitespace-nowrap text-sm text-slate-600 font-medium">
    {{ unit.floor ?? 'Planta Única' }}
</td>
```

### **Cambio sugerido:**
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

---

## 📝 **NOTA PARA rangerdev:**

**¿Prefieres que:**
1. **Ejecute TASK-017 ahora** (es rápida y agrega valor inmediato)
2. **Siga con TASK-001 primero** (plan original)
3. **Haga ambas en paralelo** (si tienes tiempo hoy)

**La TASK-017 es una mejora concreta que tus usuarios notarán inmediatamente.** 🐾