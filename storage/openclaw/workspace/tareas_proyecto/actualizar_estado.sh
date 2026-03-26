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
