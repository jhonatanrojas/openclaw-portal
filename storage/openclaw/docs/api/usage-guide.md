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
