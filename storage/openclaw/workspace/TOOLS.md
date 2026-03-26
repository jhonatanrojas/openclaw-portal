# TOOLS.md - Local Notes

Skills define _how_ tools work. This file is for _your_ specifics — the stuff that's unique to your setup.

## What Goes Here

Things like:

- Camera names and locations
- SSH hosts and aliases
- Preferred voices for TTS
- Speaker/room names
- Device nicknames
- Anything environment-specific

## Examples

```markdown
### Cameras

- living-room → Main area, 180° wide angle
- front-door → Entrance, motion-triggered

### SSH

- home-server → 192.168.1.100, user: admin

### TTS

- Preferred voice: "Nova" (warm, slightly British)
- Default speaker: Kitchen HomePod
```

## Why Separate?

Skills are shared. Your setup is yours. Keeping them apart means you can update skills without losing your notes, and share skills without leaking your infrastructure.

---

Add whatever helps you do your job. This is your cheat sheet.

## 🌐 Dominios y Servicios (Acceso Rápido)

### **openclaw.deploymatrix.com** - File Share
- **URL:** http://localhost:8081/ (local) | http://openclaw.deploymatrix.com/ (con Cloudflare)
- **Raíz:** `/var/www/openclaw.deploymatrix.com/public_html/`
- **Gestor:** `openclaw-web-manager [comando]`
- **Uso principal:** Compartir screenshots, docs, logs entre agentes

### **torreclick.deploymatrix.com** - Condominio Management
- **URL:** https://torreclick.deploymatrix.com/
- **Login:** `admin.demo@condominio.test` / `password`
- **Tecnología:** Laravel 10 + Vue.js 3 + Inertia.js
- **Estado:** Producción activa

### **GitHub - Condominio Management**
- **Repo:** https://github.com/jhonatanrojas/condominio-management
- **PR actual:** #2 (feature/piso-editable-generacion-masiva)
- **Token issues:** GraphQL API tiene problemas, usar REST API

### **Servicios del Sistema**
- **Apache:** `systemctl status apache2`
- **MySQL/MariaDB:** Usuario `testing` para tests
- **Playwright:** v1.58.0 instalado para automatización
- **GitHub CLI:** Configurado pero con problemas de token

### **Credenciales de Prueba**
```bash
# MySQL Testing
DB_USER=testing
DB_PASS=testing
DB_HOST=127.0.0.1

# Aplicación Condominio
USER=admin.demo@condominio.test
PASS=password
```

### **Comandos Útiles**
```bash
# Ver estado del file share
openclaw-web-manager status

# Subir archivo
openclaw-web-manager upload /ruta/archivo.png screenshots/

# Testear aplicación
cd /home/torreclick/condominio-management
php artisan test --env=testing --filter="Financial"

# Ver logs Apache
tail -f /var/www/openclaw.deploymatrix.com/logs/access.log
```

### **Notas de Configuración**
- **SQLite para tests:** Más rápido, sin dependencias externas
- **Workflows excluyen tests problemáticos:** Ver `phpunit.ci.xml`
- **Cloudflare pendiente:** Para dominio openclaw.deploymatrix.com
- **Puerto 8081:** Acceso temporal hasta configurar DNS
