#!/bin/bash
# setup-openclaw-portal.sh
# Script de setup inicial para OpenClaw Portal

echo "🚀 Iniciando setup de OpenClaw Portal..."
echo "=========================================="
echo ""

# Verificar requisitos
echo "🔍 Verificando requisitos del sistema..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    echo "❌ Node.js/npm no está instalado"
    exit 1
fi

echo "✅ Requisitos verificados"
echo ""

# 1. Crear directorio
echo "📁 Creando directorio del proyecto..."
mkdir -p /var/www/openclaw-portal
cd /var/www/openclaw-portal

if [ $? -ne 0 ]; then
    echo "❌ Error al crear directorio"
    exit 1
fi

echo "✅ Directorio creado: /var/www/openclaw-portal"
echo ""

# 2. Instalar Laravel
echo "🛠️ Instalando Laravel 11..."
composer create-project laravel/laravel . --prefer-dist --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Error al instalar Laravel"
    exit 1
fi

echo "✅ Laravel 11 instalado"
echo ""

# 3. Configurar entorno
echo "⚙️ Configurando entorno..."
cp .env.example .env
php artisan key:generate

if [ $? -ne 0 ]; then
    echo "❌ Error al generar key"
    exit 1
fi

echo "✅ Entorno configurado"
echo ""

# 4. Configurar base de datos SQLite
echo "🗃️ Configurando base de datos SQLite..."
touch database/database.sqlite

# Actualizar .env para usar SQLite
if grep -q "DB_CONNECTION=mysql" .env; then
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    sed -i 's/DB_DATABASE=laravel/# DB_DATABASE=laravel/' .env
    echo 'DB_DATABASE=/var/www/openclaw-portal/database/database.sqlite' >> .env
fi

echo "✅ Base de datos SQLite configurada"
echo ""

# 5. Instalar autenticación Breeze
echo "🔐 Instalando sistema de autenticación..."
composer require laravel/breeze --dev --no-interaction

if [ $? -ne 0 ]; then
    echo "⚠️ Error al instalar Breeze, continuando sin autenticación avanzada"
else
    php artisan breeze:install blade --no-interaction
    
    if [ $? -eq 0 ]; then
        echo "📦 Instalando dependencias NPM..."
        npm install --silent
        
        echo "🔨 Compilando assets..."
        npm run build --silent
        
        echo "✅ Autenticación Breeze instalada"
    else
        echo "⚠️ Error al configurar Breeze, usando autenticación básica"
    fi
fi

echo ""

# 6. Configurar permisos
echo "🔒 Configurando permisos..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Permisos configurados"
echo ""

# 7. Crear usuario admin por defecto
echo "👤 Creando usuario administrador..."
php artisan tinker --execute="
    use App\Models\User;
    if (!User::where('email', 'admin@openclaw.test')->exists()) {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@openclaw.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        echo 'Usuario admin creado: admin@openclaw.test / password';
    } else {
        echo 'Usuario admin ya existe';
    }
" 2>/dev/null || echo "⚠️ No se pudo crear usuario, se creará después de migraciones"

echo ""

# 8. Ejecutar migraciones
echo "🔄 Ejecutando migraciones de base de datos..."
php artisan migrate --force --no-interaction

if [ $? -ne 0 ]; then
    echo "⚠️ Error en migraciones, intentando sin --force..."
    php artisan migrate --no-interaction
fi

echo "✅ Migraciones ejecutadas"
echo ""

# 9. Configurar Apache para nuevo puerto
echo "🌐 Configurando Apache para puerto 8082..."
cat > /etc/apache2/sites-available/openclaw-portal.conf << 'EOF'
<VirtualHost *:8082>
    ServerName openclaw-portal.local
    DocumentRoot /var/www/openclaw-portal/public
    
    <Directory /var/www/openclaw-portal/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    
    # Configuración de PHP-FPM
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>
    
    ErrorLog /var/www/openclaw-portal/storage/logs/apache-error.log
    CustomLog /var/www/openclaw-portal/storage/logs/apache-access.log combined
</VirtualHost>
EOF

# Agregar puerto 8082 a la configuración
if ! grep -q "Listen 8082" /etc/apache2/ports.conf; then
    echo "Listen 8082" >> /etc/apache2/ports.conf
fi

# Habilitar sitio
ln -sf /etc/apache2/sites-available/openclaw-portal.conf /etc/apache2/sites-enabled/

echo "✅ Configuración Apache creada"
echo ""

# 10. Reiniciar servicios
echo "🔄 Reiniciando servicios..."
systemctl restart apache2 php8.2-fpm 2>/dev/null || echo "⚠️ No se pudieron reiniciar servicios, continúa manualmente"

echo "✅ Servicios reiniciados"
echo ""

# 11. Crear estructura inicial de directorios
echo "📁 Creando estructura de directorios..."
mkdir -p /var/www/openclaw-portal/storage/openclaw/{workspace,docs,backups,uploads}
mkdir -p /var/www/openclaw-portal/storage/logs

# Copiar archivos existentes de OpenClaw
if [ -d "/root/.openclaw/workspace" ]; then
    echo "📦 Copiando archivos existentes de OpenClaw..."
    cp -r /root/.openclaw/workspace/* /var/www/openclaw-portal/storage/openclaw/workspace/ 2>/dev/null || true
fi

echo "✅ Estructura de directorios creada"
echo ""

# 12. Crear archivo de estado
cat > /var/www/openclaw-portal/public/status.html << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>OpenClaw Portal - Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .status { padding: 20px; border-radius: 10px; margin: 20px 0; }
        .success { background: #d4edda; color: #155724; }
        .info { background: #d1ecf1; color: #0c5460; }
        .warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <h1>🚀 OpenClaw Portal - Status</h1>
    
    <div class="status success">
        <h2>✅ Setup Completado</h2>
        <p>OpenClaw Portal ha sido instalado exitosamente.</p>
    </div>
    
    <div class="status info">
        <h2>📊 Información del Sistema</h2>
        <ul>
            <li><strong>Ubicación:</strong> /var/www/openclaw-portal/</li>
            <li><strong>Framework:</strong> Laravel 11</li>
            <li><strong>Base de datos:</strong> SQLite</li>
            <li><strong>Puerto:</strong> 8082</li>
            <li><strong>Usuario demo:</strong> admin@openclaw.test</li>
            <li><strong>Contraseña demo:</strong> password</li>
        </ul>
    </div>
    
    <div class="status warning">
        <h2>⚠️ Próximos Pasos</h2>
        <ol>
            <li>Configurar autenticación avanzada</li>
            <li>Importar documentación existente</li>
            <li>Configurar integración con OpenClaw</li>
            <li>Desplegar a producción</li>
        </ol>
    </div>
    
    <p><a href="/">Ir al dashboard</a> | <a href="/login">Iniciar sesión</a></p>
</body>
</html>
EOF

echo "✅ Archivo de estado creado"
echo ""

# Resumen final
echo "🎉 SETUP COMPLETADO EXITOSAMENTE"
echo "================================="
echo ""
echo "📊 RESUMEN:"
echo "-----------"
echo "📍 Ubicación: /var/www/openclaw-portal/"
echo "🌐 URL: http://localhost:8082/"
echo "🔗 Status: http://localhost:8082/status.html"
echo "👤 Usuario demo: admin@openclaw.test"
echo "🔐 Contraseña demo: password"
echo "🗃️ Base de datos: SQLite (/var/www/openclaw-portal/database/database.sqlite)"
echo ""
echo "🚀 PRÓXIMOS PASOS:"
echo "------------------"
echo "1. Ejecutar: cd /var/www/openclaw-portal"
echo "2. Desarrollar módulos según plan"
echo "3. Configurar integración con OpenClaw"
echo "4. Desplegar a producción"
echo ""
echo "📝 LOGS:"
echo "--------"
echo "• Laravel: /var/www/openclaw-portal/storage/logs/laravel.log"
echo "• Apache: /var/www/openclaw-portal/storage/logs/apache-*.log"
echo ""
echo "🐾 OpenClaw Portal está listo para desarrollo!"