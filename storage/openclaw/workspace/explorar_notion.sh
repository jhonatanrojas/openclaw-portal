#!/bin/bash

NOTION_KEY=$(cat ~/.config/notion/api_key)

echo "📄 Explorando contenido de Notion..."
echo "===================================="

# Obtener todas las páginas (limitado a 10 para no sobrecargar)
PAGES=$(curl -s -X POST "https://api.notion.com/v1/search" \
  -H "Authorization: Bearer $NOTION_KEY" \
  -H "Notion-Version: 2025-09-03" \
  -H "Content-Type: application/json" \
  -d '{"page_size": 10}')

# Guardar respuesta para análisis
echo "$PAGES" > /tmp/notion_pages.json

echo "📊 Análisis de páginas encontradas:"
echo "-----------------------------------"

# Contar tipos de objetos
TOTAL=$(echo "$PAGES" | jq '.results | length' 2>/dev/null || echo "0")
echo "Total de elementos: $TOTAL"

# Mostrar primeros elementos con sus tipos
echo ""
echo "Primeros 5 elementos:"
echo "---------------------"

for i in $(seq 0 4); do
  if [ $i -lt $TOTAL ]; then
    ITEM=$(echo "$PAGES" | jq ".results[$i]" 2>/dev/null)
    if [ "$ITEM" != "null" ]; then
      OBJECT_TYPE=$(echo "$ITEM" | jq -r '.object' 2>/dev/null)
      TITLE=$(echo "$ITEM" | jq -r '.properties.title?.title[0]?.plain_text // .properties.Name?.title[0]?.plain_text // "Sin título"' 2>/dev/null)
      ID=$(echo "$ITEM" | jq -r '.id' 2>/dev/null)
      echo "$((i+1)). [$OBJECT_TYPE] $TITLE"
      echo "   ID: $ID"
      
      # Si es una página, mostrar propiedades disponibles
      if [ "$OBJECT_TYPE" = "page" ]; then
        PARENT_TYPE=$(echo "$ITEM" | jq -r '.parent.type' 2>/dev/null)
        if [ "$PARENT_TYPE" = "data_source_id" ]; then
          DATABASE_ID=$(echo "$ITEM" | jq -r '.parent.data_source_id' 2>/dev/null)
          echo "   📁 Pertenece a base de datos: $DATABASE_ID"
        fi
      fi
      echo ""
    fi
  fi
done

echo "🔍 Para buscar tareas pendientes, necesito:"
echo "1. Compartir tu base de datos de tareas con la integración"
echo "2. O darme el ID de la base de datos"
echo ""
echo "¿Tienes una base de datos llamada 'Tasks', 'Tareas', 'Todo', o similar?"