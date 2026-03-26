#!/bin/bash

echo "🔍 ANÁLISIS PROFUNDO DEL SISTEMA DE CONDOMINIOS"
echo "================================================"
echo "Fecha: $(date)"
echo "Directorio: /home/torreclick/condominio-management"
echo ""

# 1. ANÁLISIS DE ESTRUCTURA
echo "📁 1. ESTRUCTURA DEL PROYECTO"
echo "-----------------------------"

# Tamaño del proyecto
echo "Tamaño total del proyecto:"
du -sh . 2>/dev/null

# Número de archivos por tipo
echo ""
echo "📊 Estadísticas de archivos:"
echo "PHP: $(find . -name "*.php" -type f | wc -l)"
echo "Vue: $(find . -name "*.vue" -type f | wc -l)"
echo "JS: $(find . -name "*.js" -type f | wc -l)"
echo "Blade: $(find . -name "*.blade.php" -type f | wc -l)"
echo "Tests: $(find . -name "*Test.php" -type f | wc -l)"

# 2. ANÁLISIS DE BACKEND
echo ""
echo "⚙️  2. ANÁLISIS DE BACKEND (Laravel)"
echo "-----------------------------------"

# Modelos
echo "📦 Modelos encontrados: $(find app/Models -name "*.php" | wc -l)"
echo "Primeros 10 modelos:"
find app/Models -name "*.php" | head -10 | sed 's|.*/||' | sed 's/\.php//' | xargs -I {} echo "  - {}"

# Controladores
echo ""
echo "🎮 Controladores encontrados: $(find app/Http/Controllers -name "*.php" | wc -l)"
echo "Estructura de controladores:"
find app/Http/Controllers -type d | sed 's|app/Http/Controllers/||' | grep -v "^$" | sort | xargs -I {} echo "  📁 {}"

# Migraciones
echo ""
echo "🗄️  Migraciones: $(find database/migrations -name "*.php" | wc -l)"
echo "Últimas 5 migraciones:"
find database/migrations -name "*.php" | sort -r | head -5 | xargs -I {} basename {} | sed 's/^/  - /'

# 3. ANÁLISIS DE FRONTEND
echo ""
echo "🎨 3. ANÁLISIS DE FRONTEND (Vue 3 + Inertia)"
echo "-------------------------------------------"

# Páginas Vue
echo "📄 Páginas Vue encontradas: $(find resources/js/Pages -name "*.vue" | wc -l)"
echo "Estructura de páginas:"
find resources/js/Pages -type d | sed 's|resources/js/Pages/||' | grep -v "^$" | sort | head -15 | xargs -I {} echo "  📁 {}"

# Componentes
echo ""
echo "🧩 Componentes Vue: $(find resources/js/Components -name "*.vue" 2>/dev/null | wc -l)"
if [ -d "resources/js/Components" ]; then
    echo "Algunos componentes:"
    find resources/js/Components -name "*.vue" | head -10 | sed 's|.*/||' | sed 's/\.vue//' | xargs -I {} echo "  - {}"
fi

# 4. ANÁLISIS DE RUTAS
echo ""
echo "🛣️  4. ANÁLISIS DE RUTAS"
echo "----------------------"

# Rutas definidas
echo "Archivos de rutas:"
ls -la routes/*.php | awk '{print "  - " $9}'

# 5. ANÁLISIS DE BASE DE DATOS
echo ""
echo "🗃️  5. ANÁLISIS DE BASE DE DATOS"
echo "------------------------------"

# Tablas principales (de migraciones)
echo "Tablas identificadas en migraciones:"
grep -h "Schema::create\|Schema::table" database/migrations/*.php 2>/dev/null | \
    sed 's/.*create.*//;s/.*table.*//' | \
    grep -o "'[^']*'" | \
    sed "s/'//g" | \
    sort -u | \
    head -15 | \
    xargs -I {} echo "  - {}"

# 6. ANÁLISIS DE DEPENDENCIAS
echo ""
echo "📦 6. DEPENDENCIAS"
echo "----------------"

# PHP
echo "Dependencias PHP (composer.json):"
if [ -f "composer.json" ]; then
    echo "  Laravel: $(grep -A2 '"laravel/framework"' composer.json | grep '"version"' | sed 's/.*: "//;s/".*//' || echo "No encontrado")"
    echo "  Paquetes requeridos: $(grep -c '"require"' composer.json || echo "0")"
fi

# JavaScript
echo ""
echo "Dependencias JavaScript (package.json):"
if [ -f "package.json" ]; then
    echo "  Vue: $(grep -A2 '"vue"' package.json | grep '"version"' | sed 's/.*: "//;s/".*//' || echo "No encontrado")"
    echo "  Inertia: $(grep -A2 '"@inertiajs/vue3"' package.json | grep '"version"' | sed 's/.*: "//;s/".*//' || echo "No encontrado")"
fi

# 7. ANÁLISIS DE CONFIGURACIÓN
echo ""
echo "⚙️  7. CONFIGURACIÓN"
echo "------------------"

# Archivos de configuración
echo "Archivos de configuración principales:"
ls -la config/*.php 2>/dev/null | head -10 | awk '{print "  - " $9}'

# Variables de entorno críticas
echo ""
echo "Variables de entorno críticas (.env):"
if [ -f ".env" ]; then
    grep -E "^(APP_|DB_|REDIS_|QUEUE_|MAIL_)" .env 2>/dev/null | head -10 | sed 's/^/  /'
fi

# 8. ANÁLISIS DE DOCKER
echo ""
echo "🐳 8. CONFIGURACIÓN DOCKER"
echo "-------------------------"

if [ -f "docker-compose.yml" ]; then
    echo "Servicios Docker configurados:"
    grep -A2 "services:" docker-compose.yml | grep -v "services:" | grep -v "^--" | sed 's/^ *//' | head -10 | sed 's/^/  - /'
    
    echo ""
    echo "Puertos expuestos:"
    grep -B2 -A2 "ports:" docker-compose.yml | grep "- " | sed 's/^ *//' | head -10 | sed 's/^/  - /'
fi

# 9. ANÁLISIS DE TESTS
echo ""
echo "🧪 9. TESTS"
echo "----------"

if [ -d "tests" ]; then
    echo "Tests encontrados: $(find tests -name "*Test.php" | wc -l)"
    echo "Tipos de tests:"
    find tests -type d | sed 's|tests/||' | grep -v "^$" | xargs -I {} echo "  📁 {}"
fi

# 10. ANÁLISIS DE DOCUMENTACIÓN
echo ""
echo "📚 10. DOCUMENTACIÓN"
echo "-------------------"

echo "Archivos de documentación:"
find . -name "*.md" -type f | head -10 | sed 's|^./||' | xargs -I {} echo "  - {}"

echo ""
echo "================================================"
echo "🔍 ANÁLISIS COMPLETADO"
echo "Próximo paso: Revisión de código específico para gaps y bugs"