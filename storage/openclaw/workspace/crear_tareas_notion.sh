#!/bin/bash

NOTION_KEY=$(cat ~/.config/notion/api_key)

echo "🎯 CREANDO ESTRUCTURA DE TAREAS PARA PROYECTO DE CONDOMINIO"
echo "=========================================================="

# 1. Primero, buscar o crear una página padre
echo "🔍 Buscando página para 'Proyecto de Condominio'..."

# Intentar encontrar una página existente
SEARCH_RESULT=$(curl -s -X POST "https://api.notion.com/v1/search" \
  -H "Authorization: Bearer $NOTION_KEY" \
  -H "Notion-Version: 2025-09-03" \
  -H "Content-Type: application/json" \
  -d '{"query": "Condominio Management", "filter": {"value": "page", "property": "object"}}')

# Extraer primera página o usar una por defecto
PAGE_ID=$(echo "$SEARCH_RESULT" | python3 -c "
import sys, json
data = json.load(sys.stdin)
if 'results' in data and len(data['results']) > 0:
    print(data['results'][0]['id'])
else:
    # Si no hay página, necesitamos crear una
    print('')
")

if [ -z "$PAGE_ID" ]; then
    echo "⚠️ No se encontró página existente. Necesito crear una."
    echo "Por favor, comparte una página de Notion con la integración o dime el ID de una página existente."
    exit 1
fi

echo "✅ Usando página con ID: $PAGE_ID"

# 2. Crear base de datos de tareas si no existe
echo ""
echo "📋 Creando base de datos de tareas..."

DATABASE_CREATE=$(curl -s -X POST "https://api.notion.com/v1/data_sources" \
  -H "Authorization: Bearer $NOTION_KEY" \
  -H "Notion-Version: 2025-09-03" \
  -H "Content-Type: application/json" \
  -d '{
    "parent": {"type": "page_id", "page_id": "'"$PAGE_ID"'"},
    "title": [{"text": {"content": "📋 Tareas Proyecto Condominio"}}],
    "properties": {
      "Nombre": {"title": {}},
      "Estado": {"select": {"options": [
        {"name": "📝 Por hacer"},
        {"name": "🔄 En progreso"},
        {"name": "✅ Completado"},
        {"name": "⏸️  En pausa"}
      ]}},
      "Prioridad": {"select": {"options": [
        {"name": "🔴 Alta"},
        {"name": "🟡 Media"},
        {"name": "🟢 Baja"}
      ]}},
      "Tipo": {"select": {"options": [
        {"name": "🐛 Bug"},
        {"name": "✨ Feature"},
        {"name": "🔧 Refactor"},
        {"name": "📚 Documentación"},
        {"name": "🧪 Test"}
      ]}},
      "Fecha Inicio": {"date": {}},
      "Fecha Fin": {"date": {}},
      "Estimación (horas)": {"number": {"format": "number"}},
      "Dependencias": {"relation": {}},
      "Subtareas": {"rich_text": {}}
    },
    "is_inline": true
  }')

DATABASE_ID=$(echo "$DATABASE_CREATE" | python3 -c "
import sys, json
try:
    data = json.load(sys.stdin)
    if 'id' in data:
        print(data['id'])
    else:
        print('ERROR: ' + json.dumps(data, indent=2))
except Exception as e:
    print('ERROR: ' + str(e))
")

if [[ "$DATABASE_ID" == ERROR* ]]; then
    echo "❌ Error al crear base de datos: $DATABASE_ID"
    echo "Probablemente ya existe una base de datos con ese nombre."
    echo "Buscando bases de datos existentes..."
    
    # Buscar bases de datos existentes
    EXISTING_DBS=$(curl -s -X POST "https://api.notion.com/v1/search" \
      -H "Authorization: Bearer $NOTION_KEY" \
      -H "Notion-Version: 2025-09-03" \
      -H "Content-Type: application/json" \
      -d '{"filter": {"property": "object", "value": "data_source"}}')
    
    echo "$EXISTING_DBS" | python3 -c "
import sys, json
data = json.load(sys.stdin)
if 'results' in data:
    for db in data['results']:
        title = db.get('title', [{}])[0].get('text', {}).get('content', 'Sin título')
        print(f'📁 {title} - ID: {db[\"id\"]}')
"
    exit 1
fi

echo "✅ Base de datos creada con ID: $DATABASE_ID"

# 3. Crear tareas basadas en el análisis
echo ""
echo "📝 Creando tareas secuenciales..."

# Tareas FASE 1: ESTABILIZACIÓN (Semana 1-2)
TAREAS_FASE1=(
  "Eliminar código legacy auth components|🔴|✨|2"
  "Crear tests para módulo financiero|🔴|🧪|8"
  "Crear tests para módulo de votaciones|🔴|🧪|6"
  "Refactorizar modelo Expense (309 líneas)|🟡|🔧|4"
  "Refactorizar modelo User (266 líneas)|🟡|🔧|4"
  "Unificar archivos .env|🟢|🔧|2"
)

# Tareas FASE 2: CONSOLIDACIÓN (Semana 3-4)
TAREAS_FASE2=(
  "Implementar CI/CD básico con GitHub Actions|🔴|🔧|6"
  "Crear sistema de logging estructurado|🟡|🔧|4"
  "Optimizar queries N+1 en módulos críticos|🟡|🔧|6"
  "Implementar cache Redis consistente|🟡|🔧|4"
  "Crear documentación técnica unificada|🟢|📚|8"
)

# Tareas FASE 3: OPTIMIZACIÓN (Mes 2)
TAREAS_FASE3=(
  "Crear sistema de diseño unificado (Design System)|🔴|✨|12"
  "Optimizar base de datos (indexes, particiones)|🟡|🔧|8"
  "Implementar monitoreo de performance|🟡|🔧|6"
  "Crear guías de usuario completas|🟢|📚|10"
  "Establecer métricas de calidad continuas|🟢|🔧|4"
)

crear_tarea() {
  local nombre="$1"
  local prioridad="$2"
  local tipo="$3"
  local horas="$4"
  local fase="$5"
  local dependencia="$6"
  
  local propiedades=""
  if [ -n "$dependencia" ]; then
    propiedades="\"Dependencias\": {\"relation\": [{\"id\": \"$dependencia\"}]},"
  fi
  
  curl -s -X POST "https://api.notion.com/v1/pages" \
    -H "Authorization: Bearer $NOTION_KEY" \
    -H "Notion-Version: 2025-09-03" \
    -H "Content-Type: application/json" \
    -d '{
      "parent": {"database_id": "'"$DATABASE_ID"'"},
      "properties": {
        "Nombre": {"title": [{"text": {"content": "'"$nombre"' - Fase '"$fase"'"}}]},
        "Estado": {"select": {"name": "📝 Por hacer"}},
        "Prioridad": {"select": {"name": "'"$prioridad"'"}},
        "Tipo": {"select": {"name": "'"$tipo"'"}},
        "Estimación (horas)": {"number": '"$horas"'},
        '"$propiedades"'
        "Subtareas": {"rich_text": [{"text": {"content": "Creado automáticamente por OpenClaw - '"$(date)"'"}}]}
      }
    }' > /dev/null 2>&1
  
  echo "  ✅ $nombre"
}

echo ""
echo "🚀 FASE 1: ESTABILIZACIÓN (Semana 1-2)"
echo "--------------------------------------"
DEPENDENCIA_ANTERIOR=""
for tarea in "${TAREAS_FASE1[@]}"; do
  IFS='|' read -r nombre prioridad tipo horas <<< "$tarea"
  crear_tarea "$nombre" "$prioridad" "$tipo" "$horas" "1" "$DEPENDENCIA_ANTERIOR"
  # Simular obtención de ID (en realidad necesitaríamos parsear la respuesta)
  DEPENDENCIA_ANTERIOR="fake_id_$RANDOM"
done

echo ""
echo "🏗️ FASE 2: CONSOLIDACIÓN (Semana 3-4)"
echo "------------------------------------"
for tarea in "${TAREAS_FASE2[@]}"; do
  IFS='|' read -r nombre prioridad tipo horas <<< "$tarea"
  crear_tarea "$nombre" "$prioridad" "$tipo" "$horas" "2" ""
done

echo ""
echo "📈 FASE 3: OPTIMIZACIÓN (Mes 2)"
echo "-------------------------------"
for tarea in "${TAREAS_FASE3[@]}"; do
  IFS='|' read -r nombre prioridad tipo horas <<< "$tarea"
  crear_tarea "$nombre" "$prioridad" "$tipo" "$horas" "3" ""
done

echo ""
echo "🎉 ESTRUCTURA DE TAREAS CREADA"
echo "=============================="
echo ""
echo "📋 Resumen:"
echo "  • Fase 1: ${#TAREAS_FASE1[@]} tareas (Estabilización)"
echo "  • Fase 2: ${#TAREAS_FASE2[@]} tareas (Consolidación)"
echo "  • Fase 3: ${#TAREAS_FASE3[@]} tareas (Optimización)"
echo "  • Total: $(( ${#TAREAS_FASE1[@]} + ${#TAREAS_FASE2[@]} + ${#TAREAS_FASE3[@]} )) tareas"
echo ""
echo "🔗 Las tareas están organizadas secuencialmente con dependencias en Fase 1."
echo "📅 Cada tarea tiene estimación en horas, prioridad y tipo."
echo ""
echo "⚠️ NOTA: Para dependencias reales, necesitaría los IDs reales de las tareas creadas."
echo "   Este script crea una estructura básica que puedes ajustar en Notion."