# 📋 DOCUMENTACIÓN DE VARIABLES DE ENTORNO

**Proyecto:** Sistema de Administración de Condominios  
**Archivo unificado:** `.env.unified`  
**Generado:** 2026-03-24  
**Líneas:** 124  

---

## 🏗️ **ESTRUCTURA DEL ARCHIVO .ENV**

### **Archivos consolidados:**
1. `.env.example` - Plantilla base (82 líneas)
2. `.env` - Configuración local actual
3. `.env.docker` - Configuración para Docker
4. `.env.local` - Configuración local específica
5. `.env.production` - Configuración producción

### **Nueva estructura recomendada:**
```
.env.example       # Plantilla con valores por defecto
.env               # Configuración real (gitignored)
.env.unified       # Este archivo (referencia)
ENV_VARIABLES.md   # Esta documentación
```

---

## 🔧 **VARIABLES CRÍTICAS**

### **1. CONFIGURACIÓN DE APLICACIÓN (APP_)**
| Variable | Descripción | Valor por defecto | Requerido |
|----------|-------------|-------------------|-----------|
| `APP_NAME` | Nombre de la aplicación | `TorreClick` | ✅ |
| `APP_ENV` | Entorno (local/production) | `local` | ✅ |
| `APP_KEY` | Clave de encriptación | Generada | ✅ |
| `APP_DEBUG` | Modo debug | `true` | ⚠️ |
| `APP_URL` | URL de la aplicación | `http://localhost` | ✅ |

### **2. BASE DE DATOS (DB_)**
| Variable | Descripción | Valor Docker | Valor Local |
|----------|-------------|--------------|-------------|
| `DB_CONNECTION` | Tipo de conexión | `mysql` | `mysql` |
| `DB_HOST` | Host de la base de datos | `mysql` | `127.0.0.1` |
| `DB_PORT` | Puerto | `3306` | `3306` |
| `DB_DATABASE` | Nombre de la BD | `condominio` | `condominio` |
| `DB_USERNAME` | Usuario | `condominio_user` | `root` |
| `DB_PASSWORD` | Contraseña | `secret` | ` ` |

### **3. REDIS (REDIS_)**
| Variable | Descripción | Valor Docker |
|----------|-------------|--------------|
| `REDIS_CLIENT` | Cliente Redis | `phpredis` |
| `REDIS_HOST` | Host Redis | `redis` |
| `REDIS_PORT` | Puerto Redis | `6379` |
| `REDIS_PASSWORD` | Contraseña | ` ` |
| `REDIS_QUEUE_DB` | BD para colas | `1` |

### **4. COLA Y CACHE (QUEUE_, CACHE_)**
| Variable | Descripción | Valor Recomendado |
|----------|-------------|-------------------|
| `QUEUE_CONNECTION` | Conexión para colas | `redis` |
| `CACHE_STORE` | Almacenamiento de cache | `redis` |
| `SESSION_DRIVER` | Driver de sesiones | `redis` |

### **5. CORREO (MAIL_)**
| Variable | Descripción | Valor Desarrollo |
|----------|-------------|-------------------|
| `MAIL_MAILER` | Método de envío | `log` |
| `MAIL_HOST` | Host SMTP | `mailpit` |
| `MAIL_PORT` | Puerto SMTP | `1025` |
| `MAIL_USERNAME` | Usuario SMTP | `null` |
| `MAIL_PASSWORD` | Contraseña SMTP | `null` |

---

## 🐳 **CONFIGURACIÓN DOCKER ESPECÍFICA**

### **Variables exclusivas de Docker:**
```env
# Servicios Docker
DB_HOST=mysql           # Nombre del servicio en docker-compose
REDIS_HOST=redis        # Nombre del servicio Redis
MAIL_HOST=mailpit       # Servicio Mailpit para testing

# Configuración específica Docker
DOCKER_APP_PORT=8080
DOCKER_DB_PORT=3306
DOCKER_REDIS_PORT=6379
```

### **docker-compose.yml dependencies:**
```yaml
services:
  app:
    environment:
      - DB_HOST=mysql      # ← Referencia al servicio mysql
      - REDIS_HOST=redis   # ← Referencia al servicio redis
```

---

## 🔄 **MIGRACIÓN A ARCHIVO ÚNICO**

### **Paso 1: Backup de archivos actuales**
```bash
cp .env .env.backup.$(date +%Y%m%d)
cp .env.docker .env.docker.backup.$(date +%Y%m%d)
```

### **Paso 2: Usar archivo unificado**
```bash
# Opción A: Mantener .env actual, eliminar redundantes
cp .env.unified .env.completo  # Para referencia
# Eliminar .env.docker, .env.local, .env.production si no se necesitan

# Opción B: Reemplazar todo
cp .env.unified .env
rm .env.docker .env.local .env.production
```

### **Paso 3: Actualizar docker-compose.yml**
Revisar que las variables en `docker-compose.yml` coincidan con `.env`

---

## ⚠️ **VARIABLES SENSIBLES (NO COMMITTEAR)**

### **Gitignored por defecto:**
```bash
.env
.env.local
.env.production
*.backup
```

### **Contenido sensible típico:**
- Claves de API (`APP_KEY`, `API_KEYS`)
- Credenciales de base de datos (`DB_PASSWORD`)
- Credenciales de correo (`MAIL_PASSWORD`)
- Claves de servicios externos

---

## 🛠️ **SOLUCIÓN DE PROBLEMAS**

### **Problema: Variables duplicadas**
```bash
# Encontrar duplicados
sort .env | uniq -d
```

### **Problema: Variables faltantes**
```bash
# Comparar con plantilla
diff .env.example .env | grep "<" | grep -v "#"
```

### **Problema: Docker no lee variables**
```bash
# Verificar que estén en docker-compose.yml
grep -n "environment:" docker-compose.yml
```

---

## 📝 **MEJORES PRÁCTICAS**

1. **Una variable por línea** - No agrupar con `;`
2. **Sin espacios alrededor de `=`** - `VAR=valor` no `VAR = valor`
3. **Comentarios claros** - Usar `#` para documentación
4. **Secciones organizadas** - Agrupar por funcionalidad
5. **Valores por defecto seguros** - Para desarrollo local
6. **Backup antes de cambios** - Especialmente en producción

---

## 🔗 **ARCHIVOS RELACIONADOS**

- `docker-compose.yml` - Configuración de servicios Docker
- `config/database.php` - Configuración Laravel de BD
- `config/cache.php` - Configuración de cache
- `config/queue.php` - Configuración de colas

---

**Documentación generada por:** Claw 🐾  
**Última actualización:** 2026-03-24  
**Basado en:** `.env.unified` (consolidación de 4 archivos)