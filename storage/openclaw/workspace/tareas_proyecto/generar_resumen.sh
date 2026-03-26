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
