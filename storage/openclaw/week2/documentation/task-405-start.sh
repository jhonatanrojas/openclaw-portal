#!/bin/bash
echo "📚 Documentation Expert - Iniciando TASK-405"
echo "==========================================="
echo "Tarea: Documentación de API completa"
echo "Prioridad: Alta"
echo ""

cd /var/www/openclaw-portal

echo "🎯 Objetivos:"
echo "1. Crear documentación OpenAPI básica"
echo "2. Documentar endpoints existentes"
echo "3. Crear colección Postman"
echo "4. Generar guía de uso de API"
echo ""

echo "🚀 Iniciando implementación..."
echo ""

# Crear directorio para documentación API
mkdir -p storage/openclaw/docs/api

# Crear documentación OpenAPI básica
cat > storage/openclaw/docs/api/openapi.yaml << 'OPENAPIEOF'
openapi: 3.0.0
info:
  title: OpenClaw Portal API
  description: API para gestión de agentes, tareas y documentación
  version: 1.0.0
  contact:
    name: OpenClaw Team
    url: https://openclaw.deploymatrix.com
    email: api@openclaw.deploymatrix.com

servers:
  - url: http://openclaw.deploymatrix.com/api
    description: Servidor de producción

paths:
  /agents:
    get:
      summary: Listar agentes
      description: Obtiene lista de agentes con filtros opcionales
      parameters:
        - name: type
          in: query
          schema:
            type: string
            enum: [backend, frontend, devops, documentation, general]
          description: Filtrar por tipo de agente
        - name: status
          in: query
          schema:
            type: string
            enum: [active, inactive, busy]
          description: Filtrar por estado
        - name: page
          in: query
          schema:
            type: integer
            minimum: 1
            default: 1
          description: Número de página
      responses:
        '200':
          description: Lista de agentes obtenida exitosamente
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                    example: true
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/Agent'
                  pagination:
                    $ref: '#/components/schemas/Pagination'

  /agents/{id}:
    get:
      summary: Obtener agente por ID
      parameters:
        - name: id
          in: path
          required: true
          schema:
            type: integer
          description: ID del agente
      responses:
        '200':
          description: Agente obtenido exitosamente
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                    example: true
                  data:
                    $ref: '#/components/schemas/Agent'

  /documentation:
    get:
      summary: Listar documentación
      description: Obtiene lista de documentación con filtros de búsqueda
      parameters:
        - name: category
          in: query
          schema:
            type: string
            enum: [installation, configuration, api, agents, troubleshooting]
          description: Filtrar por categoría
        - name: search
          in: query
          schema:
            type: string
          description: Término de búsqueda en título y contenido
      responses:
        '200':
          description: Lista de documentación obtenida exitosamente
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                    example: true
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/Documentation'

components:
  schemas:
    Agent:
      type: object
      properties:
        id:
          type: integer
          example: 1
        name:
          type: string
          example: "Backend Specialist"
        type:
          type: string
          enum: [backend, frontend, devops, documentation, general]
          example: "backend"
        status:
          type: string
          enum: [active, inactive, busy]
          example: "active"
        task_count:
          type: integer
          example: 12
        completed_tasks:
          type: integer
          example: 45
        last_active_at:
          type: string
          format: date-time
          example: "2026-03-25T01:30:00+02:00"

    Documentation:
      type: object
      properties:
        id:
          type: integer
          example: 1
        title:
          type: string
          example: "Guía de instalación"
        content:
          type: string
          example: "Contenido de la documentación..."
        category:
          type: string
          enum: [installation, configuration, api, agents, troubleshooting]
          example: "installation"
        version:
          type: string
          example: "1.0.0"

    Pagination:
      type: object
      properties:
        current_page:
          type: integer
          example: 1
        last_page:
          type: integer
          example: 3
        per_page:
          type: integer
          example: 15
        total:
          type: integer
          example: 42
OPENAPIEOF

echo "✅ Documentación OpenAPI creada"
echo ""

# Crear guía de uso de API
cat > storage/openclaw/docs/api/usage-guide.md << 'GUIDEEOF'
# Guía de Uso de la API

## Introducción
La API del OpenClaw Portal permite gestionar agentes, tareas y documentación de manera programática.

## Autenticación
Actualmente la API utiliza autenticación básica. Próximamente se implementará OAuth2.

## Endpoints Principales

### Agentes
- `GET /api/agents` - Listar agentes
- `GET /api/agents/{id}` - Obtener agente específico
- `POST /api/agents` - Crear nuevo agente
- `PUT /api/agents/{id}` - Actualizar agente
- `DELETE /api/agents/{id}` - Eliminar agente

### Documentación
- `GET /api/documentation` - Listar documentación
- `GET /api/documentation/{id}` - Obtener documento específico
- `POST /api/documentation` - Crear nueva documentación
- `PUT /api/documentation/{id}` - Actualizar documentación
- `DELETE /api/documentation/{id}` - Eliminar documentación

## Ejemplos de Uso

### Listar agentes activos
```bash
curl -X GET "http://openclaw.deploymatrix.com/api/agents?status=active"
```

### Buscar documentación
```bash
curl -X GET "http://openclaw.deploymatrix.com/api/documentation?search=instalación"
```

### Crear nuevo agente
```bash
curl -X POST "http://openclaw.deploymatrix.com/api/agents" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Nuevo Agente",
    "type": "backend",
    "status": "active"
  }'
```

## Rate Limiting
- Límite: 100 requests por hora por IP
- Headers de respuesta:
  - `X-RateLimit-Limit`: Límite total
  - `X-RateLimit-Remaining`: Requests restantes
  - `X-RateLimit-Reset`: Tiempo hasta reset

## Códigos de Error
- `400`: Bad Request - Datos inválidos
- `401`: Unauthorized - Autenticación requerida
- `403`: Forbidden - Permisos insuficientes
- `404`: Not Found - Recurso no encontrado
- `429`: Too Many Requests - Rate limit excedido
- `500`: Internal Server Error - Error del servidor

## Próximas Mejoras
1. Autenticación OAuth2
2. Webhooks para eventos
3. Streaming de logs
4. Métricas en tiempo real
GUIDEEOF

echo "✅ Guía de uso de API creada"
echo ""

# Crear colección Postman básica
cat > storage/openclaw/docs/api/postman-collection.json << 'POSTMANEOF'
{
  "info": {
    "name": "OpenClaw Portal API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Agents",
      "item": [
        {
          "name": "List Agents",
          "request": {
            "method": "GET",
            "url": "http://openclaw.deploymatrix.com/api/agents"
          }
        },
        {
          "name": "Get Agent by ID",
          "request": {
            "method": "GET",
            "url": "http://openclaw.deploymatrix.com/api/agents/1"
          }
        }
      ]
    },
    {
      "name": "Documentation",
      "item": [
        {
          "name": "List Documentation",
          "request": {
            "method": "GET",
            "url": "http://openclaw.deploymatrix.com/api/documentation"
          }
        }
      ]
    }
  ]
}
POSTMANEOF

echo "✅ Colección Postman creada"
echo ""

# Marcar como en progreso
echo "TASK-405:in_progress" > storage/openclaw/week2/documentation/status.txt
echo "📝 Nota: Documentación básica creada"
echo "🔧 Pendiente: Documentar todos los endpoints, ejemplos completos, tests"
echo ""
echo "📚 Documentation Expert listo para continuar..."
