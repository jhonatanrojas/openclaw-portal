#!/bin/bash

echo "🚀 GENERANDO ESTRUCTURA COMPLETA DE TAREAS"
echo "=========================================="

# Crear estructura de carpetas
mkdir -p tareas_proyecto/fase_1_estabilizacion
mkdir -p tareas_proyecto/fase_2_consolidacion
mkdir -p tareas_proyecto/fase_3_optimizacion
mkdir -p tareas_proyecto/seguimiento
mkdir -p tareas_proyecto/plantillas

echo "✅ Estructura de carpetas creada"

# Plantilla para nuevas tareas
cat > tareas_proyecto/plantillas/nueva_tarea.md << 'EOF'
# 📋 TASK-XXX: [Nombre de la tarea]

**ID:** TASK-XXX  
**Fase:** [Número] - [Nombre de la fase]  
**Tipo:** [🔧 Refactor/✨ Feature/🧪 Test/📚 Documentación]  
**Prioridad:** [🔴 Alta/🟡 Media/🟢 Baja]  
**Estado:** [📝 Por hacer/🔄 En progreso/✅ Completado/⏸️ En pausa]  
**Estimación:** [X] horas  
**Creada:** YYYY-MM-DD  
**Responsable:** [Nombre]  
**Dependencias:** [TASK-XXX, TASK-YYY o Ninguna]  

---

## 🎯 OBJETIVO

[Descripción clara del objetivo de la tarea]

## 📋 SUBTAREAS

- [ ] [Subtarea 1]
- [ ] [Subtarea 2]
- [ ] [Subtarea 3]

## 📁 ARCHIVOS AFECTADOS

```
[ruta/archivo1.ext
ruta/archivo2.ext]
```

## 🧪 PRUEBAS REQUERIDAS

- [ ] [Prueba 1]
- [ ] [Prueba 2]

## ⚠️ RIESGOS

1. [Riesgo 1]
2. [Riesgo 2]

## 🛡️ MITIGACIÓN DE RIESGOS

1. [Mitigación 1]
2. [Mitigación 2]

## 📝 NOTAS

[Notas adicionales]

## 🔗 DEPENDENCIAS

- **TASK-XXX:** [Descripción de dependencia]

## 📊 MÉTRICAS DE ÉXITO

- [ ] [Métrica 1]
- [ ] [Métrica 2]

---

## 🗓️ SEGUIMIENTO

| Fecha | Estado | Horas | Comentarios |
|-------|--------|-------|-------------|
| YYYY-MM-DD | 📝 Por hacer | 0 | Creada la tarea |
| | | | |
| | | | |

## ✅ CRITERIOS DE COMPLETACIÓN

- [ ] [Criterio 1]
- [ ] [Criterio 2]

---

**Última actualización:** YYYY-MM-DD  
**Próxima revisión:** YYYY-MM-DD
EOF

echo "✅ Plantilla de tarea creada"

# Crear archivo de seguimiento diario
FECHA=$(date +%Y-%m-%d)
cat > tareas_proyecto/seguimiento/diario_${FECHA}.md << EOF
# 📊 SEGUIMIENTO DIARIO - ${FECHA}

**Fecha:** ${FECHA}  
**Responsable:** rangerdev  
**Duración:** [Horas trabajadas]  
**Estado general:** 🟡 En progreso

---

## 🎯 OBJETIVOS DEL DÍA

1. [Objetivo 1]
2. [Objetivo 2]

## 📝 ACTIVIDADES REALIZADAS

### 🕘 Mañana (9:00 - 13:00)
- [ ] [Actividad 1]
- [ ] [Actividad 2]

### 🕑 Tarde (14:00 - 18:00)
- [ ] [Actividad 3]
- [ ] [Actividad 4]

## ✅ LOGROS

- [Logro 1]
- [Logro 2]

## ⚠️ BLOQUEOS

1. [Bloqueo 1]
2. [Bloqueo 2]

## 🔄 PRÓXIMOS PASOS

1. [Paso 1 para mañana]
2. [Paso 2 para mañana]

## 📈 MÉTRICAS DEL DÍA

| Métrica | Valor | Objetivo | Estado |
|---------|-------|----------|--------|
| Tareas completadas | 0 | 2 | 🔴 |
| Horas productivas | 0 | 6 | 🔴 |
| Bloqueos resueltos | 0 | 1 | 🔴 |

## 💡 APRENDIZAJES

- [Aprendizaje 1]
- [Aprendizaje 2]

---

**Reporte generado automáticamente por:** Claw 🐾  
**Próximo reporte:** $(date -d "+1 day" +%Y-%m-%d)
EOF

echo "✅ Plantilla de seguimiento diario creada"

# Crear resumen de tareas pendientes
cat > tareas_proyecto/resumen_pendientes.md << 'EOF'
# 📋 RESUMEN DE TAREAS PENDIENTES

**Generado:** $(date)  
**Total tareas:** 16  
**Completadas:** 0  
**En progreso:** 0  
**Pendientes:** 16

---

## 🔴 PRIORIDAD ALTA (Fase 1)

### 📝 Por hacer:
1. **TASK-001:** Eliminar código legacy auth components (2h)
2. **TASK-002:** Crear tests para módulo financiero (8h)
3. **TASK-003:** Crear tests para módulo de votaciones (6h)
4. **TASK-004:** Refactorizar modelo Expense (309 líneas) (4h)
5. **TASK-005:** Refactorizar modelo User (266 líneas) (4h)
6. **TASK-006:** Unificar archivos .env (2h)

## 🟡 PRIORIDAD MEDIA (Fase 2)

### 📝 Por hacer:
7. **TASK-007:** Implementar CI/CD básico con GitHub Actions (6h)
8. **TASK-008:** Crear sistema de logging estructurado (4h)
9. **TASK-009:** Optimizar queries N+1 en módulos críticos (6h)
10. **TASK-010:** Implementar cache Redis consistente (4h)
11. **TASK-011:** Crear documentación técnica unificada (8h)

## 🟢 PRIORIDAD BAJA (Fase 3)

### 📝 Por hacer:
12. **TASK-012:** Crear sistema de diseño unificado (Design System) (12h)
13. **TASK-013:** Optimizar base de datos (indexes, particiones) (8h)
14. **TASK-014:** Implementar monitoreo de performance (6h)
15. **TASK-015:** Crear guías de usuario completas (10h)
16. **TASK-016:** Establecer métricas de calidad continuas (4h)

---

## 📊 ESTADÍSTICAS

| Prioridad | Tareas | Horas | % Total |
|-----------|--------|-------|---------|
| 🔴 Alta | 6 | 26 | 27.7% |
| 🟡 Media | 5 | 28 | 29.8% |
| 🟢 Baja | 5 | 40 | 42.5% |
| **TOTAL** | **16** | **94** | **100%** |

## 🎯 RECOMENDACIONES

1. **Comenzar con TASK-001** (más rápida y elimina deuda técnica)
2. **Seguir con TASK-002 y TASK-003** (tests críticos para estabilidad)
3. **Luego TASK-004 y TASK-005** (refactorización de modelos grandes)
4. **Finalizar Fase 1 con TASK-006** (unificación de configuración)

## 📅 PRÓXIMOS PLAZOS

- **Esta semana (24-28 Mar):** Completar TASK-001, TASK-006
- **Siguiente semana (31 Mar-4 Abr):** Completar TASK-002, TASK-003
- **Semana 3 (7-11 Abr):** Completar TASK-004, TASK-005

---

**Actualizado automáticamente por:** Claw 🐾  
**Próxima actualización:** Diaria a las 9:00 AM
EOF

echo "✅ Resumen de tareas pendientes creado"

# Crear script para actualizar estado
cat > tareas_proyecto/actualizar_estado.sh << 'EOF'
#!/bin/bash

# Script para actualizar estado de tareas
# Uso: ./actualizar_estado.sh TASK-001 "✅ Completado" "2 horas"

TASK=$1
ESTADO=$2
HORAS=$3
COMENTARIO=$4

if [ -z "$TASK" ]; then
    echo "❌ Error: Debes especificar el ID de la tarea"
    echo "Uso: $0 TASK-001 \"✅ Completado\" \"2 horas\" \"Comentario opcional\""
    exit 1
fi

# Buscar archivo de tarea
ARCHIVO=$(find . -name "${TASK}.md" -type f | head -1)

if [ -z "$ARCHIVO" ]; then
    echo "❌ No se encontró la tarea $TASK"
    exit 1
fi

echo "📝 Actualizando $TASK..."
echo "Archivo: $ARCHIVO"
echo "Nuevo estado: $ESTADO"
echo "Horas trabajadas: $HORAS"
echo "Comentario: ${COMENTARIO:-Sin comentario}"

# Actualizar estado en el archivo
if [ -n "$ESTADO" ]; then
    sed -i "s/Estado:.*/Estado: $ESTADO/" "$ARCHIVO"
fi

# Agregar entrada en seguimiento
FECHA=$(date +%Y-%m-%d)
LINEA="$FECHA | $ESTADO | $HORAS | ${COMENTARIO:-Actualización}"

# Encontrar la tabla de seguimiento y agregar línea
sed -i "/## 🗓️ SEGUIMIENTO/,/^---$/ {
    /|.*|.*|.*|/ {
        :a
        n
        /^---$/!ba
        i\\
$LINEA
    }
}" "$ARCHIVO"

echo "✅ Tarea $TASK actualizada correctamente"

# Actualizar resumen pendientes
./generar_resumen.sh

echo "📊 Resumen actualizado"
EOF

chmod +x tareas_proyecto/actualizar_estado.sh

# Crear script para generar resumen
cat > tareas_proyecto/generar_resumen.sh << 'EOF'
#!/bin/bash

echo "📊 GENERANDO RESUMEN ACTUALIZADO"
echo "================================"

# Contar tareas por estado
TOTAL=$(find . -name "TASK-*.md" -type f | wc -l)
COMPLETADAS=$(grep -l "Estado: ✅ Completado" $(find . -name "TASK-*.md" -type f) 2>/dev/null | wc -l)
EN_PROGRESO=$(grep -l "Estado: 🔄 En progreso" $(find . -name "TASK-*.md" -type f) 2>/dev/null | wc -l)
PENDIENTES=$((TOTAL - COMPLETADAS - EN_PROGRESO))

# Actualizar README.md
sed -i "s/| **Fase 1** | [0-9]* | [0-9]* | [0-9]* | [0-9]* |/| **Fase 1** | 6 | $COMPLETADAS | $EN_PROGRESO | $PENDIENTES |/" tareas_proyecto/README.md

echo "✅ Resumen actualizado:"
echo "   Total: $TOTAL"
echo "   Completadas: $COMPLETADAS"
echo "   En progreso: $EN_PROGRESO"
echo "   Pendientes: $PENDIENTES"
EOF

chmod +x tareas_proyecto/generar_resumen.sh

echo ""
echo "🎉 ESTRUCTURA COMPLETA CREADA"
echo "============================="
echo ""
echo "📁 Carpetas creadas:"
echo "  • tareas_proyecto/fase_1_estabilizacion"
echo "  • tareas_proyecto/fase_2_consolidacion"
echo "  • tareas_proyecto/fase_3_optimizacion"
echo "  • tareas_proyecto/seguimiento"
echo "  • tareas_proyecto/plantillas"
echo ""
echo "📄 Archivos creados:"
echo "  • TASK-001.md (tarea específica)"
echo "  • nueva_tarea.md (plantilla)"
echo "  • diario_${FECHA}.md (seguimiento)"
echo "  • resumen_pendientes.md"
echo "  • actualizar_estado.sh (script)"
echo "  • generar_resumen.sh (script)"
echo ""
echo "🚀 Para comenzar:"
echo "  1. Revisar tareas_proyecto/README.md"
echo "  2. Comenzar con TASK-001.md"
echo "  3. Usar ./actualizar_estado.sh para seguir progreso"
echo ""
echo "🐾 Listo para trabajar!"