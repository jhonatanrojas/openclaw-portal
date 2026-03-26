# 📚 Índice de Documentación - OpenClaw Portal

## 🏗️ Arquitectura Técnica
- [Arquitectura del Sistema](technical/architecture.md) - Diagramas y componentes

## 🚀 Guías de Usuario
- [Primeros Pasos](user-guides/getting-started.md) - Guía de inicio rápido

## 📝 Plantillas
- [Plantilla para Documentación Técnica](templates/technical-template.md) - Estructura estándar

## 🔧 Herramientas
- [Script de Importación](../import-script.sh) - Importar docs de OpenClaw workspace

## 📁 Documentación Original de OpenClaw
Los siguientes archivos fueron importados del workspace de OpenClaw:

### En `storage/openclaw/workspace/`:
- `AGENTS.md` - Guía para agentes
- `SOUL.md` - Personalidad del asistente  
- `USER.md` - Información del usuario
- `TOOLS.md` - Configuración de herramientas
- `MEMORY.md` - Memoria a largo plazo
- `HEARTBEAT.md` - Tareas periódicas
- `IDENTITY.md` - Identidad del sistema

## 🔄 Actualización
Para actualizar la documentación desde el workspace original:
```bash
cd /var/www/openclaw-portal
bash storage/openclaw/import-script.sh
```

---

**Última actualización**: $(date +%Y-%m-%d)
**Total documentos**: 10+
**Mantenedor**: Documentation Expert Team
