#!/bin/bash

echo "=== UNIFICANDO ARCHIVOS .ENV ==="
echo "================================="

# Crear archivo unificado basado en .env.example
cp .env.example .env.unified

echo "" >> .env.unified
echo "# ===========================================" >> .env.unified
echo "# CONFIGURACIÓN UNIFICADA" >> .env.unified
echo "# Generado automáticamente el $(date)" >> .env.unified
echo "# ===========================================" >> .env.unified
echo "" >> .env.unified

# Agregar sección de configuración de aplicación
echo "# ===========================================" >> .env.unified
echo "# CONFIGURACIÓN DE APLICACIÓN" >> .env.unified
echo "# ===========================================" >> .env.unified

# Extraer variables únicas de todos los archivos .env
for env_file in .env .env.docker .env.local .env.production; do
    if [ -f "$env_file" ]; then
        echo "" >> .env.unified
        echo "# Variables de $env_file:" >> .env.unified
        grep -v "^#" "$env_file" | grep -v "^$" | sort >> .env.unified
    fi
done

# Eliminar duplicados manteniendo el orden
awk '!seen[$0]++' .env.unified > .env.unified.tmp && mv .env.unified.tmp .env.unified

# Agregar documentación
echo "" >> .env.unified
echo "# ===========================================" >> .env.unified
echo "# DOCUMENTACIÓN" >> .env.unified
echo "# ===========================================" >> .env.unified
echo "" >> .env.unified
echo "# ARCHIVOS CONSOLIDADOS:" >> .env.unified
echo "# - .env (configuración local)" >> .env.unified
echo "# - .env.docker (configuración Docker)" >> .env.unified
echo "# - .env.local (configuración local específica)" >> .env.unified
echo "# - .env.production (configuración producción)" >> .env.unified
echo "" >> .env.unified
echo "# USO:" >> .env.unified
echo "# 1. Copiar este archivo a .env: cp .env.unified .env" >> .env.unified
echo "# 2. Ajustar valores según entorno" >> .env.unified
echo "# 3. Eliminar archivos .env.* individuales" >> .env.unified

echo "✅ Archivo .env.unified creado"
echo ""
echo "=== RESUMEN ==="
echo "Archivo original .env.example: $(wc -l < .env.example) líneas"
echo "Archivo unificado .env.unified: $(wc -l < .env.unified) líneas"
echo ""
echo "=== PRÓXIMOS PASOS ==="
echo "1. Revisar .env.unified"
echo "2. cp .env.unified .env"
echo "3. Eliminar archivos .env.* redundantes"
echo "4. Actualizar docker-compose.yml si es necesario"