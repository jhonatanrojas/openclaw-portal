#!/bin/bash

NOTION_KEY=$(cat ~/.config/notion/api_key)

echo "🔍 Buscando bases de datos en Notion..."
echo "========================================"

# 1. Buscar todas las bases de datos (data sources)
DATABASES=$(curl -s -X POST "https://api.notion.com/v1/search" \
  -H "Authorization: Bearer $NOTION_KEY" \
  -H "Notion-Version: 2025-09-03" \
  -H "Content-Type: application/json" \
  -d '{"filter": {"property": "object", "value": "data_source"}}')

# Extraer nombres e IDs de bases de datos
echo "📋 Bases de datos encontradas:"
echo "$DATABASES" | jq -r '.results[] | "• \(.title[0]?.plain_text // "Sin título"): \(.id)"' 2>/dev/null || echo "  No se pudieron extraer nombres"

echo ""
echo "🔎 Buscando páginas que parezcan ser de tareas..."
echo "=================================================="

# 2. Buscar páginas con propiedades comunes de tareas
SEARCH_RESULTS=$(curl -s -X POST "https://api.notion.com/v1/search" \
  -H "Authorization: Bearer $NOTION_KEY" \
  -H "Notion-Version: 2025-09-03" \
  -H "Content-Type: application/json" \
  -d '{"query": "task todo tarea pendiente"}')

echo "Primeros resultados de búsqueda:"
echo "$SEARCH_RESULTS" | jq -r '.results[0:3] | .[] | "• \(.properties.title?.title[0]?.plain_text // .properties.Name?.title[0]?.plain_text // "Sin título"): \(.id)"' 2>/dev/null || echo "  No se encontraron resultados específicos"

echo ""
echo "🎯 Para encontrar tareas pendientes, necesito:"
echo "1. El ID de tu base de datos de tareas"
echo "2. Los nombres de las propiedades (Status, Due Date, etc.)"
echo ""
echo "¿Tienes una base de datos específica para tareas?"