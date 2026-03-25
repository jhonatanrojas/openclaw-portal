<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .file-item { font-family: var(--font-mono); font-size: 12px; padding: 4px 0;
            border-bottom: 0.5px solid var(--border); color: var(--text-secondary); }
        .file-item:last-child { border-bottom: none; }
        .empty { color: var(--text-tertiary); font-size: 13px; padding: 12px 0; }

        .section-label { font-size: 11px; color: var(--text-tertiary); margin-bottom: 6px; }

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
    pixel: { name:'PIXEL', rol:'Diseñador',   model:'deepseek/deepseek-chat',       emoji:'🎨', color:'#D85A30' },
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
    in_progress:'en progreso', done:'completado', pending:'pendiente',
};

let memory = null;
let activeTab = 'tasks';

const t = s => STATUS_ES[s] || s;

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

function renderAgents(agents) {
    document.getElementById('agents-grid').innerHTML = Object.entries(AGENT_META).map(([id, meta]) => {
        const ag = agents?.[id] || {};
        const status = ag.status || 'offline';
        const c = STATUS_COLOR[status] || STATUS_COLOR.offline;
        return `<div class="agent-card" style="border-top:3px solid ${meta.color}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:2px">
                        <span style="font-size:18px">${meta.emoji}</span>
                        <span style="font-weight:500;font-size:15px">${meta.name}</span>
                    </div>
                    <div style="font-size:12px;color:var(--text-secondary)">${meta.rol}</div>
                </div>
                ${badge(status)}
            </div>
            <div><span class="agent-model">${meta.model}</span></div>
            ${ag.current_task ? `<div class="agent-task" style="border-left:2px solid ${meta.color}">
                Tarea: <strong>${ag.current_task}</strong></div>` : ''}
            ${ag.last_seen ? `<div class="agent-seen">Última actividad: ${fmtTime(ag.last_seen)}</div>` : ''}
        </div>`;
    }).join('');
}

function renderProject(project, tasks, plan) {
    const bar = document.getElementById('project-bar');
    if (!project?.name) { bar.style.display = 'none'; return; }
    bar.style.display = 'block';
    document.getElementById('proj-name').textContent = project.name;
    document.getElementById('proj-desc').textContent = project.description || '';
    document.getElementById('proj-status-badge').innerHTML = badge(project.status);

    document.getElementById('proj-meta').innerHTML = [
        project.repo_path && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">📁 ${project.repo_path}</span>`,
        project.branch    && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">🌿 ${project.branch}</span>`,
        project.tech_stack?.backend && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">⚙️ ${project.tech_stack.backend}</span>`,
        project.created_at && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">📅 ${fmtDate(project.created_at)}</span>`,
    ].filter(Boolean).join('');

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
                <div style="font-size:11px;color:${active?'#7F77DD':'var(--text-secondary)'};font-weight:${active?500:400};margin-bottom:4px">${ph.name||ph.id}</div>
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
        return `<div class="task-row">
            <div class="task-meta">
                <span class="task-id">${task.id}</span>
                <span class="task-title">${task.title||''}</span>
                <span style="font-size:11px;font-weight:500;min-width:56px;color:${ag.color||'#888'}">${ag.emoji||''} ${ag.name||task.agent}</span>
                <span class="badge" style="background:${tc.bg};color:${tc.text};min-width:80px;text-align:center">${t(task.status)}</span>
            </div>
            ${task.skills?.length ? `<div class="task-skills">Habilidades: ${task.skills.join(' · ')}</div>` : ''}
        </div>`;
    }).join('');
}

function renderLog(log) {
    const agColor = a => AGENT_META[a]?.color || '#888780';
    if (!log.length) return '<span style="color:var(--text-tertiary)">Sin eventos aún…</span>';
    return [...log].reverse().slice(0,80).map(e =>
        `<div style="display:flex;gap:10px;align-items:flex-start">
            <span style="color:var(--text-tertiary);flex-shrink:0">${fmtTime(e.ts)}</span>
            <span style="color:${agColor(e.agent)};flex-shrink:0;min-width:40px">[${e.agent}]</span>
            <span>${e.msg}</span>
        </div>`
    ).join('');
}

function renderFiles(files) {
    if (!files?.length) return '<div class="empty">Sin archivos generados aún.</div>';
    return files.map(f => `<div class="file-item">📄 ${f}</div>`).join('');
}

function setTab(tab, btn) {
    activeTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderTab();
}

function renderTab() {
    if (!memory) return;
    const content = document.getElementById('tab-content');
    if (activeTab === 'tasks')      content.innerHTML = renderTasks(memory.tasks||[]);
    else if (activeTab === 'log')   content.innerHTML = `<div class="log-feed">${renderLog(memory.log||[])}</div>`;
    else                            content.innerHTML = renderFiles(memory.files_produced||[]);
}

function render(mem) {
    memory = mem;
    renderAgents(mem.agents);
    renderProject(mem.project, mem.tasks||[], mem.plan||{});
    renderBlockers(mem.blockers);
    renderTab();
}

function setConnected(ok) {
    document.getElementById('conn-dot').style.background = ok ? '#639922' : '#e24b4a';
    document.getElementById('conn-label').textContent = ok ? 'Conectado' : 'Desconectado';
}

function startPolling() {
    const poll = async () => {
        try {
            const r = await fetch(`${API}/state`);
            if (r.ok) { render(await r.json()); setConnected(true); }
            else setConnected(false);
        } catch { setConnected(false); }
    };
    poll();
    setInterval(poll, 3000);
}

async function startProject() {
    const brief = document.getElementById('brief').value.trim();
    if (brief.length < 10) { alert('El brief debe tener al menos 10 caracteres.'); return; }
    const btn = document.getElementById('btn-start');
    btn.disabled = true; btn.textContent = 'Iniciando…';
    try {
        const r = await fetch(`${API}/project/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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

startPolling();
</script>
</body>
</html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Squad Dashboard — OpenClaw</title>
    <style>
        :root {
            --color-background-primary: #ffffff;
            --color-background-secondary: #f5f4f0;
            --color-border-tertiary: #e8e6df;
            --color-text-primary: #1a1916;
            --color-text-secondary: #5f5e5a;
            --color-text-tertiary: #888780;
            --border-radius-lg: 10px;
            --border-radius-md: 6px;
            --font-mono: 'JetBrains Mono', 'Fira Code', monospace;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
               background: #f0ede6; color: var(--color-text-primary); min-height: 100vh; }
        .wrap { padding: 1.5rem 1rem; max-width: 900px; margin: 0 auto; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header h2 { font-size: 20px; font-weight: 500; }
        .header .sub { font-size: 13px; color: var(--color-text-secondary); margin-top: 2px; }
        .conn-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
        .conn-label { font-size: 12px; color: var(--color-text-secondary); }

        /* Cards grid */
        .agents-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 20px; }
        .agent-card { background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg); padding: 1rem 1.25rem; }
        .agent-name { font-weight: 500; font-size: 15px; }
        .agent-role { font-size: 12px; color: var(--color-text-secondary); }
        .agent-model { font-family: var(--font-mono); font-size: 11px;
            background: var(--color-background-secondary); padding: 1px 6px;
            border-radius: 4px; display: inline-block; margin: 6px 0; }
        .agent-task { font-size: 12px; color: var(--color-text-secondary);
            background: var(--color-background-secondary); padding: 6px 8px;
            border-radius: 6px; margin-top: 6px; }
        .agent-seen { font-size: 11px; color: var(--color-text-tertiary); margin-top: 8px; }

        /* Status badge */
        .badge { font-size: 11px; font-weight: 500; padding: 2px 8px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 4px; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

        /* Project bar */
        .project-bar { background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg); padding: 1rem 1.25rem; margin-bottom: 16px; }
        .progress-track { height: 6px; background: var(--color-background-secondary);
            border-radius: 6px; overflow: hidden; margin: 6px 0 12px; }
        .progress-fill { height: 100%; border-radius: 6px; transition: width .6s ease; }
        .stat-chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .chip { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }

        /* Phase timeline */
        .phases { display: flex; gap: 8px; margin: 8px 0; }
        .phase-item { flex: 1; }
        .phase-name { font-size: 11px; color: var(--color-text-secondary); margin-bottom: 4px; }
        .phase-bar { height: 4px; background: var(--color-background-secondary); border-radius: 4px; overflow: hidden; }
        .phase-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }
        .phase-count { font-size: 10px; color: var(--color-text-tertiary); margin-top: 3px; }

        /* Tabs */
        .tabs { display: flex; border-bottom: 0.5px solid var(--color-border-tertiary); margin-bottom: 16px; }
        .tab-btn { background: none; border: none; cursor: pointer; padding: 8px 16px;
            font-size: 13px; color: var(--color-text-secondary);
            border-bottom: 2px solid transparent; margin-bottom: -1px; }
        .tab-btn.active { font-weight: 500; color: var(--color-text-primary);
            border-bottom-color: var(--color-text-primary); }

        /* Task list */
        .panel { background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg); padding: .75rem 1.25rem; }
        .task-row { padding: 8px 0; border-bottom: 0.5px solid var(--color-border-tertiary); font-size: 13px; }
        .task-row:last-child { border-bottom: none; }
        .task-meta { display: flex; align-items: center; gap: 10px; }
        .task-id { font-family: var(--font-mono); font-size: 11px;
            color: var(--color-text-secondary); min-width: 52px; }
        .task-title { flex: 1; }
        .task-agent { font-size: 11px; font-weight: 500; min-width: 56px; }
        .task-skills { margin-left: 62px; margin-top: 4px; font-size: 11px;
            color: var(--color-text-tertiary); }

        /* Log feed */
        .log-feed { height: 200px; overflow-y: auto; font-family: var(--font-mono);
            font-size: 11px; line-height: 1.7; background: var(--color-background-secondary);
            border-radius: var(--border-radius-md); padding: 8px 12px; }
        .log-row { display: flex; gap: 10px; align-items: flex-start; }
        .log-ts { color: var(--color-text-tertiary); flex-shrink: 0; }
        .log-agent { flex-shrink: 0; min-width: 36px; }
        .log-msg { color: var(--color-text-primary); }

        /* Start form */
        .start-form { background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg); padding: 1rem 1.25rem; margin-top: 20px; }
        .start-form h3 { font-size: 14px; font-weight: 500; margin-bottom: 12px; }
        .form-row { display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap; }
        .form-row input, .form-row textarea {
            flex: 1; min-width: 180px; padding: 7px 10px; font-size: 13px;
            border: 0.5px solid var(--color-border-tertiary); border-radius: 6px;
            background: var(--color-background-secondary); color: var(--color-text-primary);
            font-family: inherit; }
        .form-row textarea { min-height: 72px; resize: vertical; }
        .form-check { display: flex; align-items: center; gap: 6px; font-size: 13px;
            color: var(--color-text-secondary); margin-bottom: 10px; }
        .btn-start { background: #1a1916; color: #fff; border: none; cursor: pointer;
            padding: 8px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; }
        .btn-start:disabled { opacity: .5; cursor: not-allowed; }

        /* Files list */
        .file-item { font-family: var(--font-mono); font-size: 12px; padding: 4px 0;
            border-bottom: 0.5px solid var(--color-border-tertiary); color: var(--color-text-secondary); }
        .file-item:last-child { border-bottom: none; }

        /* Blockers */
        .blocker { background: #fcebeb; color: #791f1f; font-size: 12px;
            padding: 6px 10px; border-radius: 6px; margin-bottom: 6px; }

        @media (max-width: 600px) {
            .agents-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="wrap">

    <!-- Header -->
    <div class="header">
        <div>
            <h2>Dev Squad</h2>
            <div class="sub">Multi-agent programming team</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px">
            <span class="conn-dot" id="conn-dot" style="background:#e24b4a"></span>
            <span class="conn-label" id="conn-label">Connecting…</span>
        </div>
    </div>

    <!-- Agent Cards -->
    <div class="agents-grid" id="agents-grid">
        <!-- rendered by JS -->
    </div>

    <!-- Project Bar -->
    <div id="project-bar" style="display:none" class="project-bar">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
            <div>
                <div style="font-weight:500;font-size:14px" id="proj-name"></div>
                <div style="font-size:12px;color:var(--color-text-secondary);margin-top:2px" id="proj-desc"></div>
                <div id="proj-meta" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px"></div>
            </div>
            <span id="proj-status-badge"></span>
        </div>
        <div id="proj-progress-wrap" style="display:none">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                <span style="font-size:12px;color:var(--color-text-secondary)">Overall progress</span>
                <span style="font-size:13px;font-weight:500" id="proj-pct">0%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="proj-fill" style="width:0%;background:#7f77dd"></div>
            </div>
            <div class="stat-chips" id="stat-chips"></div>
            <div id="phases-wrap" style="margin-top:14px;display:none">
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-bottom:6px">Phases</div>
                <div class="phases" id="phases-timeline"></div>
            </div>
        </div>
    </div>

    <!-- Blockers -->
    <div id="blockers-wrap"></div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="setTab('tasks')">Tasks</button>
        <button class="tab-btn" onclick="setTab('log')">Event log</button>
        <button class="tab-btn" onclick="setTab('files')">Files</button>
    </div>

    <div class="panel" id="tab-content">
        <div style="color:var(--color-text-tertiary);font-size:13px;padding:12px 0">
            Connecting to Dev Squad API…
        </div>
    </div>

    <!-- Start Project Form -->
    <div class="start-form">
        <h3>Start a new project</h3>
        <div class="form-row">
            <textarea id="brief" placeholder="Describe the project (min 10 chars)…"></textarea>
        </div>
        <div class="form-row">
            <input id="repo-url"  placeholder="Repo URL (optional)" />
            <input id="repo-name" placeholder="Repo name (optional)" />
            <input id="branch"    placeholder="Branch (optional)" />
        </div>
        <div class="form-check">
            <input type="checkbox" id="allow-init" />
            <label for="allow-init">Allow local repo init</label>
        </div>
        <button class="btn-start" id="btn-start" onclick="startProject()">Start project</button>
    </div>

</div>

<script>
const API = '/devsquad/api';
const AGENT_META = {
    arch:  { name:'ARCH',  role:'Coordinator', model:'nvidia/z-ai/glm5',            emoji:'🗂️', color:'#7F77DD' },
    byte:  { name:'BYTE',  role:'Programmer',  model:'nvidia/moonshotai/kimi-k2.5', emoji:'💻', color:'#1D9E75' },
    pixel: { name:'PIXEL', role:'Designer',    model:'deepseek/deepseek-chat',       emoji:'🎨', color:'#D85A30' },
};
const STATUS_COLOR = {
    working:  { bg:'#EAF3DE', text:'#3B6D11', dot:'#639922' },
    thinking: { bg:'#EEEDFE', text:'#3C3489', dot:'#7F77DD' },
    speaking: { bg:'#E1F5EE', text:'#0F6E56', dot:'#1D9E75' },
    idle:     { bg:'#F1EFE8', text:'#5F5E5A', dot:'#888780' },
    error:    { bg:'#FCEBEB', text:'#791F1F', dot:'#E24B4A' },
    offline:  { bg:'#F1EFE8', text:'#888780', dot:'#B4B2A9' },
};
const TASK_COLOR = {
    pending:     { bg:'#F1EFE8', text:'#5F5E5A' },
    in_progress: { bg:'#EEEDFE', text:'#3C3489' },
    done:        { bg:'#EAF3DE', text:'#3B6D11' },
    error:       { bg:'#FCEBEB', text:'#791F1F' },
};

let memory = null;
let activeTab = 'tasks';

function badge(state) {
    const c = STATUS_COLOR[state] || STATUS_COLOR.offline;
    return `<span class="badge" style="background:${c.bg};color:${c.text}">
        <span class="badge-dot" style="background:${c.dot}"></span>${state||'offline'}</span>`;
}

function renderAgents(agents) {
    const grid = document.getElementById('agents-grid');
    grid.innerHTML = Object.entries(AGENT_META).map(([id, meta]) => {
        const ag = agents?.[id] || {};
        const status = ag.status || 'offline';
        const c = STATUS_COLOR[status] || STATUS_COLOR.offline;
        return `<div class="agent-card" style="border-top:3px solid ${meta.color}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                <div>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:2px">
                        <span style="font-size:18px">${meta.emoji}</span>
                        <span class="agent-name">${meta.name}</span>
                    </div>
                    <div class="agent-role">${meta.role}</div>
                </div>
                ${badge(status)}
            </div>
            <div><span class="agent-model">${meta.model}</span></div>
            ${ag.current_task ? `<div class="agent-task" style="border-left:2px solid ${meta.color}">
                Task: <strong>${ag.current_task}</strong></div>` : ''}
            ${ag.last_seen ? `<div class="agent-seen">Last seen: ${new Date(ag.last_seen).toLocaleTimeString()}</div>` : ''}
        </div>`;
    }).join('');
}

function renderProject(project, tasks, plan) {
    const bar = document.getElementById('project-bar');
    if (!project?.name) { bar.style.display = 'none'; return; }
    bar.style.display = 'block';
    document.getElementById('proj-name').textContent = project.name;
    document.getElementById('proj-desc').textContent = project.description || '';
    document.getElementById('proj-status-badge').innerHTML = badge(project.status);

    const meta = document.getElementById('proj-meta');
    meta.innerHTML = [
        project.repo_path && `<span class="chip" style="background:var(--color-background-secondary);color:var(--color-text-secondary)">Repo: ${project.repo_path}</span>`,
        project.branch    && `<span class="chip" style="background:var(--color-background-secondary);color:var(--color-text-secondary)">Branch: ${project.branch}</span>`,
    ].filter(Boolean).join('');

    const total = tasks.length;
    if (!total) { document.getElementById('proj-progress-wrap').style.display = 'none'; return; }
    document.getElementById('proj-progress-wrap').style.display = 'block';

    const done = tasks.filter(t=>t.status==='done').length;
    const inProg = tasks.filter(t=>t.status==='in_progress').length;
    const pending = tasks.filter(t=>t.status==='pending').length;
    const errors = tasks.filter(t=>t.status==='error').length;
    const pct = Math.round((done/total)*100);

    document.getElementById('proj-pct').textContent = pct + '%';
    const fill = document.getElementById('proj-fill');
    fill.style.width = pct + '%';
    fill.style.background = pct===100 ? '#639922' : '#7F77DD';

    document.getElementById('stat-chips').innerHTML = [
        {label:'Done', val:done, bg:'#EAF3DE', tc:'#3B6D11'},
        {label:'In progress', val:inProg, bg:'#EEEDFE', tc:'#3C3489'},
        {label:'Pending', val:pending, bg:'#F1EFE8', tc:'#5F5E5A'},
        {label:'Errors', val:errors, bg:'#FCEBEB', tc:'#791F1F'},
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
                <div class="phase-name" style="color:${active?'#7F77DD':'var(--color-text-secondary)'};font-weight:${active?500:400}">${ph.name||ph.id}</div>
                <div class="phase-bar"><div class="phase-fill" style="width:${ppct}%;background:${fillColor}"></div></div>
                <div class="phase-count">${pd}/${pt.length}</div>
            </div>`;
        }).join('');
    } else { phWrap.style.display = 'none'; }
}

function renderBlockers(blockers) {
    const wrap = document.getElementById('blockers-wrap');
    if (!blockers?.length) { wrap.innerHTML = ''; return; }
    wrap.innerHTML = blockers.slice(-3).map(b =>
        `<div class="blocker">⚠️ [${b.source}] ${b.msg}</div>`
    ).join('');
}

function renderTasks(tasks) {
    if (!tasks.length) return '<div style="color:var(--color-text-tertiary);font-size:13px;padding:12px 0">No tasks yet — start a project below.</div>';
    return tasks.map(t => {
        const ag = AGENT_META[t.agent] || {};
        const tc = TASK_COLOR[t.status] || TASK_COLOR.pending;
        return `<div class="task-row">
            <div class="task-meta">
                <span class="task-id">${t.id}</span>
                <span class="task-title">${t.title||''}</span>
                <span class="task-agent" style="color:${ag.color||'#888'}">${ag.emoji||''} ${ag.name||t.agent}</span>
                <span class="badge" style="background:${tc.bg};color:${tc.text};min-width:72px;text-align:center">${t.status}</span>
            </div>
            ${t.skills?.length ? `<div class="task-skills">Skills: ${t.skills.join(' · ')}</div>` : ''}
        </div>`;
    }).join('');
}

function renderLog(log) {
    const agColor = a => AGENT_META[a]?.color || '#888780';
    if (!log.length) return '<span style="color:var(--color-text-tertiary)">No events yet…</span>';
    return [...log].reverse().slice(0,60).map(e =>
        `<div class="log-row">
            <span class="log-ts">${new Date(e.ts).toLocaleTimeString()}</span>
            <span class="log-agent" style="color:${agColor(e.agent)}">[${e.agent}]</span>
            <span class="log-msg">${e.msg}</span>
        </div>`
    ).join('');
}

function renderFiles(files) {
    if (!files?.length) return '<div style="color:var(--color-text-tertiary);font-size:13px;padding:12px 0">No files produced yet.</div>';
    return files.map(f => `<div class="file-item">${f}</div>`).join('');
}

function setTab(tab) {
    activeTab = tab;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    renderTab();
}

function renderTab() {
    const content = document.getElementById('tab-content');
    if (!memory) return;
    const tasks = memory.tasks || [];
    const log   = memory.log   || [];
    const files = memory.files_produced || [];
    if (activeTab === 'tasks') content.innerHTML = renderTasks(tasks);
    else if (activeTab === 'log') content.innerHTML = `<div class="log-feed">${renderLog(log)}</div>`;
    else content.innerHTML = renderFiles(files);
}

function render(mem) {
    memory = mem;
    renderAgents(mem.agents);
    renderProject(mem.project, mem.tasks||[], mem.plan||{});
    renderBlockers(mem.blockers);
    renderTab();
}

// Polling fallback (SSE/WS no disponible desde proxy Laravel)
function startPolling() {
    const poll = async () => {
        try {
            const r = await fetch(`${API}/state`);
            if (r.ok) {
                const data = await r.json();
                render(data);
                setConnected(true);
            }
        } catch { setConnected(false); }
    };
    poll();
    setInterval(poll, 3000);
}

function setConnected(ok) {
    document.getElementById('conn-dot').style.background = ok ? '#639922' : '#e24b4a';
    document.getElementById('conn-label').textContent = ok ? 'Connected' : 'Disconnected';
}

async function startProject() {
    const brief = document.getElementById('brief').value.trim();
    if (brief.length < 10) { alert('Brief must be at least 10 characters.'); return; }
    const btn = document.getElementById('btn-start');
    btn.disabled = true; btn.textContent = 'Starting…';
    try {
        const r = await fetch(`${API}/project/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                brief,
                repo_url:       document.getElementById('repo-url').value.trim() || null,
                repo_name:      document.getElementById('repo-name').value.trim() || null,
                branch:         document.getElementById('branch').value.trim() || null,
                allow_init_repo: document.getElementById('allow-init').checked,
            }),
        });
        const data = await r.json();
        if (r.ok) {
            document.getElementById('brief').value = '';
            alert('Project started: ' + (data.message || 'OK'));
        } else {
            alert('Error: ' + (data.error || JSON.stringify(data)));
        }
    } catch(e) { alert('Request failed: ' + e.message); }
    btn.disabled = false; btn.textContent = 'Start project';
}

// Init
startPolling();
</script>
</body>
</html>
