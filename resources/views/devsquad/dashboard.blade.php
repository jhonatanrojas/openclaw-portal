<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dev Squad — OpenClaw Portal</title>
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f4f0;
            --border: #e8e6df;
            --text-primary: #1a1916;
            --text-secondary: #5f5e5a;
            --text-tertiary: #888780;
            --radius-lg: 10px;
            --radius-md: 6px;
            --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               background: #f0ede6; color: var(--text-primary); min-height: 100vh; }
        .wrap { padding: 1.5rem 1rem; max-width: 920px; margin: 0 auto; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h2 { font-size: 20px; font-weight: 600; }
        .header .sub { font-size: 13px; color: var(--text-secondary); margin-top: 2px; }
        .conn-row { display: flex; align-items: center; gap: 8px; }
        .conn-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }

        .agents-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 20px; }
        .agent-card { background: var(--bg-primary); border: 0.5px solid var(--border);
            border-radius: var(--radius-lg); padding: 1rem 1.25rem; }
        .agent-model { font-family: var(--font-mono); font-size: 11px;
            background: var(--bg-secondary); padding: 1px 6px; border-radius: 4px;
            display: inline-block; margin: 6px 0; }
        .agent-task { font-size: 12px; color: var(--text-secondary);
            background: var(--bg-secondary); padding: 6px 8px; border-radius: 6px; margin-top: 6px; }
        .agent-seen { font-size: 11px; color: var(--text-tertiary); margin-top: 8px; }
        .agent-logs { margin-top: 10px; font-size: 11px; color: var(--text-secondary); }
        .agent-logs-title { font-size: 11px; color: var(--text-tertiary); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
        .agent-log-item { display: flex; gap: 8px; margin-bottom: 4px; }
        .agent-log-time { color: var(--text-tertiary); flex-shrink: 0; }

        .badge { font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

        .project-bar { background: var(--bg-primary); border: 0.5px solid var(--border);
            border-radius: var(--radius-lg); padding: 1rem 1.25rem; margin-bottom: 16px; }
        .progress-track { height: 6px; background: var(--bg-secondary);
            border-radius: 6px; overflow: hidden; margin: 6px 0 12px; }
        .progress-fill { height: 100%; border-radius: 6px; transition: width .6s ease; }
        .stat-chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .chip { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }

        .phases { display: flex; gap: 8px; margin: 8px 0; }
        .phase-item { flex: 1; }
        .phase-bar { height: 4px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; }
        .phase-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }

        .tabs { display: flex; border-bottom: 0.5px solid var(--border); margin-bottom: 16px; }
        .tab-btn { background: none; border: none; cursor: pointer; padding: 8px 16px;
            font-size: 13px; color: var(--text-secondary);
            border-bottom: 2px solid transparent; margin-bottom: -1px; }
        .tab-btn.active { font-weight: 500; color: var(--text-primary); border-bottom-color: var(--text-primary); }

        .panel { background: var(--bg-primary); border: 0.5px solid var(--border);
            border-radius: var(--radius-lg); padding: .75rem 1.25rem; }
        .task-row { padding: 8px 0; border-bottom: 0.5px solid var(--border); font-size: 13px; }
        .task-row:last-child { border-bottom: none; }
        .task-meta { display: flex; align-items: center; gap: 10px; }
        .task-id { font-family: var(--font-mono); font-size: 11px; color: var(--text-secondary); min-width: 52px; }
        .task-title { flex: 1; }
        .task-skills { margin-left: 62px; margin-top: 4px; font-size: 11px; color: var(--text-tertiary); }

        .log-feed { height: 200px; overflow-y: auto; font-family: var(--font-mono);
            font-size: 11px; line-height: 1.7; background: var(--bg-secondary);
            border-radius: var(--radius-md); padding: 8px 12px; }

        .blocker { background: #fcebeb; color: #791f1f; font-size: 12px;
            padding: 6px 10px; border-radius: 6px; margin-bottom: 6px; }

        .start-form { background: var(--bg-primary); border: 0.5px solid var(--border);
            border-radius: var(--radius-lg); padding: 1rem 1.25rem; margin-top: 20px; }
        .start-form h3 { font-size: 14px; font-weight: 500; margin-bottom: 12px; }
        .form-row { display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap; }
        .form-row input, .form-row textarea {
            flex: 1; min-width: 180px; padding: 7px 10px; font-size: 13px;
            border: 0.5px solid var(--border); border-radius: 6px;
            background: var(--bg-secondary); color: var(--text-primary); font-family: inherit; }
        .form-row textarea { min-height: 72px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 6px; font-size: 13px;
            color: var(--text-secondary); margin-bottom: 10px; }
        .btn-start { background: #1a1916; color: #fff; border: none; cursor: pointer;
            padding: 8px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; }
        .btn-start:disabled { opacity: .5; cursor: not-allowed; }
        .file-browser { display: grid; grid-template-columns: minmax(0, 1fr) 1.1fr; gap: 12px; }
        .file-groups { display: flex; flex-direction: column; gap: 12px; }
.file-group { border: 0.5px solid var(--border); border-radius: var(--radius-lg); background: var(--bg-primary); overflow: hidden; }
.file-group-head { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border-bottom: 0.5px solid var(--border); background: var(--bg-secondary); }
.file-group-title { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: var(--text-secondary); }
.file-group-subtitle { font-size: 11px; color: var(--text-tertiary); margin-top: 3px; }
.file-group-count { font-size: 11px; color: var(--text-tertiary); }
.file-list { max-height: 380px; overflow: auto; }
.file-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; padding: 10px 12px; border-bottom: 0.5px solid var(--border); }
.file-row:last-child { border-bottom: none; }
.file-row.active { background: #f4f1ff; }
        .file-main { min-width: 0; }
        .file-name { font-size: 13px; font-weight: 500; margin-bottom: 3px; word-break: break-word; }
        .file-path { font-size: 11px; color: var(--text-tertiary); word-break: break-all; font-family: var(--font-mono); }
        .file-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
        .file-tag { font-size: 10px; padding: 2px 6px; border-radius: 999px; background: var(--bg-secondary); color: var(--text-secondary); }
        .file-actions { display: flex; gap: 6px; flex-shrink: 0; }
.file-button { border: 0.5px solid var(--border); background: var(--bg-primary); color: var(--text-secondary); border-radius: 6px; font-size: 11px; padding: 5px 8px; cursor: pointer; }
.file-button:hover { color: var(--text-primary); }
.project-files { display: grid; gap: 12px; padding: 12px; }
.project-root { border: 0.5px solid var(--border); border-radius: var(--radius-md); overflow: hidden; background: var(--bg-primary); }
.project-root-head { padding: 10px 12px; border-bottom: 0.5px solid var(--border); background: rgba(245,244,240,.7); }
.project-root-title { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: var(--text-secondary); }
.project-root-path { font-size: 11px; font-family: var(--font-mono); color: var(--text-tertiary); word-break: break-all; margin-top: 4px; }
.project-root-count { font-size: 11px; color: var(--text-tertiary); margin-top: 6px; }
.files-toolbar { display: flex; gap: 10px; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; }
.files-toolbar label { font-size: 12px; color: var(--text-secondary); display: flex; gap: 8px; align-items: center; font-weight: 500; }
.files-toolbar select { border: 0.5px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); padding: 6px 10px; font: inherit; font-size: 12px; }
.files-toolbar .hint { font-size: 12px; color: var(--text-tertiary); }
.models-panel { margin-bottom: 16px; }
.models-grid { display: grid; gap: 10px; margin-top: 12px; }
.model-row { display: grid; gap: 8px; padding: 12px; border: 0.5px solid var(--border); border-radius: var(--radius-md); background: var(--bg-secondary); }
.model-row-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.model-row-title { font-size: 13px; font-weight: 600; }
.model-row-meta { font-size: 11px; color: var(--text-tertiary); }
.model-select { width: 100%; border: 0.5px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); padding: 8px 10px; font: inherit; font-size: 12px; }
.models-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
.models-status { font-size: 12px; color: var(--text-tertiary); }
        .file-preview { border: 0.5px solid var(--border); border-radius: var(--radius-lg); background: #161512; color: #f2efe6; overflow: hidden; display: flex; flex-direction: column; min-height: 420px; }
        .file-preview-head { display: flex; justify-content: space-between; gap: 10px; align-items: center; padding: 10px 12px; border-bottom: 0.5px solid rgba(255,255,255,.08); background: rgba(255,255,255,.03); }
        .file-preview-title { font-size: 13px; font-weight: 600; word-break: break-word; }
        .file-preview-meta { font-size: 11px; color: rgba(242,239,230,.72); margin-top: 2px; }
        .file-preview-body { flex: 1; overflow: auto; padding: 12px; }
        .file-preview-body pre { margin: 0; white-space: pre-wrap; word-break: break-word; font-family: var(--font-mono); font-size: 12px; line-height: 1.65; }
        .file-preview-empty { color: rgba(242,239,230,.6); font-size: 13px; padding: 8px 0; }
        .empty { color: var(--text-tertiary); font-size: 13px; padding: 12px 0; }

        .section-label { font-size: 11px; color: var(--text-tertiary); margin-bottom: 6px; }

        .projects-panel { margin-bottom: 16px; }
        .project-item { display: flex; align-items: center; justify-content: space-between;
            padding: 10px 0; border-bottom: 0.5px solid var(--border); font-size: 13px; }
        .project-item:last-child { border-bottom: none; }
        .project-meta { display: flex; flex-direction: column; gap: 4px; }
        .project-title { font-weight: 500; font-size: 14px; }
        .project-actions { display: flex; gap: 8px; }
        .btn-outline { background: transparent; border: 0.5px solid var(--border); color: var(--text-secondary);
            cursor: pointer; padding: 6px 10px; border-radius: 6px; font-size: 12px; }
        .btn-danger { background: #E24B4A; color: #fff; border: none; cursor: pointer;
            padding: 6px 10px; border-radius: 6px; font-size: 12px; }

        @media (max-width: 600px) { .agents-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="wrap">

    <div class="header">
        <div>
            <h2>🤖 Dev Squad</h2>
            <div class="sub">Equipo multiagente de programación — OpenClaw Portal</div>
        </div>
        <div class="conn-row">
            <span class="conn-dot" id="conn-dot" style="background:#e24b4a"></span>
            <span style="font-size:12px;color:var(--text-secondary)" id="conn-label">Conectando…</span>
        </div>
    </div>

    <!-- Agentes -->
    <div class="agents-grid" id="agents-grid"></div>

    <!-- Barra de proyecto -->
    <div id="project-bar" style="display:none" class="project-bar">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
            <div>
                <div style="font-weight:500;font-size:14px" id="proj-name"></div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:2px" id="proj-desc"></div>
                <div id="proj-meta" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px"></div>
            </div>
            <span id="proj-status-badge"></span>
        </div>
        <div id="proj-progress-wrap" style="display:none">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span style="font-size:12px;color:var(--text-secondary)">Progreso general</span>
                <span style="font-size:13px;font-weight:500" id="proj-pct">0%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="proj-fill" style="width:0%;background:#7f77dd"></div>
            </div>
            <div class="stat-chips" id="stat-chips"></div>
            <div id="phases-wrap" style="margin-top:14px;display:none">
                <div class="section-label">Fases</div>
                <div class="phases" id="phases-timeline"></div>
            </div>
        </div>
    </div>

    <!-- Proyectos iniciados -->
    <div class="panel projects-panel">
        <div class="section-label">Proyectos</div>
        <div id="projects-list">
            <div class="empty">Cargando proyectos…</div>
        </div>
    </div>

    <div class="panel models-panel">
        <div class="section-label">Modelos de agentes</div>
        <div class="sub">Selecciona el LLM de cada agente antes de iniciar un proyecto o cámbialo desde aquí.</div>
        <div id="models-panel" class="models-grid">
            <div class="empty">Cargando modelos…</div>
        </div>
        <div class="models-actions">
            <button class="btn-outline" type="button" onclick="saveAgentModels()">Guardar modelos</button>
            <span id="models-status" class="models-status"></span>
        </div>
    </div>

    <!-- Bloqueantes -->
    <div id="blockers-wrap"></div>

    <!-- Pestañas -->
    <div class="tabs">
        <button class="tab-btn active" onclick="setTab('tasks',this)">Tareas</button>
        <button class="tab-btn" onclick="setTab('log',this)">Registro de eventos</button>
        <button class="tab-btn" onclick="setTab('files',this)">Archivos</button>
    </div>

    <div class="panel" id="tab-content">
        <div class="empty">Cargando datos…</div>
    </div>

    <!-- Formulario nuevo proyecto -->
    <div class="start-form">
        <h3>Iniciar nuevo proyecto</h3>
        <div class="form-row">
            <textarea id="brief" placeholder="Describe el proyecto (mínimo 10 caracteres)…"></textarea>
        </div>
        <div class="form-row">
            <input id="repo-url"  placeholder="URL del repositorio (opcional)" />
            <input id="repo-name" placeholder="Nombre del repositorio (opcional)" />
            <input id="branch"    placeholder="Rama (opcional)" />
        </div>
        <div class="form-check">
            <input type="checkbox" id="allow-init" />
            <label for="allow-init">Permitir inicialización local del repositorio</label>
        </div>
        <button class="btn-start" id="btn-start" onclick="startProject()">Iniciar proyecto</button>
    </div>

</div>
<script>
const API = '/devsquad/api';

const AGENT_META = {
    arch:  { name:'ARCH',  rol:'Coordinador', model:'nvidia/z-ai/glm5',            emoji:'🗂️', color:'#7F77DD' },
    byte:  { name:'BYTE',  rol:'Programador', model:'nvidia/moonshotai/kimi-k2.5', emoji:'💻', color:'#1D9E75' },
    pixel: { name:'PIXEL', rol:'Diseñador',   model:'nvidia/moonshotai/kimi-k2.5', emoji:'🎨', color:'#D85A30' },
};
const STATUS_COLOR = {
    working:  { bg:'#EAF3DE', text:'#3B6D11', dot:'#639922' },
    thinking: { bg:'#EEEDFE', text:'#3C3489', dot:'#7F77DD' },
    speaking: { bg:'#E1F5EE', text:'#0F6E56', dot:'#1D9E75' },
    idle:     { bg:'#F1EFE8', text:'#5F5E5A', dot:'#888780' },
    error:    { bg:'#FCEBEB', text:'#791F1F', dot:'#E24B4A' },
    offline:  { bg:'#F1EFE8', text:'#888780', dot:'#B4B2A9' },
    delivered:{ bg:'#EAF3DE', text:'#3B6D11', dot:'#639922' },
    planned:  { bg:'#EEEDFE', text:'#3C3489', dot:'#7F77DD' },
    blocked:  { bg:'#FFF2D8', text:'#9A5B00', dot:'#D48A00' },
};
const TASK_COLOR = {
    pending:     { bg:'#F1EFE8', text:'#5F5E5A' },
    in_progress: { bg:'#EEEDFE', text:'#3C3489' },
    done:        { bg:'#EAF3DE', text:'#3B6D11' },
    error:       { bg:'#FCEBEB', text:'#791F1F' },
};
const STATUS_ES = {
    idle:'inactivo', working:'trabajando', thinking:'pensando', speaking:'hablando',
    error:'error', offline:'desconectado', delivered:'entregado', planned:'planificado',
    blocked:'bloqueado',
    in_progress:'en progreso', done:'completado', pending:'pendiente',
};

let memory = null;
let filesSnapshot = null;
let filesLoading = false;
let filesError = null;
let filesRequestedAt = 0;
let filesRequestPromise = null;
let filesScope = 'running';
let activeTab = 'tasks';
let modelConfig = null;
let streamSource = null;
const MODEL_SELECTION_KEY = 'devsquad:model-selection:v1';
const LOG_MAX = 200;
const LOG_AGENT_LIMIT = 5;
let selectedFilePath = null;
let selectedFilePreview = null;
let selectedFileLoading = false;
let selectedFileError = null;

const t = s => STATUS_ES[s] || s;

function dedupeLog(log) {
    const seen = new Set();
    const out = [];
    for (const e of log || []) {
        const k = `${e.ts||''}|${e.agent||''}|${e.msg||''}`;
        if (seen.has(k)) continue;
        seen.add(k);
        out.push(e);
        if (out.length >= LOG_MAX) break;
    }
    return out;
}

function agentModel(agentId, fallback) {
    return (modelConfig && modelConfig[agentId]) ? modelConfig[agentId] : fallback;
}

function renderAgentLogs(agentId) {
    const log = dedupeLog(memory?.log || []);
    const items = log.filter(e => e.agent === agentId).slice(-LOG_AGENT_LIMIT);
    if (!items.length) return '<div class="empty">Sin actividad reciente.</div>';
    return items.map(e => `
        <div class="agent-log-item">
            <span class="agent-log-time">${escapeHtml(fmtTime(e.ts))}</span>
            <span>${escapeHtml(e.msg)}</span>
        </div>
    `).join('');
}

function badge(state) {
    const c = STATUS_COLOR[state] || STATUS_COLOR.offline;
    return `<span class="badge" style="background:${c.bg};color:${c.text}">
        <span class="badge-dot" style="background:${c.dot}"></span>${t(state)}</span>`;
}

function fmtTime(iso) {
    if (!iso) return '';
    try { return new Date(iso).toLocaleTimeString('es-ES'); } catch { return iso; }
}
function fmtDate(iso) {
    if (!iso) return '';
    try { return new Date(iso).toLocaleString('es-ES'); } catch { return iso; }
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function loadSelectedAgentModels() {
    try {
        const raw = localStorage.getItem(MODEL_SELECTION_KEY);
        return raw ? JSON.parse(raw) : {};
    } catch {
        return {};
    }
}

function saveSelectedAgentModels(models) {
    try {
        localStorage.setItem(MODEL_SELECTION_KEY, JSON.stringify(models || {}));
    } catch {}
}

function availableModelsByProvider() {
    const models = Array.isArray(modelConfig?.available) ? modelConfig.available : [];
    const grouped = {};
    for (const model of models) {
        const provider = model.provider || 'unknown';
        if (!grouped[provider]) grouped[provider] = [];
        grouped[provider].push(model);
    }
    return grouped;
}

function availableModelSet() {
    return new Set((Array.isArray(modelConfig?.available) ? modelConfig.available : []).map(model => model.qualified));
}

function currentAgentModel(agentId) {
    return modelConfig?.agents?.[agentId]?.model || AGENT_META[agentId]?.model || '';
}

function resolvedAgentModel(agentId) {
    const stored = loadSelectedAgentModels()[agentId];
    const current = currentAgentModel(agentId);
    const available = availableModelSet();
    if (stored && available.has(stored)) return stored;
    if (current && available.has(current)) return current;
    const models = Array.isArray(modelConfig?.available) ? modelConfig.available : [];
    return models[0]?.qualified || current || stored || '';
}

function selectedAgentModels() {
    return ['arch', 'byte', 'pixel'].reduce((acc, agentId) => {
        const el = document.getElementById(`agent-model-${agentId}`);
        if (el && el.value) {
            acc[agentId] = el.value;
        } else {
            acc[agentId] = resolvedAgentModel(agentId);
        }
        return acc;
    }, {});
}

function updateModelsStatus(text, tone = 'muted') {
    const el = document.getElementById('models-status');
    if (!el) return;
    el.textContent = text || '';
    el.style.color = tone === 'error' ? '#791f1f' : tone === 'ok' ? '#3b6d11' : 'var(--text-tertiary)';
}

function renderModelSelect(agentId) {
    const grouped = availableModelsByProvider();
    const current = currentAgentModel(agentId);
    const selected = resolvedAgentModel(agentId);
    const options = Object.entries(grouped).map(([provider, models]) => {
        const opts = models.map(model => {
            const label = `${model.name || model.model_id || model.qualified} (${model.qualified})`;
            const selectedAttr = model.qualified === selected ? ' selected' : '';
            return `<option value="${escapeHtml(model.qualified)}"${selectedAttr}>${escapeHtml(label)}</option>`;
        }).join('');
        return `<optgroup label="${escapeHtml(provider)}">${opts}</optgroup>`;
    }).join('');

    return `<label class="model-row" for="agent-model-${agentId}">
        <div class="model-row-head">
            <div>
                <div class="model-row-title">${escapeHtml(AGENT_META[agentId]?.name || agentId.toUpperCase())}</div>
                <div class="model-row-meta">Actual: ${escapeHtml(current || 'sin definir')}</div>
            </div>
            <span class="badge" style="background:${STATUS_COLOR.idle.bg};color:${STATUS_COLOR.idle.text}">Modelo</span>
        </div>
        <select id="agent-model-${agentId}" class="model-select" onchange="onAgentModelChange()">
            ${options}
        </select>
    </label>`;
}

function renderModelPanel() {
    const container = document.getElementById('models-panel');
    if (!container) return;
    if (!modelConfig || !modelConfig.available) {
        container.innerHTML = '<div class="empty">Cargando modelos…</div>';
        return;
    }
    const agents = ['arch', 'byte', 'pixel'];
    container.innerHTML = agents.map(renderModelSelect).join('');
}

function onAgentModelChange() {
    saveSelectedAgentModels(selectedAgentModels());
    updateModelsStatus('Cambios listos para guardar', 'muted');
}

async function saveAgentModels() {
    const payload = selectedAgentModels();
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    updateModelsStatus('Guardando modelos...', 'muted');
    try {
        const r = await fetch(`${API}/models`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        });
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || data.errors?.join(', ') || 'No se pudieron guardar los modelos');
        modelConfig = data.config || modelConfig;
        saveSelectedAgentModels(payload);
        renderAgents(memory?.agents || {});
        renderModelPanel();
        updateModelsStatus('Modelos guardados', 'ok');
        return true;
    } catch (e) {
        updateModelsStatus(e.message || 'Error al guardar modelos', 'error');
        return false;
    }
}

function renderAgents(agents) {
    document.getElementById('agents-grid').innerHTML = Object.entries(AGENT_META).map(([id, meta]) => {
        const ag = agents?.[id] || {};
        const status = ag.status || 'offline';
        const c = STATUS_COLOR[status] || STATUS_COLOR.offline;
        const model = agentModel(id, meta.model);
        return `<div class="agent-card" style="border-top:3px solid ${meta.color}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:2px">
                        <span style="font-size:18px">${escapeHtml(meta.emoji)}</span>
                        <span style="font-weight:500;font-size:15px">${escapeHtml(meta.name)}</span>
                    </div>
                    <div style="font-size:12px;color:var(--text-secondary)">${escapeHtml(meta.rol)}</div>
                </div>
                ${badge(status)}
            </div>
            <div><span class="agent-model">${model}</span></div>
            ${ag.current_task ? `<div class="agent-task" style="border-left:2px solid ${meta.color}">
                Tarea: <strong>${escapeHtml(ag.current_task)}</strong></div>` : ''}
            ${ag.last_seen ? `<div class="agent-seen">Última actividad: ${escapeHtml(fmtTime(ag.last_seen))}</div>` : ''}
            <div class="agent-logs">
                <div class="agent-logs-title">Logs recientes</div>
                ${renderAgentLogs(id)}
            </div>
        </div>`;
    }).join('');
}

function renderProject(project, tasks, plan) {
    const bar = document.getElementById('project-bar');
    if (!project?.name) { bar.style.display = 'none'; return; }
    bar.style.display = 'block';
    const incompleteTasks = Array.isArray(tasks) ? tasks.filter(t => t && t.status !== 'done') : [];
    const hasErrors = incompleteTasks.some(t => t.status === 'error');
    const hasPending = incompleteTasks.some(t => t.status === 'pending');
    const projectStatus = String(project.status || '').toLowerCase();
    const displayStatus = (projectStatus === 'delivered' && incompleteTasks.length)
        ? 'blocked'
        : (projectStatus === 'delivered' && hasErrors)
            ? 'blocked'
            : project.status;
    document.getElementById('proj-name').textContent = project.name;
    document.getElementById('proj-desc').textContent = project.description || '';
    document.getElementById('proj-status-badge').innerHTML = badge(displayStatus);

    document.getElementById('proj-meta').innerHTML = [
        project.repo_path && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">📁 ${project.repo_path}</span>`,
        project.branch    && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">🌿 ${project.branch}</span>`,
        project.tech_stack?.backend && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">⚙️ ${project.tech_stack.backend}</span>`,
        project.created_at && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">📅 ${fmtDate(project.created_at)}</span>`,
    ].filter(Boolean).join('');

    const canResume = hasErrors || hasPending || (projectStatus === 'delivered' && incompleteTasks.length > 0);
    if (canResume) {
        document.getElementById('proj-meta').innerHTML += `
            <button class="btn-outline" type="button" onclick="resumeProject()">
                Reanudar tareas
            </button>
        `;
    }
    if (displayStatus === 'blocked' && projectStatus === 'delivered' && incompleteTasks.length) {
        document.getElementById('proj-meta').innerHTML += `
            <span class="chip" style="background:#FCEBEB;color:#791F1F">Estado corregido: había tareas sin completar</span>
        `;
    }

    const total = tasks.length;
    if (!total) { document.getElementById('proj-progress-wrap').style.display = 'none'; return; }
    document.getElementById('proj-progress-wrap').style.display = 'block';

    const done    = tasks.filter(t=>t.status==='done').length;
    const inProg  = tasks.filter(t=>t.status==='in_progress').length;
    const pending = tasks.filter(t=>t.status==='pending').length;
    const errors  = tasks.filter(t=>t.status==='error').length;
    const pct = Math.round((done/total)*100);

    document.getElementById('proj-pct').textContent = pct + '%';
    const fill = document.getElementById('proj-fill');
    fill.style.width = pct + '%';
    fill.style.background = pct===100 ? '#639922' : '#7F77DD';

    document.getElementById('stat-chips').innerHTML = [
        {label:'Completadas', val:done,    bg:'#EAF3DE', tc:'#3B6D11'},
        {label:'En progreso', val:inProg,  bg:'#EEEDFE', tc:'#3C3489'},
        {label:'Pendientes',  val:pending, bg:'#F1EFE8', tc:'#5F5E5A'},
        {label:'Errores',     val:errors,  bg:'#FCEBEB', tc:'#791F1F'},
    ].map(s=>`<span class="chip" style="background:${s.bg};color:${s.tc}">${s.val} ${s.label}</span>`).join('');

    const phases = plan?.phases || [];
    const phWrap = document.getElementById('phases-wrap');
    if (phases.length) {
        phWrap.style.display = 'block';
        document.getElementById('phases-timeline').innerHTML = phases.map(ph => {
            const pt = tasks.filter(t=>t.phase===ph.id);
            const pd = pt.filter(t=>t.status==='done').length;
            const active = pt.some(t=>t.status==='in_progress');
            const ppct = pt.length ? Math.round((pd/pt.length)*100) : 0;
            const fillColor = pd===pt.length && pt.length>0 ? '#639922' : active ? '#7F77DD' : '#B4B2A9';
            return `<div class="phase-item">
                <div style="font-size:11px;color:${active?'#7F77DD':'var(--text-secondary)'};font-weight:${active?500:400};margin-bottom:4px">${escapeHtml(ph.name||ph.id)}</div>
                <div class="phase-bar"><div class="phase-fill" style="width:${ppct}%;background:${fillColor}"></div></div>
                <div style="font-size:10px;color:var(--text-tertiary);margin-top:3px">${pd}/${pt.length}</div>
            </div>`;
        }).join('');
    } else { phWrap.style.display = 'none'; }
}

function renderBlockers(blockers) {
    document.getElementById('blockers-wrap').innerHTML = (blockers||[]).slice(-3).map(b =>
        `<div class="blocker">⚠️ [${b.source}] ${b.msg}</div>`
    ).join('');
}

function renderTasks(tasks) {
    if (!tasks.length) return '<div class="empty">Sin tareas — inicia un proyecto abajo.</div>';
    return tasks.map(task => {
        const ag = AGENT_META[task.agent] || {};
        const tc = TASK_COLOR[task.status] || TASK_COLOR.pending;
        const canRetry = task.status === 'error' || task.retryable || Number(task.failure_count || 0) > 0;
        const failureCount = Number(task.failure_count || 0);
        return `<div class="task-row">
            <div class="task-meta">
                <span class="task-id">${task.id}</span>
                <span class="task-title">${task.title||''}</span>
                <span style="font-size:11px;font-weight:500;min-width:56px;color:${ag.color||'#888'}">${ag.emoji||''} ${ag.name||task.agent}</span>
                <span class="badge" style="background:${tc.bg};color:${tc.text};min-width:80px;text-align:center">${t(task.status)}</span>
                ${canRetry ? `<button class="btn-outline" type="button" onclick="resumeTask('${task.id}')">Reanudar</button>` : ''}
            </div>
            ${failureCount ? `<div class="task-skills">Intentos fallidos: ${failureCount}${task.suggested_agent ? ` · sugerido: ${escapeHtml((AGENT_META[task.suggested_agent] || {}).name || task.suggested_agent)}` : ''}</div>` : ''}
            ${task.skills?.length ? `<div class="task-skills">Habilidades: ${task.skills.join(' · ')}</div>` : ''}
        </div>`;
    }).join('');
}

function renderLog(log) {
    const agColor = a => AGENT_META[a]?.color || '#888780';
    const clean = dedupeLog(log);
    if (!clean.length) return '<span style="color:var(--text-tertiary)">Sin eventos aún…</span>';
    return [...clean].reverse().slice(0,80).map(e =>
        `<div style="display:flex;gap:10px;align-items:flex-start">
            <span style="color:var(--text-tertiary);flex-shrink:0">${escapeHtml(fmtTime(e.ts))}</span>
            <span style="color:${agColor(e.agent)};flex-shrink:0;min-width:40px">[${escapeHtml(e.agent)}]</span>
            <span>${escapeHtml(e.msg)}</span>
        </div>`
    ).join('');
}

function fileLabel(path) {
    if (!path) return 'Archivo';
    const parts = path.split('/').filter(Boolean);
    return parts[parts.length - 1] || path;
}

function previewUrl(path) {
    return `${API.replace(/\/api$/, '')}/files/view?path=${encodeURIComponent(path)}`;
}

function downloadUrl(path) {
    return `/devsquad/files/download?path=${encodeURIComponent(path)}`;
}

async function previewWorkspaceFile(path) {
    selectedFilePath = path;
    selectedFileLoading = true;
    selectedFileError = null;
    selectedFilePreview = null;
    if (activeTab === 'files') renderTab();
    try {
        const r = await fetch(previewUrl(path));
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || 'No se pudo cargar el archivo');
        selectedFilePreview = data.file;
    } catch (e) {
        selectedFileError = e.message || 'No se pudo cargar el archivo';
    } finally {
        selectedFileLoading = false;
        if (activeTab === 'files') renderTab();
    }
}

async function fetchFilesSnapshot(force = false) {
    const now = Date.now();
    if (filesRequestPromise) return filesRequestPromise;
    if (!force && filesSnapshot && now - filesRequestedAt < 4000) {
        return filesSnapshot;
    }

    filesLoading = true;
    filesError = null;
    filesRequestedAt = now;
    if (activeTab === 'files') renderTab();

    filesRequestPromise = fetch(`${API}/files`)
        .then(async (r) => {
            const data = await r.json();
            if (!r.ok) throw new Error(data.error || 'No se pudieron cargar los archivos');
            filesSnapshot = data;
            return data;
        })
        .catch((e) => {
            filesError = e.message || 'No se pudieron cargar los archivos';
            return null;
        })
        .finally(() => {
            filesLoading = false;
            filesRequestPromise = null;
            if (activeTab === 'files') renderTab();
        });

    return filesRequestPromise;
}

function labelForProjectPath(project, root) {
    if (!project || !root) return 'Archivos';
    if (root.label === 'repo_path') return 'Repo';
    if (root.label === 'output_dir') return 'Workspace';
    return root.label || 'Archivos';
}

function isFinalizedProject(project) {
    const status = String(project?.status || '').toLowerCase();
    const orchestratorStatus = String(project?.orchestrator?.status || '').toLowerCase();
    return ['delivered', 'deleted', 'error', 'failed', 'completed'].includes(status)
        || ['delivered', 'error', 'failed', 'completed'].includes(orchestratorStatus);
}

function isActiveProject(project) {
    const status = String(project?.status || '').toLowerCase();
    const orchestratorStatus = String(project?.orchestrator?.status || '').toLowerCase();
    return ['planning', 'planned', 'executing', 'execution', 'running', 'in_progress'].includes(status)
        || ['planning', 'executing', 'execution', 'running', 'working', 'in_progress'].includes(orchestratorStatus);
}

function filteredProjects(projects) {
    if (!Array.isArray(projects)) return [];
    if (filesScope === 'all') return projects;
    if (filesScope === 'finished') return projects.filter(isFinalizedProject);
    return projects.filter(isActiveProject);
}

function filesScopeLabel(scope) {
    if (scope === 'finished') return 'Finalizados';
    if (scope === 'all') return 'Todos';
    return 'En ejecución';
}

function setFilesScope(scope) {
    filesScope = scope || 'running';
    if (activeTab === 'files') {
        renderTab();
    }
}

function renderFileRow(item, prefix = '') {
    const active = item.path === selectedFilePath ? 'active' : '';
    return `<div class="file-row ${active}">
        <div class="file-main">
            <div class="file-name">${escapeHtml(fileLabel(item.path))}</div>
            <div class="file-path">${escapeHtml(item.path)}</div>
            <div class="file-meta">
                ${item.group ? `<span class="file-tag">${escapeHtml(item.group)}</span>` : ''}
                ${item.extension ? `<span class="file-tag">.${escapeHtml(item.extension)}</span>` : ''}
            </div>
        </div>
        <div class="file-actions">
            <button class="file-button" onclick="${prefix}previewWorkspaceFile('${item.path.replace(/'/g, "\\'")}')">Ver</button>
            <a class="file-button" href="${downloadUrl(item.path)}" style="text-decoration:none;display:inline-flex;align-items:center">Descargar</a>
        </div>
    </div>`;
}

function renderFiles(mem) {
    const snapshot = filesSnapshot || mem || {};
    const projects = filteredProjects(Array.isArray(snapshot.projects) ? snapshot.projects : []);
    const fallbackEntries = (() => {
        const entries = [];
        const seen = new Set();
        const push = (path, group) => {
            if (!path || seen.has(path)) return;
            seen.add(path);
            entries.push({ path, group });
        };
        (snapshot?.files_produced || []).forEach(path => push(path, 'Generados'));
        (snapshot?.progress_files || []).forEach(path => push(path, 'Progreso'));
        (snapshot?.tasks || []).forEach(task => {
            if (task?.progress_file) push(task.progress_file, 'Progreso');
            if (task?.workspace_context) push(task.workspace_context, 'Workspace');
        });
        return entries;
    })();

    const projectHtml = projects.length ? projects.map(project => {
        const roots = Array.isArray(project.roots) ? project.roots : [];
        const totalFiles = project.total_files || roots.reduce((sum, root) => sum + ((root.files || []).length), 0);
        return `<section class="file-group">
            <div class="file-group-head">
                <div>
                    <div class="file-group-title">${escapeHtml(project.name || project.id || 'Proyecto')}</div>
                    <div class="file-group-subtitle">${escapeHtml(project.id || '')}${project.status ? ` · ${escapeHtml(t(project.status))}` : ''}</div>
                </div>
                <div class="file-group-count">${totalFiles} archivo${totalFiles === 1 ? '' : 's'}</div>
            </div>
            <div class="project-files">
                ${roots.length ? roots.map(root => {
                    const files = Array.isArray(root.files) ? root.files : [];
                    return `<div class="project-root">
                        <div class="project-root-head">
                            <div class="project-root-title">${escapeHtml(labelForProjectPath(project, root))}</div>
                            <div class="project-root-path">${escapeHtml(root.path || '')}</div>
                            <div class="project-root-count">${files.length} archivo${files.length === 1 ? '' : 's'}</div>
                        </div>
                        <div class="file-list">
                            ${files.length ? files.map(item => renderFileRow(item)).join('') : '<div class="empty">Sin archivos todavía en esta raíz.</div>'}
                        </div>
                    </div>`;
                }).join('') : '<div class="empty">Sin rutas de archivos disponibles para este proyecto.</div>'}
            </div>
        </section>`;
    }).join('') : '';

    const legacyHtml = !projects.length && fallbackEntries.length ? fallbackEntries.map(item => renderFileRow(item)).join('') : '';
    const preview = selectedFilePreview;
    const previewTitle = preview?.name || (selectedFilePath ? fileLabel(selectedFilePath) : 'Selecciona un archivo');
    const previewMeta = preview ? `${preview.mime || 'text/plain'} · ${preview.size || 0} bytes${preview.truncated ? ' · truncado' : ''}` : '';
    const previewBody = selectedFileLoading
        ? '<div class="file-preview-empty">Cargando archivo…</div>'
        : selectedFileError
            ? `<div class="file-preview-empty">${escapeHtml(selectedFileError)}</div>`
            : preview
                ? `<pre>${escapeHtml(preview.content || '')}</pre>`
                : '<div class="file-preview-empty">Selecciona un archivo para ver su contenido.</div>';

    const toolbar = `<div class="files-toolbar">
        <label>
            Ver archivos de
            <select onchange="setFilesScope(this.value)">
                <option value="running"${filesScope === 'running' ? ' selected' : ''}>En ejecución</option>
                <option value="finished"${filesScope === 'finished' ? ' selected' : ''}>Finalizados</option>
                <option value="all"${filesScope === 'all' ? ' selected' : ''}>Todos</option>
            </select>
        </label>
        <div class="hint">${escapeHtml(filesScopeLabel(filesScope))}</div>
    </div>`;

    return `${toolbar}
    <div class="file-browser">
        <div class="file-groups">
            ${projects.length ? projectHtml : (filesScope === 'running' ? '<div class="empty">No hay proyectos en ejecución con archivos para mostrar.</div>' : legacyHtml)}
        </div>
        <div class="file-preview">
            <div class="file-preview-head">
                <div>
                    <div class="file-preview-title">${escapeHtml(previewTitle)}</div>
                    <div class="file-preview-meta">${escapeHtml(previewMeta)}</div>
                </div>
                ${selectedFilePath ? `<a class="file-button" href="${downloadUrl(selectedFilePath)}" style="text-decoration:none;display:inline-flex;align-items:center">Descargar</a>` : ''}
            </div>
            <div class="file-preview-body">
                ${previewBody}
            </div>
        </div>
    </div>`;
}

function renderProjects(projects, currentId) {
    const list = (projects && projects.length) ? projects : (memory?.project?.name ? [memory.project] : []);
    if (!list.length) return '<div class="empty">Sin proyectos iniciados aún.</div>';
    return list.map(p => {
        const isCurrent = currentId && p.id === currentId;
        const title = p.name || p.id || 'Proyecto sin nombre';
        const status = p.status ? badge(p.status) : badge('idle');
        const created = p.created_at ? fmtDate(p.created_at) : '';
        const actions = isCurrent ? `
            <div class="project-actions">
                <button class="btn-outline" onclick="pauseProject()">Pausar</button>
                <button class="btn-danger" onclick="deleteProject()">Eliminar</button>
            </div>` : '';
        return `<div class="project-item">
            <div class="project-meta">
                <div class="project-title">${title}</div>
                <div style="font-size:12px;color:var(--text-tertiary)">${created}</div>
            </div>
            <div style="display:flex;gap:10px;align-items:center">
                ${status}
                ${actions}
            </div>
        </div>`;
    }).join('');
}

async function pauseProject() {
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const r = await fetch(`${API}/project/pause`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (!r.ok) throw new Error('No se pudo pausar el proyecto');
    } catch (e) {
        alert(e.message || 'Error al pausar el proyecto');
    }
}

async function deleteProject() {
    if (!confirm('¿Eliminar el proyecto y detener su ejecución?')) return;
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const r = await fetch(`${API}/project/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (!r.ok) throw new Error('No se pudo eliminar el proyecto');
    } catch (e) {
        alert(e.message || 'Error al eliminar el proyecto');
    }
}

async function resumeProject(taskId = null) {
    const body = taskId ? { task_id: taskId, resume_all_failed: false } : { resume_all_failed: true };
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const r = await fetch(`${API}/project/resume`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(body),
        });
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || 'No se pudo reanudar el proyecto');
        fetchInitialState();
        fetchFilesSnapshot(true);
    } catch (e) {
        alert(e.message || 'Error al reanudar el proyecto');
    }
}

async function resumeTask(taskId) {
    return resumeProject(taskId);
}

async function fetchModels() {
    try {
        const r = await fetch(`${API}/models`);
        if (r.ok) {
            modelConfig = await r.json();
            renderModelPanel();
        }
    } catch {}
}

async function fetchInitialState() {
    try {
        const r = await fetch(`${API}/state`);
        if (!r.ok) return;
        const data = await r.json();
        if (data) {
            render(data);
        }
    } catch (e) {
        console.error('No se pudo cargar el estado inicial de Dev Squad', e);
        if (!memory) {
            memory = {
                agents: {},
                tasks: [],
                log: [],
                blockers: [],
                projects: [],
                project: null,
                plan: { phases: [] },
            };
            document.getElementById('projects-list').innerHTML = '<div class="empty">Sin proyectos iniciados aún.</div>';
            renderTab();
        }
    }
}

function setTab(tab, btn) {
    activeTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderTab();
    if (tab === 'files') {
        fetchFilesSnapshot();
    }
}

function renderTab() {
    if (!memory) return;
    const content = document.getElementById('tab-content');
    if (activeTab === 'tasks')      content.innerHTML = renderTasks(memory.tasks||[]);
    else if (activeTab === 'log')   content.innerHTML = `<div class="log-feed">${renderLog(memory.log||[])}</div>`;
    else                            content.innerHTML = renderFiles(filesSnapshot || memory);
}

function render(mem) {
    memory = mem;
    renderAgents(mem.agents);
    renderProject(mem.project, mem.tasks||[], mem.plan||{});
    renderBlockers(mem.blockers);
    document.getElementById('projects-list').innerHTML = renderProjects(mem.projects||[], mem.project?.id);
    renderTab();
    if (activeTab === 'files') {
        fetchFilesSnapshot();
    }
}

function setConnected(ok) {
    document.getElementById('conn-dot').style.background = ok ? '#639922' : '#e24b4a';
    document.getElementById('conn-label').textContent = ok ? 'Conectado' : 'Desconectado';
}

function startStream() {
    if (typeof EventSource === 'undefined') {
        setConnected(false);
        document.getElementById('conn-label').textContent = 'Stream no soportado';
        return;
    }

    if (streamSource) {
        streamSource.close();
    }

    streamSource = new EventSource(`${API}/stream`);
    streamSource.onopen = () => setConnected(true);
    streamSource.onmessage = (event) => {
        try {
            render(JSON.parse(event.data));
            setConnected(true);
            if (activeTab === 'files') {
                fetchFilesSnapshot();
            }
        } catch (e) {
            console.error('No se pudo procesar el stream del Dev Squad', e);
        }
    };
    streamSource.onerror = () => {
        setConnected(false);
    };

    window.addEventListener('beforeunload', () => {
        if (streamSource) {
            streamSource.close();
            streamSource = null;
        }
    }, { once: true });
}

async function startProject() {
    const brief = document.getElementById('brief').value.trim();
    if (brief.length < 10) { alert('El brief debe tener al menos 10 caracteres.'); return; }
    const btn = document.getElementById('btn-start');
    btn.disabled = true; btn.textContent = 'Iniciando…';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const modelsSaved = await saveAgentModels();
        if (!modelsSaved) {
            throw new Error('No se pudieron guardar los modelos seleccionados');
        }
        const r = await fetch(`${API}/project/start`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                brief,
                repo_url:        document.getElementById('repo-url').value.trim() || null,
                repo_name:       document.getElementById('repo-name').value.trim() || null,
                branch:          document.getElementById('branch').value.trim() || null,
                allow_init_repo: document.getElementById('allow-init').checked,
            }),
        });
        const data = await r.json();
        if (r.ok) {
            document.getElementById('brief').value = '';
            alert('Proyecto iniciado: ' + (data.message || 'OK'));
        } else {
            alert('Error: ' + (data.error || JSON.stringify(data)));
        }
    } catch(e) { alert('Error de conexión: ' + e.message); }
    btn.disabled = false; btn.textContent = 'Iniciar proyecto';
}

fetchModels();
setInterval(fetchModels, 60000);
fetchInitialState();
fetchFilesSnapshot(true);
startStream();
</script>
</body>
</html>
