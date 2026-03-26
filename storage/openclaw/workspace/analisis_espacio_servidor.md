# 📊 Análisis de Espacio en el Servidor

## ⚠️ **ALERTA CRÍTICA: DISCO CASI LLENO**
**Uso total:** 97% (38GB de 39GB)
**Espacio disponible:** 1.2GB solamente

## 📁 **TOP 10 DIRECTORIOS QUE MÁS ESPACIO OCUPAN**

### **1. Docker - 8.8GB+**
- **`/var/lib/docker/overlay2`**: 7.6GB
- **`/var/lib/docker/volumes`**: 1.1GB
- **`/var/lib/docker/containers`**: 76MB
- **Total Docker:** ~8.8GB

### **2. Logs del Sistema - 2.6GB+**
- **`/var/log/journal`**: 2.0GB (logs de systemd)
- **`/var/log/proftpd`**: 450MB (logs de FTP)
- **`/var/log/btmp`**: 132MB (logs de login fallidos)
- **`/var/log/auth.log*`**: 56MB (logs de autenticación)
- **`/var/log/syslog*`**: 30MB+ (logs del sistema)
- **`/var/log/fail2ban.log*`**: 30MB (logs de fail2ban)

### **3. Proyecto Condominio Management - 566MB**
- **`/home/torreclick/condominio-management`**: 566MB
  - **`node_modules`**: 394MB (dependencias Node.js)
  - **`vendor`**: 136MB (dependencias PHP)
  - **`public`**: 12MB (assets compilados)
  - **`resources`**: 3.5MB
  - **`app`**: 3.0MB

### **4. Sistema - 3.3GB**
- **`/usr/lib`**: 3.3GB (librerías del sistema)

### **5. Otros directorios del sistema**
- **`/usr/share`**: 854MB
- **`/usr/bin`**: 574MB
- **`/usr/src`**: 281MB
- **`/usr/include`**: 93MB

## 🐳 **ANÁLISIS DETALLADO DE DOCKER**

### **Contenedores más grandes en overlay2:**
1. **668MB** - `5af2ad662518ea8a9d4ac5886c4e1759d7f162c4ddf156c8e5098aaec920b8b9`
2. **591MB** - `9d64ae6a23b088bda84b4f6723b7c7df19182109cc0e158fda08d6bde790c499`
3. **524MB** - `24f05ae5b0197e424c1a7339785d5083a34a31c6c2508dc55a18c15bbd48759c`
4. **441MB** - 6 contenedores de ~441MB cada uno

### **Contenedores activos (vistos anteriormente):**
1. `torreclick-php-1` (PHP)
2. `torreclick-nginx-1` (Nginx)
3. `torreclick-mysql-1` (MariaDB)
4. `torreclick-redis-1` (Redis)
5. `condominio_condominio-app-redis-1` (Redis adicional)
6. `torreclick-phpmyadmin` (PHPMyAdmin)
7. `torreclick-redis-ui` (Redis Commander)
8. `torreclick-mailpit` (Mailpit)

## 📝 **ANÁLISIS DE LOGS**

### **Logs problemáticos:**
1. **`/var/log/journal` (2.0GB)** - Logs de systemd
2. **`/var/log/proftpd` (450MB)** - Logs de FTP (¿necesarios?)
3. **`/var/log/btmp` (132MB)** - Logs de intentos fallidos de login

## 🚨 **RECOMENDACIONES PARA LIBERAR ESPACIO**

### **1. Limpiar logs del sistema (PRIORIDAD ALTA)**
```bash
# Limpiar logs de systemd (mantener solo últimos 2 días)
sudo journalctl --vacuum-time=2d

# Rotar logs manualmente
sudo logrotate -f /etc/logrotate.conf

# Limpiar logs específicos grandes
sudo truncate -s 0 /var/log/btmp
sudo truncate -s 0 /var/log/proftpd/*.log
```

### **2. Limpiar Docker (PRIORIDAD ALTA)**
```bash
# Eliminar contenedores detenidos
docker container prune -f

# Eliminar imágenes no utilizadas
docker image prune -a -f

# Eliminar volúmenes no utilizados
docker volume prune -f

# Eliminar build cache
docker builder prune -f

# Limpiar todo (cuidado!)
docker system prune -a -f --volumes
```

### **3. Limpiar proyecto condominio (PRIORIDAD MEDIA)**
```bash
# Limpiar cache de npm (dentro del proyecto)
cd /home/torreclick/condominio-management
rm -rf node_modules/.cache

# Limpiar cache de composer
rm -rf vendor/*/tests
rm -rf vendor/*/test
rm -rf vendor/*/docs
```

### **4. Limpiar sistema (PRIORIDAD BAJA)**
```bash
# Limpiar cache de apt
sudo apt-get clean
sudo apt-get autoclean

# Eliminar kernels antiguos
sudo apt-get autoremove --purge
```

## 📈 **ESTIMACIÓN DE ESPACIO A LIBERAR**

### **Liberación inmediata (conservadora):**
1. **Logs de systemd:** 1.5GB (manteniendo 500MB)
2. **Logs FTP:** 400MB
3. **Docker:** 2-3GB (eliminando imágenes/volúmenes no usados)
4. **Cache npm/composer:** 100-200MB

**Total estimado:** 4-5GB liberados

### **Liberación agresiva:**
1. **Todos los logs antiguos:** 2.6GB
2. **Todo Docker no esencial:** 4-5GB
3. **Cache del sistema:** 500MB-1GB

**Total estimado:** 7-8GB liberados

## 🔍 **PASOS DE VERIFICACIÓN**

### **1. Verificar espacio antes y después:**
```bash
df -h
```

### **2. Monitorear logs grandes:**
```bash
# Encontrar archivos mayores a 100MB
find / -type f -size +100M 2>/dev/null | xargs du -h | sort -rh
```

### **3. Verificar uso por usuario:**
```bash
du -sh /home/* 2>/dev/null
```

## ⚠️ **ADVERTENCIAS IMPORTANTES**

1. **No eliminar logs sin verificar** si son necesarios para auditoría
2. **Hacer backup** antes de limpiar Docker si hay datos importantes
3. **Verificar contenedores activos** antes de limpiar Docker
4. **Mantener logs recientes** para debugging

## 🎯 **PLAN DE ACCIÓN RECOMENDADO**

### **Fase 1 (Inmediata - 5 minutos):**
1. Limpiar logs de systemd
2. Rotar logs manualmente
3. Eliminar contenedores Docker detenidos

### **Fase 2 (15 minutos):**
1. Limpiar imágenes Docker no utilizadas
2. Limpiar cache de npm/composer
3. Limpiar cache de apt

### **Fase 3 (Opcional):**
1. Revisar si se necesitan todos los contenedores
2. Considerar mover datos a almacenamiento externo
3. Configurar rotación automática de logs

---

**Estado actual:** ⚠️ **CRÍTICO - 97% de uso del disco**
**Espacio disponible:** 1.2GB
**Riesgo:** El sistema puede dejar de funcionar si se llena el disco

*Análisis generado por Claw 🐾 - Familiar Digital de rangerdev*