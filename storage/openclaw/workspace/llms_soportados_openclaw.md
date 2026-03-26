# 🤖 LLMs Soportados por OpenClaw (2026)

## 📊 **RESUMEN GENERAL**

OpenClaw soporta **más de 30 proveedores de LLMs** diferentes, con cientos de modelos disponibles. El sistema es altamente extensible y soporta tanto proveedores cloud como modelos locales.

## 🏆 **PRINCIPALES CATEGORÍAS DE LLMs SOPORTADOS**

### **1. 🏢 Proveedores Cloud Principales**
- **OpenAI** (`openai/`): GPT-5.4, GPT-5.4-Pro, etc.
- **Anthropic** (`anthropic/`): Claude Opus 4.6, Sonnet, Haiku
- **Google Gemini** (`google/`): Gemini 3.1 Pro, Gemini 3 Flash
- **DeepSeek** (`deepseek/`): DeepSeek Chat, DeepSeek Coder
- **Z.AI/GLM** (`zai/`): GLM-5, GLM-4.7
- **Mistral AI** (`mistral/`): Mistral Large, Mixtral
- **Moonshot/Kimi** (`moonshot/`, `kimi-coding/`): Kimi K2.5

### **2. 🌐 Gateways y Agregadores**
- **OpenRouter** (`openrouter/`): Acceso a múltiples proveedores
- **Kilo Gateway** (`kilocode/`): GLM-5 Free, MiniMax M2.5 Free, etc.
- **Vercel AI Gateway** (`vercel-ai-gateway/`)
- **Together AI** (`together/`)
- **Synthetic** (`synthetic/`)

### **3. 🇨🇳 Proveedores Chinos**
- **Volcano Engine** (`volcengine/`): Doubao, Kimi, GLM
- **BytePlus** (`byteplus/`): Versión internacional de Volcano
- **Qianfan** (`qianfan/`): Baidu
- **MiniMax** (`minimax/`): MiniMax M2.5
- **Xiaomi** (`xiaomi/`)

### **4. 🖥️ Modelos Locales/Self-hosted**
- **Ollama** (`ollama/`): Llama 3.3, CodeLlama, etc.
- **vLLM** (`vllm/`): Servidores OpenAI-compatibles
- **SGLang** (`sglang/`): Servidores de alta velocidad
- **LM Studio** (custom providers)
- **LiteLLM** (custom providers)

### **5. 🛠️ Proveedores Especializados**
- **GitHub Copilot** (`github-copilot/`)
- **OpenAI Codex** (`openai-codex/`)
- **OpenCode** (`opencode/`, `opencode-go/`)
- **NVIDIA** (`nvidia/`): Nemotron, etc.
- **Hugging Face** (`huggingface/`)
- **Cloudflare AI Gateway** (`cloudflare-ai-gateway/`)

## 📋 **LISTA COMPLETA DE PROVEEDORES SOPORTADOS**

### **Proveedores Integrados (pi-ai catalog):**
1. `openai` - OpenAI API
2. `anthropic` - Anthropic Claude
3. `openai-codex` - OpenAI Codex (OAuth)
4. `opencode` / `opencode-go` - OpenCode
5. `google` - Google Gemini API
6. `google-vertex` - Google Vertex AI
7. `google-gemini-cli` - Gemini CLI (OAuth)
8. `zai` - Z.AI GLM models
9. `vercel-ai-gateway` - Vercel AI Gateway
10. `kilocode` - Kilo Gateway
11. `openrouter` - OpenRouter
12. `minimax` - MiniMax
13. `moonshot` - Moonshot AI (Kimi)
14. `kimi-coding` - Kimi Coding
15. `qianfan` - Qianfan (Baidu)
16. `modelstudio` - Model Studio
17. `nvidia` - NVIDIA models
18. `together` - Together AI
19. `venice` - Venice
20. `xiaomi` - Xiaomi
21. `huggingface` - Hugging Face Inference
22. `cloudflare-ai-gateway` - Cloudflare AI Gateway
23. `volcengine` - Volcano Engine
24. `byteplus` - BytePlus
25. `xai` - xAI (Grok)
26. `mistral` - Mistral AI
27. `groq` - Groq
28. `cerebras` - Cerebras
29. `github-copilot` - GitHub Copilot
30. `ollama` - Ollama (local)
31. `vllm` - vLLM (local)
32. `sglang` - SGLang (local)
33. `qwen-portal` - Qwen OAuth (free tier)

### **Proveedores Custom (via `models.providers`):**
- Cualquier endpoint OpenAI-compatible
- Cualquier endpoint Anthropic-compatible
- Servidores locales personalizados

## 🔧 **CONFIGURACIÓN TÍPICA**

### **Configuración básica en `openclaw.json`:**
```json5
{
  agents: {
    defaults: {
      model: {
        primary: "deepseek/deepseek-chat",  // Modelo por defecto
        fallbacks: [
          "anthropic/claude-sonnet-4-5",
          "openai/gpt-5.4"
        ]
      }
    }
  },
  models: {
    mode: "merge",  // Fusionar con proveedores integrados
    providers: {
      // Proveedores custom adicionales
      my-local-llm: {
        baseUrl: "http://localhost:8000/v1",
        apiKey: "${MY_LOCAL_KEY}",
        api: "openai-completions",
        models: [
          {
            id: "my-model",
            name: "Mi Modelo Local",
            contextWindow: 128000
          }
        ]
      }
    }
  }
}
```

### **Variables de entorno comunes:**
```bash
# OpenAI
OPENAI_API_KEY="sk-..."

# Anthropic  
ANTHROPIC_API_KEY="sk-ant-..."

# DeepSeek
DEEPSEEK_API_KEY="sk-d7ad4..."

# Google Gemini
GEMINI_API_KEY="AIza..."

# NVIDIA
NVIDIA_API_KEY="nvapi-..."

# OpenRouter
OPENROUTER_API_KEY="sk-or-..."
```

## 🎯 **MODELOS POPULARES POR CASO DE USO**

### **Para programación/codificación:**
1. `deepseek/deepseek-chat` - Buen equilibrio calidad/costo
2. `openai-codex/gpt-5.4` - Excelente para código (requiere OAuth)
3. `zai/glm-5` - GLM-5 para coding agentico
4. `kilocode/anthropic/claude-opus-4.6` - Claude via Kilo
5. `ollama/codellama` - Local, gratuito

### **Para razonamiento general:**
1. `anthropic/claude-opus-4.6` - Mejor razonamiento
2. `openai/gpt-5.4-pro` - GPT más avanzado
3. `google/gemini-3.1-pro-preview` - Gemini Pro

### **Para velocidad/bajo costo:**
1. `google/gemini-3-flash-preview` - Muy rápido
2. `deepseek/deepseek-chat` - Económico
3. `openrouter/anthropic/claude-haiku-3` - Haiku via OpenRouter

### **Para uso local/gratuito:**
1. `ollama/llama3.3` - Llama 3.3 local
2. `ollama/codellama` - CodeLlama local
3. `vllm/mi-modelo-local` - Custom local server

## 📈 **ESTADÍSTICAS DE SOPORTE**

### **Número total de proveedores:**
- **Integrados:** ~33 proveedores principales
- **Custom:** Ilimitados (cualquier endpoint compatible)
- **Modelos individuales:** Cientos (varía por proveedor)

### **Tipos de autenticación soportados:**
1. **API Keys** (la mayoría)
2. **OAuth** (OpenAI Codex, Google Gemini CLI, Qwen Portal)
3. **Setup Tokens** (Anthropic)
4. **Sin autenticación** (local servers)
5. **gcloud ADC** (Google Vertex)

### **Protocolos/APIs soportados:**
1. **OpenAI Completions API** (compatible)
2. **Anthropic Messages API** (compatible)  
3. **Google Gemini API** (nativo)
4. **Custom endpoints** (via configuración)

## 🚀 **CÓMO AGREGAR NUEVOS LLMs**

### **1. Proveedores integrados:**
```bash
# Habilitar plugin de proveedor
openclaw plugins enable nvidia

# Configurar autenticación
openclaw onboard --auth-choice nvidia-api-key

# Establecer como modelo por defecto
openclaw models set nvidia/nemotron-3-super
```

### **2. Proveedores custom:**
```json5
{
  models: {
    providers: {
      "mi-proveedor": {
        baseUrl: "https://api.mi-proveedor.com/v1",
        apiKey: "${MI_API_KEY}",
        api: "openai-completions",
        models: [
          {
            id: "mi-modelo",
            name: "Mi Modelo Personalizado",
            contextWindow: 128000,
            maxTokens: 4096
          }
        ]
      }
    }
  }
}
```

### **3. Modelos locales:**
```bash
# Con Ollama
ollama pull llama3.3
openclaw models set ollama/llama3.3

# Con vLLM/SGLang
export VLLM_API_KEY="vllm-local"
openclaw models set vllm/mi-modelo-local
```

## ⚡ **CARACTERÍSTICAS AVANZADAS**

### **Failover automático:**
```json5
{
  agents: {
    defaults: {
      model: {
        primary: "openai/gpt-5.4",
        fallbacks: [
          "anthropic/claude-sonnet-4-5",
          "deepseek/deepseek-chat",
          "google/gemini-3-flash-preview"
        ]
      }
    }
  }
}
```

### **Rotación de API Keys:**
```bash
# Múltiples keys para rate limiting
OPENAI_API_KEYS="sk-key1,sk-key2,sk-key3"
ANTHROPIC_API_KEYS="sk-ant-key1,sk-ant-key2"
```

### **Thinking/Reasoning:**
- Soporte para modelos con capacidad de "pensamiento"
- Configurable por modelo
- Algunos modelos soportan `xhigh` thinking

### **Cache de prompts:**
- Algunos modelos soportan TTL de cache
- Reduce costes y latencia
- Configurable por proveedor

## 📊 **LIMITACIONES Y CONSIDERACIONES**

### **Límites prácticos:**
- **Rate limits** por proveedor
- **Costos** variables por modelo
- **Disponibilidad** regional
- **Requisitos hardware** para modelos locales

### **Requisitos por tipo:**
- **Cloud:** Solo API key + internet
- **Local:** GPU potente (para modelos grandes)
- **OAuth:** Cuenta en el servicio
- **Enterprise:** Licencias especiales

### **Compatibilidad de herramientas:**
- No todos los modelos soportan todas las herramientas
- Algunos proveedores tienen limitaciones específicas
- Verificar documentación por proveedor

## 🎉 **CONCLUSIÓN**

**OpenClaw soporta una amplia variedad de LLMs:**

### **✅ Ventajas:**
- **+30 proveedores** integrados
- **Cientos de modelos** disponibles
- **Extensible** con proveedores custom
- **Soporte local** (Ollama, vLLM, etc.)
- **Failover automático** entre modelos
- **Rotación de API keys** para rate limiting

### **🎯 Para la mayoría de usuarios:**
1. **Comienza con DeepSeek** (buen equilibrio)
2. **Agrega Claude/GPT** para tareas críticas
3. **Usa modelos locales** para privacidad
4. **Configura fallbacks** para resiliencia

### **🔮 Tendencias 2026:**
- Más proveedores **open-source**
- Mejor soporte para **modelos locales**
- **Multimodalidad** integrada
- **Agentes autónomos** con tool calling

**OpenClaw es una plataforma altamente flexible para trabajar con LLMs, soportando desde los mayores modelos comerciales hasta soluciones locales personalizadas.**

*Documentación generada por Claw 🐾 - Familiar Digital de rangerdev*