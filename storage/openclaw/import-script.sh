#!/bin/bash
# Script para importar documentación de OpenClaw workspace
# Ejecutar manualmente cuando sea necesario

SOURCE_DIR="/root/.openclaw/workspace"
DEST_DIR="/var/www/openclaw-portal/storage/openclaw/workspace"

echo "🔄 Importando documentación de OpenClaw..."
echo "Origen: $SOURCE_DIR"
echo "Destino: $DEST_DIR"
echo ""

# Crear directorio destino si no existe
mkdir -p "$DEST_DIR"

# Lista de archivos a importar
FILES=(
    "AGENTS.md"
    "SOUL.md" 
    "USER.md"
    "TOOLS.md"
    "MEMORY.md"
    "HEARTBEAT.md"
    "IDENTITY.md"
)

# Contadores
IMPORTED=0
SKIPPED=0
ERRORS=0

for file in "${FILES[@]}"; do
    SOURCE_FILE="$SOURCE_DIR/$file"
    DEST_FILE="$DEST_DIR/$file"
    
    if [ -f "$SOURCE_FILE" ]; then
        cp "$SOURCE_FILE" "$DEST_FILE"
        if [ $? -eq 0 ]; then
            echo "✅ Importado: $file"
            ((IMPORTED++))
        else
            echo "❌ Error importando: $file"
            ((ERRORS++))
        fi
    else
        echo "⚠️ No encontrado: $file"
        ((SKIPPED++))
    fi
done

echo ""
echo "📊 RESUMEN DE IMPORTACIÓN:"
echo "   ✅ Importados: $IMPORTED archivos"
echo "   ⚠️ No encontrados: $SKIPPED archivos"
echo "   ❌ Errores: $ERRORS archivos"
echo ""
echo "📁 Ubicación de archivos importados: $DEST_DIR"
echo "🔄 Para re-importar, ejecutar este script nuevamente"
