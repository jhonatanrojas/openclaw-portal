# Configurar GLM5 a través de la API de NVIDIA

## ¿Qué es GLM5?

GLM-5 es el modelo de quinta generación de Zhipu AI (Z.ai), disponible a través de la API NVIDIA NIM (NVIDIA Inference Microservices). Es un modelo de lenguaje grande de 744B parámetros (40B activos) con arquitectura Mixture of Experts (MoE) y DeepSeek Sparse Attention (DSA).

**Características principales:**
- 744B parámetros totales, 40B activos (MoE)
- Capacidad de contexto: 205K tokens
- Entrenado con 28.5T tokens
- Optimizado para tareas complejas de ingeniería de sistemas y agentes de largo horizonte
- Soporta llamadas a herramientas, razonamiento, codificación y operaciones de terminal

## Pasos para configurar GLM5 con la API de NVIDIA

### 1. Obtener credenciales de API de NVIDIA

1. Visita: https://build.nvidia.com/
2. Regístrate o inicia sesión en NVIDIA Developer
3. Navega a la sección de APIs
4. Busca "GLM-5" o "z-ai/glm5"
5. Solicita acceso a la API (puede ser gratuita para pruebas)

### 2. Configurar la autenticación

Necesitarás un **API Key** de NVIDIA. Una vez obtenido:

```bash
export NVIDIA_API_KEY="tu-api-key-aqui"
```

### 3. Endpoint de la API

**URL base:** `https://integrate.api.nvidia.com/v1/chat/completions`

**Modelo específico:** `z-ai/glm5`

### 4. Ejemplo de uso con cURL

```bash
curl -X POST https://integrate.api.nvidia.com/v1/chat/completions \
  -H "Authorization: Bearer $NVIDIA_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "z-ai/glm5",
    "messages": [
      {"role": "system", "content": "Eres un asistente útil."},
      {"role": "user", "content": "Hola, ¿cómo estás?"}
    ],
    "temperature": 0.7,
    "max_tokens": 1000
  }'
```

### 5. Ejemplo en Python

```python
import requests
import os

# Configurar API key
api_key = os.getenv("NVIDIA_API_KEY")
url = "https://integrate.api.nvidia.com/v1/chat/completions"

headers = {
    "Authorization": f"Bearer {api_key}",
    "Content-Type": "application/json"
}

payload = {
    "model": "z-ai/glm5",
    "messages": [
        {"role": "system", "content": "Eres un asistente experto en programación."},
        {"role": "user", "content": "Explica qué es GLM5 en términos simples."}
    ],
    "temperature": 0.7,
    "max_tokens": 500
}

response = requests.post(url, headers=headers, json=payload)

if response.status_code == 200:
    result = response.json()
    print(result["choices"][0]["message"]["content"])
else:
    print(f"Error: {response.status_code}")
    print(response.text)
```

### 6. Parámetros importantes

- **model**: `"z-ai/glm5"` (siempre necesario)
- **messages**: Array de mensajes con roles (system, user, assistant)
- **temperature**: Controla la aleatoriedad (0.0-2.0)
- **max_tokens**: Máximo de tokens en la respuesta
- **top_p**: Muestreo de núcleo (0.0-1.0)
- **stream**: `true`/`false` para respuestas en streaming

### 7. Características especiales de GLM5

GLM5 soporta características avanzadas:

1. **Modo de razonamiento (thinking)**: Puede mostrar su proceso de pensamiento
2. **Llamadas a herramientas**: Soporta function calling
3. **Contexto largo**: Hasta 205K tokens
4. **Respuestas estructuradas**: Puede devolver JSON

Ejemplo con modo de razonamiento:
```json
{
  "model": "z-ai/glm5",
  "messages": [
    {"role": "user", "content": "Resuelve este problema matemático paso a paso: 15 * (3 + 7) / 5"}
  ],
  "thinking": true
}
```

### 8. Consideraciones de costo y límites

- **Costo**: Consulta la página de precios de NVIDIA NIM
- **Rate limits**: Dependen de tu plan
- **Disponibilidad**: Global (todos los servidores de NVIDIA)
- **Licencia**: MIT License + NVIDIA Open Model License

### 9. Alternativas en la misma API

Si GLM5 no está disponible, considera:
- `z-ai/glm4.7` (versión anterior)
- `meta/llama-3.3-70b-instruct`
- `deepseek-ai/deepseek-v3.2`

### 10. Solución de problemas

**Error común: "Model not found"**
- Verifica que el modelo sea exactamente `"z-ai/glm5"`
- Confirma que tienes acceso a este modelo específico

**Error de autenticación:**
- Verifica que tu API key sea correcta
- Asegúrate de incluir `Bearer` en el header

**Error de rate limit:**
- Implementa retry con exponential backoff
- Considera actualizar tu plan

## Recursos adicionales

1. **Documentación oficial**: https://docs.api.nvidia.com/nim/reference/z-ai-glm5
2. **Model card**: https://build.nvidia.com/z-ai/glm5/modelcard
3. **Blog técnico**: https://z.ai/blog/glm-5
4. **Hugging Face**: https://huggingface.co/zai-org/GLM-5
5. **Ejemplos de código**: https://github.com/NVIDIA/GenerativeAIExamples

## Notas importantes

- GLM5 es un modelo de terceros (Z.ai) disponible a través de NVIDIA
- Requiere hardware NVIDIA para ejecución local (si descargas el contenedor)
- Para uso en la nube, solo necesitas la API key
- Compatible con la especificación OpenAI API