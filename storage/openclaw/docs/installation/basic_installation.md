# 🚀 Guía de Instalación Básica - OpenClaw Portal

## 📋 Prerrequisitos
- [ ] Servidor Ubuntu 22.04 LTS o superior
- [ ] PHP 8.2+ con extensiones requeridas
- [ ] Apache 2.4+ o Nginx
- [ ] Composer 2.0+
- [ ] Node.js 18+ y npm
- [ ] Git

## 🛠️ Instalación Automática

### Opción 1: Script de instalación completo
```bash
# Descargar script de instalación
cd /root/.openclaw/workspace/scripts
chmod +x setup-openclaw-portal.sh
./setup-openclaw-portal.sh
```

### Opción 2: Instalación manual paso a paso

#### 1. Clonar repositorio
```bash
cd /var/www
git clone https://github.com/jhonatanrojas/openclaw-portal.git
cd openclaw-portal
```

#### 2. Instalar dependencias PHP
```bash
composer install --no-dev --optimize-autoloader
```

#### 3. Instalar dependencias JavaScript
```bash
npm install --production
npm run build
```

#### 4. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

#### 5. Configurar base de datos
```bash
touch database/database.sqlite
php artisan migrate --force
```

#### 6. Configurar permisos
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 🌐 Configuración del Servidor Web

### Apache
```apache
<VirtualHost *:80>
    ServerName openclaw-portal.local
    DocumentRoot /var/www/openclaw-portal/public
    
    <Directory /var/www/openclaw-portal/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx
```nginx
server {
    listen 80;
    server_name openclaw-portal.local;
    root /var/www/openclaw-portal/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
    }
}
```

## 🔧 Configuración Inicial

### 1. Acceder al portal
```
URL: http://tu-servidor:8082/
Usuario: admin@openclaw.test
Contraseña: password
```

### 2. Cambiar credenciales por defecto
1. Iniciar sesión como administrador
2. Ir a Perfil → Configuración
3. Actualizar email y contraseña

### 3. Configurar integración con OpenClaw
1. Ir a Configuración → OpenClaw Integration
2. Especificar ruta del workspace: `/root/.openclaw/workspace`
3. Guardar configuración

## 🧪 Verificación de la Instalación

### Comandos de verificación
```bash
# Verificar servicios
systemctl status apache2
systemctl status php8.2-fpm

# Verificar acceso web
curl -I http://localhost:8082/

# Verificar base de datos
php artisan db:show

# Verificar logs
tail -f storage/logs/laravel.log
```

### Página de estado
Accede a la página de estado para verificar todos los componentes:
```
http://localhost:8082/status.html
```

## ❓ Solución de Problemas Comunes

### Problema: Error 500 al acceder al portal
**Solución:**
```bash
# Verificar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Verificar logs
tail -f storage/logs/laravel.log

# Limpiar cache
php artisan config:clear
php artisan cache:clear
```

### Problema: Base de datos de solo lectura
**Solución:**
```bash
chown www-data:www-data database/database.sqlite
chmod 664 database/database.sqlite
```

### Problema: Assets no cargan
**Solución:**
```bash
npm run build
php artisan storage:link
```

## 📚 Recursos Adicionales
- [Documentación oficial de Laravel](https://laravel.com/docs)
- [Foro de soporte de OpenClaw](https://github.com/jhonatanrojas/openclaw-portal/issues)
- [Guía de troubleshooting avanzado](troubleshooting/advanced_troubleshooting.md)
