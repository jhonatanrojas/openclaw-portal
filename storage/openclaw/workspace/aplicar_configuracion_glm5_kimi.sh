#!/bin/bash

echo "🔧 Configurando OpenClaw con GLM-5 y Kimi K2.5"
echo "=============================================="

# 1. Verificar que OpenClaw está corriendo
echo "✅ Verificando estado de OpenClaw..."
openclaw gateway status 2>/dev/null || {
    echo "⚠️  Gateway no está corriendo, iniciando..."
    openclaw gateway start --background
    sleep 3
}

# 2. Configurar variables de entorno (si no están configuradas)
echo "✅ Configurando variables de entorno..."
if [ -z "$ZAI_API_KEY" ]; then
    echo "⚠️  ZAI_API_KEY no está configurada"
    echo "   Obtén tu API key en: https://open.bigmodel.cn/"
    echo "   Luego ejecuta: export ZAI_API_KEY='tu-api-key'"
fi

if [ -z "$MOONSHOT_API_KEY" ]; then
    echo "⚠️  MOONSHOT_API_KEY no está configurada"
    echo "   Obtén tu API key en: https://platform.moonshot.cn/"
    echo "   Luego ejecuta: export MOONSHOT_API_KEY='tu-api-key'"
fi

# 3. Habilitar plugins necesarios
echo "✅ Habilitando plugins..."
openclaw plugins enable zai 2>/dev/null || echo "⚠️  Plugin zai ya habilitado o no disponible"
openclaw plugins enable moonshot 2>/dev/null || echo "⚠️  Plugin moonshot ya habilitado o no disponible"

# 4. Aplicar configuración
echo "✅ Aplicando configuración..."
CONFIG_FILE="/root/.openclaw/openclaw.json"
BACKUP_FILE="/root/.openclaw/openclaw.json.backup.$(date +%Y%m%d_%H%M%S)"

# Hacer backup
cp "$CONFIG_FILE" "$BACKUP_FILE"
echo "   Backup creado: $BACKUP_FILE"

# Aplicar nueva configuración
cp configuracion_glm5_kimi_openclaw.json "$CONFIG_FILE"
echo "   Nueva configuración aplicada"

# 5. Reiniciar gateway para aplicar cambios
echo "✅ Reiniciando gateway..."
openclaw gateway restart

# 6. Verificar configuración
echo "✅ Verificando configuración..."
sleep 2
openclaw models status

echo ""
echo "🎉 CONFIGURACIÓN COMPLETADA"
echo "==========================="
echo ""
echo "📋 Resumen de configuración:"
echo "   • Modelo principal: GLM-5 (zai/glm-5)"
echo "   • Modelo para testing: Kimi K2.5 (moonshot/kimi-k2.5)"
echo "   • Modelo thinking: Kimi K2 Thinking (moonshot/kimi-k2-thinking)"
echo "   • Fallbacks: DeepSeek, Nemotron 70B"
echo ""
echo "🔧 Para usar modelos específicos:"
echo "   • Desarrollo normal: Usa GLM-5 automáticamente"
echo "   • Testing/agentes: Especifica 'kimi' en tu prompt"
echo "   • Reasoning complejo: Usa 'kimi-thinking'"
echo ""
echo "💡 Ejemplos de uso:"
echo "   • 'Desarrolla un modelo Laravel para recibos' → Usará GLM-5"
echo "   • 'kimi: Ejecuta tests en paralelo para este código' → Usará Kimi K2.5"
echo "   • 'kimi-thinking: Analiza este error complejo' → Usará Kimi K2 Thinking"
echo ""
echo "⚠️  RECUERDA: Necesitas API keys válidas para GLM-5 y Kimi K2.5"
echo "   • GLM-5: https://open.bigmodel.cn/"
echo "   • Kimi K2.5: https://platform.moonshot.cn/"