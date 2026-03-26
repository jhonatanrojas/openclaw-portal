#!/bin/bash
# openclaw-portal-task-runner.sh
# Ejecutor de tareas secuenciales para OpenClaw Portal

TASK_PLAN="/root/.openclaw/workspace/openclaw-portal-project-plan.md"
LOG_DIR="/root/.openclaw/workspace/logs"
PROJECT_DIR="/var/www/openclaw-portal"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Crear directorio de logs
mkdir -p "$LOG_DIR"

# Función para loguear
log() {
    local level=$1
    local message=$2
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    
    case $level in
        "INFO") color=$BLUE ;;
        "SUCCESS") color=$GREEN ;;
        "WARNING") color=$YELLOW ;;
        "ERROR") color=$RED ;;
        *) color=$NC ;;
    esac
    
    echo -e "${color}[$timestamp] [$level] $message${NC}"
    echo "[$timestamp] [$level] $message" >> "$LOG_DIR/openclaw-portal-$(date '+%Y-%m-%d').log"
}

# Función para extraer tareas del plan
extract_tasks() {
    local phase=$1
    log "INFO" "Extrayendo tareas de la fase: $phase"
    
    case $phase in
        "1")
            # Tareas de la Fase 1 (1-15)
            seq 1 15 | while read num; do
                printf "TASK-%03d\n" $num
            done
            ;;
        "2")
            # Tareas de la Fase 2 (16-35)
            seq 16 35 | while read num; do
                printf "TASK-%03d\n" $num
            done
            ;;
        "3")
            # Tareas de la Fase 3 (36-60)
            seq 36 60 | while read num; do
                printf "TASK-%03d\n" $num
            done
            ;;
        *)
            # Todas las tareas
            seq 1 60 | while read num; do
                printf "TASK-%03d\n" $num
            done
            ;;
    esac
}

# Función para ejecutar una tarea específica
execute_task() {
    local task=$1
    log "INFO" "Ejecutando tarea: $task"
    
    case $task in
        "TASK-001")
            log "INFO" "Instalando Laravel 11..."
            cd /var/www
            if [ -d "openclaw-portal" ]; then
                log "WARNING" "El directorio openclaw-portal ya existe, omitiendo..."
                return 0
            fi
            composer create-project laravel/laravel openclaw-portal --prefer-dist --no-interaction
            if [ $? -eq 0 ]; then
                log "SUCCESS" "Laravel 11 instalado"
                return 0
            else
                log "ERROR" "Error al instalar Laravel"
                return 1
            fi
            ;;
        
        "TASK-002")
            log "INFO" "Configurando base de datos SQLite..."
            cd "$PROJECT_DIR"
            touch database/database.sqlite
            if grep -q "DB_CONNECTION=mysql" .env; then
                sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
                sed -i 's/DB_DATABASE=laravel/# DB_DATABASE=laravel/' .env
                echo 'DB_DATABASE=/var/www/openclaw-portal/database/database.sqlite' >> .env
            fi
            log "SUCCESS" "Base de datos SQLite configurada"
            return 0
            ;;
        
        "TASK-003")
            log "INFO" "Instalando sistema de autenticación Breeze..."
            cd "$PROJECT_DIR"
            composer require laravel/breeze --dev --no-interaction
            if [ $? -eq 0 ]; then
                php artisan breeze:install blade --no-interaction
                npm install --silent
                npm run build --silent
                log "SUCCESS" "Autenticación Breeze instalada"
                return 0
            else
                log "ERROR" "Error al instalar Breeze"
                return 1
            fi
            ;;
        
        "TASK-004")
            log "INFO" "Integrando con autenticación existente..."
            # Copiar y adaptar auth.php existente
            if [ -f "/var/www/openclaw.deploymatrix.com/public_html/auth.php" ]; then
                mkdir -p "$PROJECT_DIR/app/Services"
                cp "/var/www/openclaw.deploymatrix.com/public_html/auth.php" "$PROJECT_DIR/app/Services/AuthService.php"
                log "SUCCESS" "AuthService creado a partir de sistema existente"
            else
                log "WARNING" "Archivo auth.php no encontrado, creando nuevo"
                cat > "$PROJECT_DIR/app/Services/AuthService.php" << 'EOF'
<?php
namespace App\Services;

class AuthService
{
    // Servicio de autenticación para OpenClaw Portal
    // Integrar con sistema existente más adelante
}
EOF
            fi
            return 0
            ;;
        
        "TASK-005")
            log "INFO" "Configurando rutas básicas..."
            cd "$PROJECT_DIR"
            cat > routes/web.php << 'EOF'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ConfigController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('docs', DocumentationController::class);
    Route::resource('config', ConfigController::class);
});

require __DIR__.'/auth.php';
EOF
            log "SUCCESS" "Rutas básicas configuradas"
            return 0
            ;;
        
        # Puedes agregar más tareas aquí según sea necesario
        
        *)
            log "WARNING" "Tarea $task no implementada aún"
            return 2
            ;;
    esac
}

# Función para mostrar estado
show_status() {
    log "INFO" "Estado del proyecto OpenClaw Portal"
    echo ""
    echo "📊 ESTADO ACTUAL:"
    echo "-----------------"
    
    if [ -d "$PROJECT_DIR" ]; then
        echo "✅ Proyecto Laravel instalado en: $PROJECT_DIR"
        
        # Verificar componentes clave
        components=(
            ".env"
            "database/database.sqlite"
            "routes/web.php"
            "app/Http/Controllers"
            "resources/views"
        )
        
        for comp in "${components[@]}"; do
            if [ -e "$PROJECT_DIR/$comp" ]; then
                echo "  ✅ $comp"
            else
                echo "  ❌ $comp (faltante)"
            fi
        done
        
        # Verificar servicios
        echo ""
        echo "🔧 SERVICIOS:"
        if systemctl is-active --quiet apache2; then
            echo "  ✅ Apache2 activo"
        else
            echo "  ❌ Apache2 inactivo"
        fi
        
        if systemctl is-active --quiet php8.2-fpm; then
            echo "  ✅ PHP8.2-FPM activo"
        else
            echo "  ❌ PHP8.2-FPM inactivo"
        fi
        
        # Verificar acceso web
        echo ""
        echo "🌐 ACCESO WEB:"
        echo "  URL: http://localhost:8082/"
        echo "  Status: http://localhost:8082/status.html"
        
    else
        echo "❌ Proyecto no instalado. Ejecuta: ./setup-openclaw-portal.sh"
    fi
    
    echo ""
    echo "📋 TAREAS COMPLETADAS:"
    echo "----------------------"
    # Aquí podrías leer de un archivo de estado qué tareas están completadas
    echo "  (Estado de tareas no implementado aún)"
}

# Función para mostrar ayuda
show_help() {
    echo "OpenClaw Portal Task Runner"
    echo "Uso: $0 [comando] [opciones]"
    echo ""
    echo "Comandos:"
    echo "  setup           Ejecutar setup inicial completo"
    echo "  phase <num>     Ejecutar todas las tareas de una fase (1, 2, 3)"
    echo "  task <nombre>   Ejecutar una tarea específica (ej: TASK-001)"
    echo "  status          Mostrar estado actual del proyecto"
    echo "  help            Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  $0 setup                    # Setup completo inicial"
    echo "  $0 phase 1                  # Ejecutar Fase 1 completa"
    echo "  $0 task TASK-001            # Ejecutar solo TASK-001"
    echo "  $0 status                   # Ver estado del proyecto"
}

# Main
case $1 in
    "setup")
        log "INFO" "Iniciando setup completo de OpenClaw Portal..."
        # Ejecutar script de setup
        if [ -f "/root/.openclaw/workspace/scripts/setup-openclaw-portal.sh" ]; then
            bash "/root/.openclaw/workspace/scripts/setup-openclaw-portal.sh"
        else
            log "ERROR" "Script de setup no encontrado"
            exit 1
        fi
        ;;
    
    "phase")
        if [ -z "$2" ]; then
            log "ERROR" "Debes especificar el número de fase (1, 2, 3)"
            exit 1
        fi
        
        phase=$2
        log "INFO" "Ejecutando Fase $phase..."
        
        tasks=$(extract_tasks "$phase")
        completed=0
        failed=0
        skipped=0
        
        for task in $tasks; do
            execute_task "$task"
            result=$?
            
            case $result in
                0) ((completed++)) ;;
                1) ((failed++)) ;;
                2) ((skipped++)) ;;
            esac
        done
        
        log "INFO" "Fase $phase completada: $completed exitosas, $failed fallidas, $skipped omitidas"
        ;;
    
    "task")
        if [ -z "$2" ]; then
            log "ERROR" "Debes especificar el nombre de la tarea"
            exit 1
        fi
        
        task=$2
        execute_task "$task"
        ;;
    
    "status")
        show_status
        ;;
    
    "help"|"--help"|"-h")
        show_help
        ;;
    
    *)
        log "ERROR" "Comando no reconocido: $1"
        show_help
        exit 1
        ;;
esac