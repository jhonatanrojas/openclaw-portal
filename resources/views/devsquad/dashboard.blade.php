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
.agent-chat { margin-top: 10px; border: 0.5px solid var(--border); border-radius: var(--radius-md); background: linear-gradient(180deg, rgba(245,244,240,.96), rgba(255,255,255,.99)); padding: 10px 12px; display: grid; gap: 8px; animation: agentChatIn .2s ease; min-height: 116px; }
.agent-chat-head { display: flex; justify-content: space-between; gap: 10px; align-items: flex-start; flex-wrap: wrap; }
.agent-chat-label { font-size: 11px; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: .04em; }
.agent-chat-meta { font-size: 10px; color: var(--text-tertiary); display: flex; gap: 6px; flex-wrap: wrap; }
.agent-chat-body { font-size: 12px; color: var(--text-secondary); line-height: 1.6; white-space: pre-wrap; word-break: break-word; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; min-height: 64px; }
.agent-chat-empty { margin-top: 10px; border: 0.5px dashed var(--border); border-radius: var(--radius-md); padding: 10px 12px; color: var(--text-tertiary); font-size: 11px; background: rgba(245,244,240,.5); }

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
.miniverse-shell { display: grid; gap: 12px; }
.miniverse-hero {
    border-radius: var(--radius-lg);
    padding: 18px;
    color: #f8fafc;
    background:
        radial-gradient(circle at top right, rgba(125, 211, 252, 0.16), transparent 32%),
        linear-gradient(135deg, #0f172a 0%, #111827 55%, #1e293b 100%);
    border: 0.5px solid #243041;
}
.miniverse-kicker {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .16em;
    color: #7dd3fc;
    margin-bottom: 8px;
}
.miniverse-title { font-size: 22px; font-weight: 700; line-height: 1.1; margin-bottom: 6px; }
.miniverse-subtitle { font-size: 13px; color: rgba(248, 250, 252, .74); max-width: 68ch; }
.miniverse-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
.miniverse-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 999px;
    border: 1px solid rgba(226, 232, 240, .18);
    background: rgba(15, 23, 42, .45);
    color: #e2e8f0;
    text-decoration: none;
    font-size: 12px;
}
.miniverse-action.primary { background: #7dd3fc; color: #0f172a; border-color: #7dd3fc; font-weight: 600; }
.miniverse-grid { display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr); gap: 12px; }
.miniverse-card {
    border: 0.5px solid var(--border);
    border-radius: var(--radius-lg);
    background: var(--bg-primary);
    padding: 12px;
}
.miniverse-card-head { display: flex; justify-content: space-between; gap: 10px; align-items: flex-start; margin-bottom: 10px; }
.miniverse-card h4 { font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: var(--text-secondary); }
.miniverse-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
.miniverse-stats { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
.miniverse-stat {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 999px;
    background: rgba(125, 211, 252, .12);
    color: #0f172a;
    border: 1px solid rgba(125, 211, 252, .25);
}
.miniverse-stat.soft {
    background: rgba(15, 23, 42, .08);
    color: var(--text-secondary);
    border-color: rgba(15, 23, 42, .12);
}
.miniverse-stats.compact { margin-top: 8px; }
.miniverse-agent-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
.miniverse-agent-card {
    border: 0.5px solid var(--border);
    border-radius: 8px;
    background: var(--bg-secondary);
    padding: 10px;
}
.miniverse-agent-name { font-weight: 600; font-size: 12px; }
.miniverse-agent-meta { font-size: 11px; color: var(--text-secondary); margin-top: 4px; }
.miniverse-agent-task { font-size: 11px; color: var(--text-tertiary); margin-top: 4px; }
.miniverse-json {
    font-family: var(--font-mono);
    font-size: 11px;
    background: #0b1220;
    color: #dbeafe;
    border-radius: 8px;
    padding: 10px;
    overflow: auto;
    max-height: 280px;
    white-space: pre-wrap;
    word-break: break-word;
}
.miniverse-preview-grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr); gap: 12px; align-items: start; }
.miniverse-side-stack { display: grid; gap: 12px; }
.miniverse-iframe-shell {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background:
        radial-gradient(circle at top right, rgba(125, 211, 252, 0.10), transparent 36%),
        linear-gradient(180deg, #050816 0%, #0f172a 100%);
    border: 1px solid rgba(125, 211, 252, 0.18);
    min-height: 560px;
}
.miniverse-iframe-shell iframe {
    display: block;
    width: 100%;
    min-height: 560px;
    height: 100%;
    border: 0;
    background: #050816;
}
.miniverse-iframe-overlay {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    pointer-events: none;
}
.miniverse-iframe-overlay .badge,
.miniverse-iframe-overlay .miniverse-action {
    pointer-events: auto;
}
.miniverse-iframe-hint {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(15, 23, 42, .72);
    color: #e2e8f0;
    border: 1px solid rgba(226, 232, 240, .16);
    backdrop-filter: blur(8px);
    font-size: 11px;
}
.miniverse-iframe-fallback {
    min-height: 560px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 14px;
    color: #e2e8f0;
    background:
        radial-gradient(circle at top right, rgba(125, 211, 252, 0.16), transparent 34%),
        linear-gradient(135deg, #020617 0%, #111827 58%, #1e293b 100%);
}
.miniverse-iframe-fallback h4 { font-size: 18px; margin-bottom: 4px; color: #f8fafc; }
.miniverse-iframe-fallback p { color: rgba(226, 232, 240, .76); line-height: 1.55; }
.miniverse-fallback-bullets { display: grid; gap: 8px; margin-top: 4px; }
.miniverse-fallback-bullet {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    padding: 10px 12px;
    border-radius: 10px;
    background: rgba(15, 23, 42, .38);
    border: 1px solid rgba(226, 232, 240, .12);
    font-size: 12px;
    color: #dbeafe;
}
.miniverse-fallback-step {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 999px;
    background: rgba(125, 211, 252, .18);
    color: #7dd3fc;
    flex: 0 0 auto;
    font-size: 11px;
    font-weight: 600;
    margin-top: 1px;
}
.miniverse-details {
    margin-top: 12px;
    border-top: 1px solid var(--border);
    padding-top: 10px;
}
.miniverse-details summary {
    cursor: pointer;
    font-size: 12px;
    color: var(--text-secondary);
    list-style: none;
}
.miniverse-details summary::-webkit-details-marker { display: none; }
.miniverse-url {
    font-size: 12px;
    color: var(--text-secondary);
    word-break: break-word;
}
.gateway-panel { display: grid; gap: 12px; }
.gateway-toolbar { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; flex-wrap: wrap; }
.gateway-status { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.gateway-status .badge { white-space: normal; }
.gateway-url { font-size: 11px; color: var(--text-tertiary); font-family: var(--font-mono); word-break: break-all; }
.gateway-event-list { display: grid; gap: 10px; max-height: 420px; overflow: auto; padding-right: 4px; }
.gateway-chat-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
.gateway-chat-card { border: 0.5px solid var(--border); border-radius: var(--radius-md); background: linear-gradient(180deg, rgba(245,244,240,.92), rgba(255,255,255,.98)); padding: 12px 14px; display: grid; gap: 8px; box-shadow: 0 1px 0 rgba(0,0,0,.02); animation: gatewayChatIn .22s ease; min-height: 160px; }
.gateway-chat-card-head { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; align-items: flex-start; }
.gateway-chat-card-title { font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.gateway-chat-card-meta { font-size: 11px; color: var(--text-tertiary); display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.gateway-chat-card-body { font-size: 12px; color: var(--text-secondary); line-height: 1.65; white-space: pre-wrap; word-break: break-word; min-height: 72px; }
.gateway-chat-card-source { font-size: 10px; color: var(--text-tertiary); font-family: var(--font-mono); word-break: break-all; }
.gateway-pill { font-size: 10px; padding: 2px 6px; border-radius: 999px; background: var(--bg-primary); color: var(--text-secondary); border: 0.5px solid var(--border); }
.gateway-empty { color: var(--text-tertiary); font-size: 13px; padding: 12px 0; }
@keyframes gatewayChatIn {
    from { opacity: .25; transform: translateY(6px) scale(.99); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes agentChatIn {
    from { opacity: .35; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.file-preview { border: 0.5px solid var(--border); border-radius: var(--radius-lg); background: #161512; color: #f2efe6; overflow: hidden; display: flex; flex-direction: column; min-height: 420px; }
        .file-preview-head { display: flex; justify-content: space-between; gap: 10px; align-items: center; padding: 10px 12px; border-bottom: 0.5px solid rgba(255,255,255,.08); background: rgba(255,255,255,.03); }
        .file-preview-title { font-size: 13px; font-weight: 600; word-break: break-word; }
        .file-preview-meta { font-size: 11px; color: rgba(242,239,230,.72); margin-top: 2px; }
        .file-preview-body { flex: 1; overflow: auto; padding: 12px; }
        .file-preview-body pre { margin: 0; white-space: pre-wrap; word-break: break-word; font-family: var(--font-mono); font-size: 12px; line-height: 1.65; }
        .file-preview-empty { color: rgba(242,239,230,.6); font-size: 13px; padding: 8px 0; }
        .empty { color: var(--text-tertiary); font-size: 13px; padding: 12px 0; }

        .section-label { font-size: 11px; color: var(--text-tertiary); margin-bottom: 6px; }
        .projects-toolbar { display: flex; gap: 10px; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; }
        .projects-toolbar label { font-size: 12px; color: var(--text-secondary); display: flex; gap: 8px; align-items: center; font-weight: 500; }
        .projects-toolbar select { border: 0.5px solid var(--border); border-radius: 6px; background: var(--bg-primary); color: var(--text-primary); padding: 6px 10px; font: inherit; font-size: 12px; }
        .projects-toolbar .hint { font-size: 12px; color: var(--text-tertiary); }

        .projects-panel { margin-bottom: 16px; }
        .project-item { display: flex; align-items: center; justify-content: space-between;
            padding: 10px 0; border-bottom: 0.5px solid var(--border); font-size: 13px; }
        .project-item:last-child { border-bottom: none; }
        .project-meta { display: flex; flex-direction: column; gap: 4px; }
        .project-title { font-weight: 500; font-size: 14px; }
        .project-actions { display: flex; gap: 8px; }
        .runtime-panel { margin: 16px 0; display: grid; gap: 12px; }
        .runtime-panel-head { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; align-items: center; }
        .runtime-summary { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .runtime-list { display: grid; gap: 8px; }
        .runtime-item { display: flex; justify-content: space-between; gap: 12px; padding: 10px 12px; border: 0.5px solid var(--border); border-radius: var(--radius-md); background: var(--bg-secondary); }
        .runtime-item-main { min-width: 0; }
        .runtime-item-title { font-size: 13px; font-weight: 600; display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .runtime-item-meta { font-size: 11px; color: var(--text-tertiary); margin-top: 4px; word-break: break-all; }
        .runtime-item-actions { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
        .runtime-issues { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn-outline { background: transparent; border: 0.5px solid var(--border); color: var(--text-secondary);
            cursor: pointer; padding: 6px 10px; border-radius: 6px; font-size: 12px; }
        .btn-danger { background: #E24B4A; color: #fff; border: none; cursor: pointer;
            padding: 6px 10px; border-radius: 6px; font-size: 12px; }

        @media (max-width: 1080px) {
            .miniverse-preview-grid { grid-template-columns: 1fr; }
            .miniverse-iframe-shell,
            .miniverse-iframe-shell iframe,
            .miniverse-iframe-fallback { min-height: 460px; }
        }
        @media (max-width: 760px) { .gateway-chat-grid { grid-template-columns: 1fr; } }
        @media (max-width: 720px) {
            .agents-grid,
            .miniverse-agent-grid { grid-template-columns: 1fr; }
            .miniverse-grid { grid-template-columns: 1fr; }
            .miniverse-iframe-overlay { flex-direction: column; align-items: stretch; }
            .miniverse-iframe-shell,
            .miniverse-iframe-shell iframe,
            .miniverse-iframe-fallback { min-height: 380px; }
        }
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

    <div class="panel runtime-panel">
        <div id="runtime-panel">
            <div class="empty">Cargando ejecuciones…</div>
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
        <button class="tab-btn" onclick="setTab('gateway',this)">Eventos del Gateway</button>
        <button class="tab-btn" onclick="setTab('files',this)">Archivos</button>
        <button class="tab-btn" onclick="setTab('miniverse',this)">Miniverse</button>
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
    paused:   { bg:'#FFF2D8', text:'#9A5B00', dot:'#D48A00' },
    sleeping: { bg:'#F1EFE8', text:'#5F5E5A', dot:'#B4B2A9' },
};
const TASK_COLOR = {
    pending:     { bg:'#F1EFE8', text:'#5F5E5A' },
    in_progress: { bg:'#EEEDFE', text:'#3C3489' },
    paused:      { bg:'#FFF2D8', text:'#9A5B00' },
    done:        { bg:'#EAF3DE', text:'#3B6D11' },
    error:       { bg:'#FCEBEB', text:'#791F1F' },
};
const STATUS_ES = {
    idle:'inactivo', running:'en ejecución', working:'trabajando', thinking:'pensando', speaking:'hablando',
    error:'error', offline:'desconectado', delivered:'entregado', planned:'planificado',
    blocked:'bloqueado', paused:'pausado', resumable:'reanudable',
    sleeping:'durmiendo',
    in_progress:'en progreso', done:'completado', pending:'pendiente',
};

let memory = null;
let filesSnapshot = null;
let filesLoading = false;
let filesError = null;
let filesRequestedAt = 0;
let filesRequestPromise = null;
let filesScope = 'running';
let projectsScope = 'active';
let activeTab = 'tasks';
let modelConfig = null;
let streamSource = null;
let gatewaySource = null;
let gatewayReconnectTimer = null;
let gatewaySnapshot = { status: {}, events: [] };
let runtimeSnapshot = null;
let runtimeRequestPromise = null;
let runtimeRequestedAt = 0;
let miniverseSnapshot = null;
let miniverseLoading = false;
let miniverseError = null;
let miniverseRequestedAt = 0;
let miniverseRequestPromise = null;
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

function normalizeGatewayValue(value) {
    if (Array.isArray(value)) {
        return value.map(normalizeGatewayValue);
    }
    if (!value || typeof value !== 'object') {
        return value;
    }
    return Object.keys(value).sort().reduce((acc, key) => {
        if (['timestamp', 'ts', 'received_at', '_meta', 'date'].includes(key)) {
            return acc;
        }
        acc[key] = normalizeGatewayValue(value[key]);
        return acc;
    }, {});
}

function gatewayEventFingerprint(event) {
    if (!event || typeof event !== 'object') return '';
    try {
        return JSON.stringify({
            agent_id: String(event.agent_id || ''),
            session_key: String(event.session_key || ''),
            event: String(event.event || ''),
            kind: String(event.kind || ''),
            seq: event.seq ?? null,
            stateVersion: event.stateVersion ?? null,
            payload: normalizeGatewayValue(event.payload || {}),
        });
    } catch {
        return `${event.agent_id || ''}|${event.session_key || ''}|${event.event || ''}|${event.kind || ''}|${event.seq ?? ''}|${event.stateVersion ?? ''}`;
    }
}

function dedupeGatewayEvents(events) {
    const seen = new Set();
    const out = [];
    for (const event of Array.isArray(events) ? events : []) {
        const key = gatewayEventFingerprint(event);
        if (!key || seen.has(key)) continue;
        seen.add(key);
        out.push(event);
    }
    return out;
}

function agentModel(agentId, fallback) {
    return modelConfig?.agents?.[agentId]?.model || fallback;
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
        <select id="agent-model-${agentId}" class="model-select" onchange="onAgentModelChange('${agentId}')">
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

async function onAgentModelChange(agentId) {
    const select = document.getElementById(`agent-model-${agentId}`);
    const model = select?.value || '';
    if (!model) return;
    const selected = loadSelectedAgentModels();
    selected[agentId] = model;
    saveSelectedAgentModels(selected);
    updateModelsStatus(`Guardando ${agentId}...`, 'muted');
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const r = await fetch(`${API}/models/agent`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ agent_id: agentId, model }),
        });
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || data.errors?.join(', ') || 'No se pudo guardar el modelo');
        modelConfig = data.config || modelConfig;
        saveSelectedAgentModels(selected);
        renderAgents(memory?.agents || {});
        renderModelPanel();
        updateModelsStatus(`${agentId.toUpperCase()} guardado`, 'ok');
    } catch (e) {
        updateModelsStatus(e.message || 'Error al guardar el modelo', 'error');
    }
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
        const latestChat = latestGatewayChatByAgent(id);
        const chatTone = latestChat ? gatewayEventTone(latestChat.kind) : null;
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
            ${latestChat ? `
                <div class="agent-chat" style="border-left:3px solid ${meta.color}">
                    <div class="agent-chat-head">
                        <div>
                            <div class="agent-chat-label">Último chat</div>
                            <div class="agent-chat-meta">
                                <span>${escapeHtml(fmtTime(latestChat.received_at || latestChat.ts))}</span>
                                <span>·</span>
                                <span>${escapeHtml(latestChat.event || 'chat')}</span>
                                ${latestChat.session_key ? `<span>·</span><span>${escapeHtml(latestChat.session_key)}</span>` : ''}
                            </div>
                        </div>
                        <span class="gateway-pill" style="background:${chatTone?.bg || '#F1EFE8'};color:${chatTone?.text || '#5F5E5A'}">${escapeHtml(gatewayAgentLabel(latestChat.agent_id || id))}</span>
                    </div>
                    <div class="agent-chat-body">${escapeHtml(gatewayMessageText(latestChat))}</div>
                </div>
            ` : `
                <div class="agent-chat-empty">Sin mensajes chat recientes del Gateway.</div>
            `}
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
    const runtimeStatus = String(project.runtime_status || '').toLowerCase();
    const taskCounts = project.task_counts || {};
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
        project.artifact_index && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">🧾 ${project.artifact_index.split('/').pop()}</span>`,
        runtimeStatus && `<span class="chip" style="background:${runtimeStatus === 'blocked' ? '#FFF2D8' : runtimeStatus === 'paused' ? '#F1EFE8' : runtimeStatus === 'resumable' ? '#FCEBEB' : '#EEEDFE'};color:${runtimeStatus === 'blocked' ? '#9A5B00' : runtimeStatus === 'paused' ? '#5F5E5A' : runtimeStatus === 'resumable' ? '#791F1F' : '#3C3489'}">Estado operativo: ${t(runtimeStatus)}</span>`,
        taskCounts.open !== undefined && `<span class="chip" style="background:var(--bg-secondary);color:var(--text-secondary)">🧩 ${taskCounts.done || 0}/${taskCounts.total || tasks.length || 0} tareas cerradas</span>`,
    ].filter(Boolean).join('');

    const canResume = Boolean(project.can_resume) || hasErrors || hasPending || (projectStatus === 'delivered' && incompleteTasks.length > 0);
    if (canResume) {
        document.getElementById('proj-meta').innerHTML += `
            <button class="btn-outline" type="button" onclick="resumeProject()">
                Reanudar fallidas
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
        const canRetry = ['error', 'pending', 'paused', 'in_progress'].includes(String(task.status || '').toLowerCase()) || task.retryable || Number(task.failure_count || 0) > 0;
        const canPause = String(task.status || '').toLowerCase() === 'in_progress';
        const failureCount = Number(task.failure_count || 0);
        return `<div class="task-row">
            <div class="task-meta">
                <span class="task-id">${task.id}</span>
                <span class="task-title">${task.title||''}</span>
                <span style="font-size:11px;font-weight:500;min-width:56px;color:${ag.color||'#888'}">${ag.emoji||''} ${ag.name||task.agent}</span>
                <span class="badge" style="background:${tc.bg};color:${tc.text};min-width:80px;text-align:center">${t(task.status)}</span>
                ${canPause ? `<button class="btn-outline" type="button" onclick="pauseTask('${task.id}')">Pausar</button>` : ''}
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

function gatewayWsUrl() {
    const base = window.location.origin.replace(/^http/, 'ws');
    return `${base}/ws/gateway-events`;
}

function gatewayStatusSnapshot() {
    return gatewaySnapshot?.status || {};
}

function gatewayEventsList() {
    const events = dedupeGatewayEvents(Array.isArray(gatewaySnapshot?.events) ? gatewaySnapshot.events : []);
    return events.slice().sort((a, b) => {
        const at = new Date(a.received_at || a.ts || 0).getTime();
        const bt = new Date(b.received_at || b.ts || 0).getTime();
        return bt - at;
    });
}

function gatewayChatEventsList() {
    const events = gatewayEventsList().filter(event => String(event?.event || '').toLowerCase() === 'chat');
    const latestByAgent = new Map();
    for (const event of events) {
        const key = event.agent_id || event.session_key || 'unknown';
        const current = latestByAgent.get(key);
        const eventTime = new Date(event.received_at || event.ts || 0).getTime();
        const currentTime = current ? new Date(current.received_at || current.ts || 0).getTime() : -1;
        if (!current || eventTime >= currentTime) {
            latestByAgent.set(key, event);
        }
    }
    return Array.from(latestByAgent.values()).sort((a, b) => {
        const at = new Date(a.received_at || a.ts || 0).getTime();
        const bt = new Date(b.received_at || b.ts || 0).getTime();
        return bt - at;
    });
}

function latestGatewayChatByAgent(agentId) {
    const matchId = String(agentId || '').toLowerCase();
    return gatewayChatEventsList().find(event => String(event.agent_id || '').toLowerCase() === matchId) || null;
}

function gatewayAgentLabel(agentId) {
    return (AGENT_META[agentId] || {}).name || agentId || 'agent';
}

function gatewayEventTone(kind) {
    if (kind === 'thinking') return { bg:'#EEEDFE', text:'#3C3489' };
    if (kind === 'tool') return { bg:'#E1F5EE', text:'#0F6E56' };
    if (kind === 'message') return { bg:'#EAF3DE', text:'#3B6D11' };
    return { bg:'#F1EFE8', text:'#5F5E5A' };
}

function gatewayStatusBadge(status) {
    const connected = Boolean(status?.connected);
    const tone = connected ? { bg:'#EAF3DE', text:'#3B6D11', dot:'#639922' } : { bg:'#FCEBEB', text:'#791F1F', dot:'#E24B4A' };
    const label = connected ? 'Conectado' : 'Desconectado';
    return `<span class="badge" style="background:${tone.bg};color:${tone.text}">
        <span class="badge-dot" style="background:${tone.dot}"></span>${label}</span>`;
}

function gatewayEventSummary(event) {
    if (!event || typeof event !== 'object') return 'Sin resumen disponible';
    const summary = String(event.summary || '').trim();
    if (summary) return summary;
    const payload = event.payload || {};
    for (const key of ['text', 'message', 'content', 'detail', 'delta']) {
        const value = payload?.[key];
        if (typeof value === 'string' && value.trim()) return value.trim();
    }
    return 'Sin resumen disponible';
}

function gatewayMessageText(event) {
    if (!event || typeof event !== 'object') return 'Sin mensaje disponible';
    const payload = event.payload || {};
    const message = payload.message || {};
    const content = Array.isArray(message.content) ? message.content : [];
    const textParts = [];
    for (const item of content) {
        if (!item || typeof item !== 'object') continue;
        const itemType = String(item.type || '').toLowerCase();
        if (itemType === 'text' && item.text) {
            textParts.push(String(item.text));
        } else if (itemType === 'thinking' && item.thinking) {
            textParts.push(String(item.thinking));
        } else if (itemType === 'toolcall') {
            const toolName = item.name || item.tool || 'tool';
            const args = item.arguments && typeof item.arguments === 'object' ? JSON.stringify(item.arguments) : '';
            textParts.push(args ? `${toolName} ${args}` : String(toolName));
        }
    }
    const fallback = event.summary || payload.text || payload.message || payload.delta || '';
    const full = textParts.join(' ').trim() || String(fallback || '');
    return full || 'Sin mensaje disponible';
}

function renderGatewayEvent(event) {
    const agentId = event.agent_id || 'unknown';
    const meta = AGENT_META[agentId] || {};
    const tone = gatewayEventTone(event.kind);
    const summary = gatewayEventSummary(event);
    const sessionKey = event.session_key || '';
    const time = fmtTime(event.received_at || event.ts);
    const seq = event.seq !== undefined && event.seq !== null ? `#${event.seq}` : '';
    const version = event.stateVersion !== undefined && event.stateVersion !== null ? `v${event.stateVersion}` : '';
    const badges = [
        event.kind && `<span class="gateway-pill">${escapeHtml(event.kind)}</span>`,
        seq && `<span class="gateway-pill">${escapeHtml(seq)}</span>`,
        version && `<span class="gateway-pill">${escapeHtml(version)}</span>`,
    ].filter(Boolean).join('');
    return `<div class="gateway-event">
        <div class="gateway-event-head">
            <div>
                <div class="gateway-event-title">
                    <span style="color:${meta.color || '#888780'}">${escapeHtml(meta.emoji || '•')}</span>
                    <span>${escapeHtml(gatewayAgentLabel(agentId))}</span>
                    <span class="gateway-pill" style="background:${tone.bg};color:${tone.text}">${escapeHtml(event.event || 'event')}</span>
                </div>
                <div class="gateway-event-meta">
                    <span>${escapeHtml(time)}</span>
                    ${sessionKey ? `<span>·</span><span>${escapeHtml(sessionKey)}</span>` : ''}
                </div>
            </div>
            ${badges ? `<div style="display:flex;gap:6px;flex-wrap:wrap">${badges}</div>` : ''}
        </div>
        <div class="gateway-event-summary">${escapeHtml(summary)}</div>
    </div>`;
}

function renderGatewayChatCard(event) {
    const agentId = event.agent_id || 'unknown';
    const meta = AGENT_META[agentId] || {};
    const tone = gatewayEventTone(event.kind);
    const summary = gatewayMessageText(event);
    const sessionKey = event.session_key || '';
    const time = fmtTime(event.received_at || event.ts);
    const seq = event.seq !== undefined && event.seq !== null ? `#${event.seq}` : '';
    const version = event.stateVersion !== undefined && event.stateVersion !== null ? `v${event.stateVersion}` : '';
    const badges = [
        event.kind && `<span class="gateway-pill">${escapeHtml(event.kind)}</span>`,
        seq && `<span class="gateway-pill">${escapeHtml(seq)}</span>`,
        version && `<span class="gateway-pill">${escapeHtml(version)}</span>`,
    ].filter(Boolean).join('');
    return `<div class="gateway-chat-card">
        <div class="gateway-chat-card-head">
            <div>
                <div class="gateway-chat-card-title">
                    <span style="color:${meta.color || '#888780'}">${escapeHtml(meta.emoji || '•')}</span>
                    <span>${escapeHtml(gatewayAgentLabel(agentId))}</span>
                    <span class="gateway-pill" style="background:${tone.bg};color:${tone.text}">${escapeHtml(event.event || 'event')}</span>
                </div>
                <div class="gateway-chat-card-meta">
                    <span>${escapeHtml(time)}</span>
                    ${sessionKey ? `<span>·</span><span>${escapeHtml(sessionKey)}</span>` : ''}
                </div>
            </div>
            ${badges ? `<div style="display:flex;gap:6px;flex-wrap:wrap">${badges}</div>` : ''}
        </div>
        <div class="gateway-chat-card-body">${escapeHtml(summary)}</div>
        <div class="gateway-chat-card-source">${escapeHtml(sessionKey || 'session desconocida')}</div>
    </div>`;
}

function renderGatewayTab() {
    const status = gatewayStatusSnapshot();
    const events = gatewayEventsList();
    const error = status.last_error ? `<div class="gateway-empty" style="color:#791F1F">Último error: ${escapeHtml(status.last_error)}</div>` : '';
    const url = status.url ? `<div class="gateway-url">${escapeHtml(status.url)}</div>` : '';
    const lastEvent = status.last_event_at ? `<span class="gateway-pill">Último evento: ${escapeHtml(fmtTime(status.last_event_at))}</span>` : '';
    const count = `<span class="gateway-pill">${events.length} evento${events.length === 1 ? '' : 's'}</span>`;
    return `
        <div class="gateway-panel">
            <div class="gateway-toolbar">
                <div>
                    <div style="font-size:13px;font-weight:600">Stream de Gateway</div>
                    <div style="font-size:12px;color:var(--text-secondary);margin-top:3px">Eventos en vivo por sub-agente: thinking, tool calls y respuestas parciales.</div>
                    ${url}
                </div>
                <div class="gateway-status">
                    ${gatewayStatusBadge(status)}
                    ${count}
                    ${lastEvent}
                </div>
            </div>
            ${error}
            <div class="gateway-event-list">
                ${events.length ? events.map(renderGatewayEvent).join('') : '<div class="gateway-empty">No hay eventos del Gateway todavía.</div>'}
            </div>
        </div>
    `;
}

function normalizeMiniverseAgents(payload) {
    if (Array.isArray(payload)) return payload;
    if (Array.isArray(payload?.agents)) return payload.agents;
    if (payload && typeof payload === 'object') {
        const statKeys = ['online', 'total', 'version', 'world', 'grid'];
        const looksLikeStats = statKeys.every(key => key in payload) || (
            'online' in payload && 'total' in payload && !('state' in payload) && !('task' in payload) && !('agent' in payload)
        );
        if (looksLikeStats) return [];
        return Object.entries(payload).map(([name, value]) => {
            if (value && typeof value === 'object') {
                return { agent: name, name, ...value };
            }
            return { agent: name, name, state: value };
        });
    }
    return [];
}

function renderMiniverse(snapshot) {
    const current = snapshot || {};
    if (miniverseLoading && !current.repo && !current.world) {
        return '<div class="empty">Cargando Miniverse...</div>';
    }
    if (miniverseError && !current.repo && !current.world) {
        return `<div class="empty">${escapeHtml(miniverseError)}</div>`;
    }

    const repo = current.repo || {};
    const world = current.world || {};
    const ui = current.ui || {};
    const info = world.info || {};
    const agents = normalizeMiniverseAgents(world.agents);
    const visibleAgentCount = agents.filter(agent => {
        const state = String(agent?.state || agent?.status || '').toLowerCase();
        return state !== 'offline' && state !== 'sleeping';
    }).length;
    const onlineCount = Number(info?.agents?.online ?? visibleAgentCount ?? 0);
    const totalCount = Number(info?.agents?.total ?? agents.length ?? 0);
    const gridCols = info?.grid?.cols;
    const gridRows = info?.grid?.rows;
    const gridLabel = gridCols && gridRows ? `${gridCols}x${gridRows}` : 'desconocido';
    const worldLabel = info?.world || repo.full_name || 'Miniverse';
    const versionLabel = info?.version ? `v${info.version}` : null;
    const apiUrl = world.api_url || world.base_url || current.links?.api || '';
    const previewUrl = ui.final_url || ui.url || current.links?.ui || current.links?.world || world.ui_url || '';
    const previewCaption = ui.embeddable
        ? 'La UI pública se renderiza aquí dentro de la pestaña.'
        : 'La UI pública existe, pero el navegador la abre fuera del iframe.';
    const previewBadges = [
        ui.embeddable
            ? `<span class="miniverse-iframe-hint">Embed permitido</span>`
            : `<span class="miniverse-iframe-hint">Embed no disponible</span>`,
        ui.status_code != null ? `<span class="miniverse-iframe-hint">HTTP ${escapeHtml(String(ui.status_code))}</span>` : '',
        ui.blocked_by?.length ? `<span class="miniverse-iframe-hint">${escapeHtml(ui.blocked_by.join(' · '))}</span>` : '',
    ].filter(Boolean).join('');
    const previewPanel = ui.embeddable && previewUrl
        ? `<div class="miniverse-iframe-shell">
            <div class="miniverse-iframe-overlay">
                <span class="miniverse-iframe-hint">UI en vivo</span>
                <a class="miniverse-action" href="${escapeHtml(previewUrl)}" target="_blank" rel="noreferrer">Abrir UI</a>
            </div>
            <iframe src="${escapeHtml(previewUrl)}" title="Miniverse world preview" loading="lazy" referrerpolicy="no-referrer"></iframe>
        </div>`
        : `<div class="miniverse-iframe-fallback">
            <div>
                <h4>Vista del mundo</h4>
                <p>${escapeHtml(ui.embeddable ? 'La UI está lista, pero falta una URL válida para el preview.' : 'La UI pública existe, pero este navegador la está tratando como vista externa.')}</p>
            </div>
            <div class="miniverse-fallback-bullets">
                <div class="miniverse-fallback-bullet"><span class="miniverse-fallback-step">1</span><span>Abre la UI pública en una pestaña nueva.</span></div>
                <div class="miniverse-fallback-bullet"><span class="miniverse-fallback-step">2</span><span>Usa la URL pública del mundo para seguir su estado en vivo.</span></div>
            </div>
            <div class="miniverse-actions" style="margin-top:4px">
                ${previewUrl ? `<a class="miniverse-action primary" href="${escapeHtml(previewUrl)}" target="_blank" rel="noreferrer">Abrir UI</a>` : ''}
                ${current.links?.api ? `<a class="miniverse-action" href="${escapeHtml(current.links.api)}" target="_blank" rel="noreferrer">Abrir API</a>` : ''}
            </div>
        </div>`;
    const statItems = [
        worldLabel,
        versionLabel,
        `Agentes ${onlineCount}/${totalCount || visibleAgentCount || 0}`,
        `Grid ${gridLabel}`,
        repo.language ? `Lang ${repo.language}` : null,
        repo.license ? `License ${repo.license}` : null,
        ui.embeddable != null ? (ui.embeddable ? 'UI embebible' : 'UI externa') : null,
    ].filter(Boolean);

    const agentCards = agents.length ? agents.map(agent => {
        const name = agent.agent || agent.name || agent.id || 'agent';
        const state = String(agent.state || agent.status || 'idle').toLowerCase();
        const metaBits = [
            agent.task || agent.current_task || agent.activity || null,
            agent.role || null,
        ].filter(Boolean);
        const positionBits = [];
        if (agent.x !== undefined && agent.y !== undefined) positionBits.push(`(${agent.x}, ${agent.y})`);
        if (agent.world) positionBits.push(agent.world);
        if (agent.last_seen) positionBits.push(fmtTime(agent.last_seen));
        return `<article class="miniverse-agent-card">
            <div style="display:flex;justify-content:space-between;gap:8px;align-items:flex-start">
                <div>
                    <div class="miniverse-agent-name">${escapeHtml(name)}</div>
                    <div class="miniverse-agent-meta">${escapeHtml(metaBits.join(' | ') || 'Sin detalle adicional')}</div>
                </div>
                ${badge(state)}
            </div>
            ${positionBits.length ? `<div class="miniverse-agent-task">${escapeHtml(positionBits.join(' | '))}</div>` : ''}
        </article>`;
    }).join('') : '<div class="empty">No hay agentes visibles en este momento.</div>';

    const infoJson = info && Object.keys(info).length
        ? `<details class="miniverse-details">
            <summary>Ver snapshot JSON</summary>
            <div style="margin-top:10px">
                <pre class="miniverse-json">${escapeHtml(JSON.stringify(info, null, 2))}</pre>
            </div>
        </details>`
        : '<div class="empty">Sin snapshot del mundo disponible.</div>';
    const errorBanner = current.meta?.error
        ? `<div class="blocker" style="margin-bottom:0">Sincronizacion Miniverse: ${escapeHtml(current.meta.error)}</div>`
        : '';
    const staleBanner = current.meta?.stale
        ? `<div class="blocker" style="background:#fff2d8;color:#9a5b00">Mostrando cache temporal mientras vuelve el servicio.</div>`
        : '';
    const repoSummary = [
        repo.description || 'Un mundo pixelado para agentes de IA.',
        repo.homepage ? `Homepage: ${repo.homepage}` : null,
        repo.updated_at ? `Updated: ${fmtDate(repo.updated_at)}` : null,
    ].filter(Boolean).join(' ');

    return `<section class="miniverse-shell">
        <div class="miniverse-hero">
            <div class="miniverse-kicker">Miniverse</div>
            <div class="miniverse-title">${escapeHtml(repo.full_name || 'ianscott313/miniverse')}</div>
            <div class="miniverse-subtitle">${escapeHtml(repoSummary)}</div>
            <div class="miniverse-actions">
                <a class="miniverse-action primary" href="${escapeHtml(current.links?.world || world.base_url || '#')}" target="_blank" rel="noreferrer">Abrir mundo</a>
                <a class="miniverse-action" href="${escapeHtml(current.links?.repo || repo.html_url || '#')}" target="_blank" rel="noreferrer">Abrir repo</a>
                ${current.links?.docs ? `<a class="miniverse-action" href="${escapeHtml(current.links.docs)}" target="_blank" rel="noreferrer">Docs</a>` : ''}
            </div>
            <div class="miniverse-stats">
                ${statItems.map(item => `<span class="miniverse-stat">${escapeHtml(item)}</span>`).join('')}
            </div>
        </div>
        ${errorBanner}
        ${staleBanner}
        <div class="miniverse-preview-grid">
            <div class="miniverse-card">
                <div class="miniverse-card-head">
                    <div>
                        <h4>Vista del mundo</h4>
                        <p>${escapeHtml(previewCaption)}</p>
                    </div>
                    ${ui.embeddable
                        ? `<span class="badge" style="background:${STATUS_COLOR.delivered.bg};color:${STATUS_COLOR.delivered.text}">iframe</span>`
                        : `<span class="badge" style="background:${STATUS_COLOR.blocked.bg};color:${STATUS_COLOR.blocked.text}">externo</span>`}
                </div>
                ${previewPanel}
                ${previewBadges ? `<div class="miniverse-stats compact" style="margin-top:12px">${previewBadges}</div>` : ''}
            </div>
            <div class="miniverse-card">
                <div class="miniverse-card-head">
                    <div>
                        <h4>Estado del mundo</h4>
                        <p class="miniverse-url">${escapeHtml(apiUrl || current.links?.api || '')}</p>
                    </div>
                    ${onlineCount > 0 ? badge('working') : badge('idle')}
                </div>
                <div class="miniverse-stats compact">
                    ${repo.stargazers_count != null ? `<span class="miniverse-stat">${escapeHtml(String(repo.stargazers_count))} stars</span>` : ''}
                    ${repo.forks_count != null ? `<span class="miniverse-stat">${escapeHtml(String(repo.forks_count))} forks</span>` : ''}
                    ${repo.open_issues_count != null ? `<span class="miniverse-stat">${escapeHtml(String(repo.open_issues_count))} issues</span>` : ''}
                </div>
                <div class="miniverse-stats compact">
                    ${world.ui_url ? `<span class="miniverse-stat soft">UI ${escapeHtml(world.ui_url)}</span>` : ''}
                    ${apiUrl ? `<span class="miniverse-stat soft">API ${escapeHtml(apiUrl)}</span>` : ''}
                </div>
                ${infoJson}
            </div>
        </div>
        <div class="miniverse-card">
            <div class="miniverse-card-head">
                <div>
                    <h4>Agentes visibles</h4>
                    <p>${escapeHtml(agents.length ? 'Estado vivo del mundo en tiempo real.' : 'Esperando datos del mundo.')}</p>
                </div>
                <span class="badge" style="background:${STATUS_COLOR.planned.bg};color:${STATUS_COLOR.planned.text}">${escapeHtml(onlineCount + '/' + (totalCount || visibleAgentCount || 0))}</span>
            </div>
            <div class="miniverse-agent-grid">
                ${agentCards}
            </div>
        </div>
    </section>`;
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

async function fetchMiniverseSnapshot(force = false) {
    const now = Date.now();
    if (miniverseRequestPromise) return miniverseRequestPromise;
    if (!force && miniverseSnapshot && now - miniverseRequestedAt < 60000) {
        return miniverseSnapshot;
    }

    miniverseLoading = true;
    miniverseError = null;
    miniverseRequestedAt = now;
    if (activeTab === 'miniverse') renderTab();

    miniverseRequestPromise = fetch(`${API}/miniverse${force ? '?force=1' : ''}`)
        .then(async (r) => {
            const data = await r.json();
            if (!r.ok) throw new Error(data.error || data.meta?.error || 'No se pudo cargar Miniverse');
            miniverseSnapshot = data;
            return data;
        })
        .catch((e) => {
            miniverseError = e.message || 'No se pudo cargar Miniverse';
            return null;
        })
        .finally(() => {
            miniverseLoading = false;
            miniverseRequestPromise = null;
            if (activeTab === 'miniverse') renderTab();
        });

    return miniverseRequestPromise;
}

async function fetchGatewayEventsSnapshot(force = false) {
    if (!force && gatewaySnapshot?.events?.length) {
        return gatewaySnapshot;
    }
    try {
        const r = await fetch(`${API}/gateway/events?limit=200`);
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || 'No se pudieron cargar los eventos del Gateway');
        gatewaySnapshot = {
            status: data.status || {},
            events: dedupeGatewayEvents(Array.isArray(data.events) ? data.events : []),
        };
        if (activeTab === 'gateway') renderTab();
        return gatewaySnapshot;
    } catch (e) {
        gatewaySnapshot = {
            status: { connected: false, last_error: e.message || 'No se pudieron cargar los eventos del Gateway' },
            events: dedupeGatewayEvents(Array.isArray(gatewaySnapshot?.events) ? gatewaySnapshot.events : []),
        };
        if (activeTab === 'gateway') renderTab();
        return gatewaySnapshot;
    }
}

function mergeGatewayEvent(event) {
    if (!event || typeof event !== 'object') return;
    const currentEvents = dedupeGatewayEvents(Array.isArray(gatewaySnapshot.events) ? gatewaySnapshot.events : []);
    const key = gatewayEventFingerprint(event);
    const seen = new Set(currentEvents.map(gatewayEventFingerprint));
    if (seen.has(key)) return;
    currentEvents.push(event);
    gatewaySnapshot = {
        status: {
            ...(gatewaySnapshot.status || {}),
            connected: true,
            last_error: null,
            last_event_at: event.received_at || gatewaySnapshot.status?.last_event_at || null,
        },
        events: dedupeGatewayEvents(currentEvents).slice(-200),
    };
    if (activeTab === 'gateway') renderTab();
}

function updateGatewayStatus(status) {
    gatewaySnapshot = {
        status: { ...(gatewaySnapshot.status || {}), ...(status || {}) },
        events: Array.isArray(gatewaySnapshot.events) ? gatewaySnapshot.events : [],
    };
    if (activeTab === 'gateway') renderTab();
}

function startGatewayStream() {
    if (typeof WebSocket === 'undefined') {
        fetchGatewayEventsSnapshot(true);
        return;
    }

    if (gatewaySource) {
        gatewaySource.close();
    }
    if (gatewayReconnectTimer) {
        clearTimeout(gatewayReconnectTimer);
        gatewayReconnectTimer = null;
    }

    try {
        const socket = new WebSocket(gatewayWsUrl());
        gatewaySource = socket;
        socket.onopen = () => {
            if (gatewaySource !== socket) return;
            updateGatewayStatus({ connected: true, last_error: null });
        };
        socket.onmessage = (event) => {
            if (gatewaySource !== socket) return;
            try {
                const data = JSON.parse(event.data);
                if (data?.type === 'snapshot') {
                    gatewaySnapshot = {
                        status: data.snapshot?.status || {},
                        events: dedupeGatewayEvents(Array.isArray(data.snapshot?.events) ? data.snapshot.events : []),
                    };
                    if (activeTab === 'gateway') renderTab();
                    return;
                }
                if (data?.type === 'event') {
                    mergeGatewayEvent(data);
                    return;
                }
                if (data?.type === 'status') {
                    updateGatewayStatus(data.status || {});
                    return;
                }
            } catch (e) {
                console.error('No se pudo procesar un evento del Gateway', e);
            }
        };
        socket.onerror = () => {
            if (gatewaySource !== socket) return;
            updateGatewayStatus({ connected: false });
        };
        socket.onclose = () => {
            if (gatewaySource !== socket) return;
            updateGatewayStatus({ connected: false });
            gatewaySource = null;
            if (gatewayReconnectTimer) {
                clearTimeout(gatewayReconnectTimer);
            }
            gatewayReconnectTimer = setTimeout(() => {
                gatewayReconnectTimer = null;
                if (!gatewaySource) startGatewayStream();
            }, 4000);
        };
    } catch (e) {
        console.error('No se pudo abrir el WebSocket del Gateway', e);
        fetchGatewayEventsSnapshot(true);
    }
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
    return ['delivered', 'error', 'failed', 'completed'].includes(status)
        || ['delivered', 'error', 'failed', 'completed'].includes(orchestratorStatus);
}

function isActiveProject(project) {
    const status = String(project?.status || '').toLowerCase();
    const orchestratorStatus = String(project?.orchestrator?.status || '').toLowerCase();
    return ['planning', 'planned', 'executing', 'execution', 'running', 'in_progress', 'blocked', 'paused', 'error'].includes(status)
        || ['planning', 'executing', 'execution', 'running', 'working', 'in_progress', 'blocked', 'paused', 'error'].includes(orchestratorStatus);
}

function isDeletedProject(project) {
    const status = String(project?.status || '').toLowerCase();
    const orchestratorStatus = String(project?.orchestrator?.status || '').toLowerCase();
    return status === 'deleted' || orchestratorStatus === 'deleted';
}

function filteredProjects(projects) {
    if (!Array.isArray(projects)) return [];
    const visible = projects.filter(project => !isDeletedProject(project));
    if (projectsScope === 'all') return visible;
    if (projectsScope === 'finished') return visible.filter(isFinalizedProject);
    return visible.filter(isActiveProject);
}

function projectScopeLabel(scope) {
    if (scope === 'finished') return 'Finalizados';
    if (scope === 'all') return 'Todos';
    return 'Activos';
}

function setProjectsScope(scope) {
    projectsScope = scope || 'active';
    if (memory) {
        document.getElementById('projects-list').innerHTML = renderProjects(memory.projects || [], memory.project?.id);
    }
}

function filesScopeLabel(scope) {
    if (scope === 'finished') return 'Finalizados';
    if (scope === 'all') return 'Todos';
    return 'En ejecución y recuperables';
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
    const visible = filteredProjects(list);
    const toolbar = `<div class="projects-toolbar">
        <label>
            Ver proyectos
            <select onchange="setProjectsScope(this.value)">
                <option value="active"${projectsScope === 'active' ? ' selected' : ''}>Activos</option>
                <option value="finished"${projectsScope === 'finished' ? ' selected' : ''}>Finalizados</option>
                <option value="all"${projectsScope === 'all' ? ' selected' : ''}>Todos</option>
            </select>
        </label>
        <div class="hint">${escapeHtml(projectScopeLabel(projectsScope))} · eliminados ocultos</div>
    </div>`;
    if (!visible.length) return `${toolbar}<div class="empty">Sin proyectos para mostrar.</div>`;
    return `${toolbar}${visible.map(p => {
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
    }).join('')}`;
}

function renderRuntimeProcess(proc) {
    const role = proc.role || (proc.is_lock_pid ? 'lock' : 'duplicate');
    const roleLabel = role === 'primary' ? 'Primaria' : role === 'lock' ? 'Lock' : 'Duplicada';
    const elapsed = typeof proc.elapsed_sec === 'number' ? `${Math.max(0, Math.floor(proc.elapsed_sec / 60))}m ${proc.elapsed_sec % 60}s` : '';
    return `<div class="runtime-item">
        <div class="runtime-item-main">
            <div class="runtime-item-title">
                <span>P${proc.pid}</span>
                <span class="badge">${escapeHtml(roleLabel)}</span>
                ${proc.is_lock_pid ? '<span class="badge">lockfile</span>' : ''}
                ${proc.is_mem_pid ? '<span class="badge">MEMORY</span>' : ''}
            </div>
            <div class="runtime-item-meta">
                ${escapeHtml(proc.cmdline || '')}
            </div>
        </div>
        <div class="runtime-item-actions">
            ${elapsed ? `<span class="badge">${escapeHtml(elapsed)}</span>` : ''}
        </div>
    </div>`;
}

function renderRuntimePanel(snapshot) {
    const runtime = snapshot?.runtime || snapshot || {};
    const lockfile = runtime.lockfile || {};
    const project = runtime.project_orchestrator || {};
    const processes = Array.isArray(runtime.processes) ? runtime.processes : [];
    const duplicates = Array.isArray(runtime.duplicates) ? runtime.duplicates : [];
    const issues = Array.isArray(runtime.issues) ? runtime.issues : [];
    const cleanupAvailable = !!runtime.cleanup_available;
    const summaryBadges = [
        `<span class="badge">${processes.length} proceso${processes.length === 1 ? '' : 's'}</span>`,
        `<span class="badge">${duplicates.length} duplicada${duplicates.length === 1 ? '' : 's'}</span>`,
        `<span class="badge">PID lock ${lockfile.pid ?? 'N/A'}</span>`,
        project.status ? `<span class="badge">${escapeHtml(t(project.status))}</span>` : '',
    ].filter(Boolean).join('');
    const issueHtml = issues.length
        ? `<div class="runtime-issues">${issues.map(item => `<span class="badge" style="background:#FFF2D8;color:#9A5B00">${escapeHtml(item)}</span>`).join('')}</div>`
        : '<div class="empty">No hay ejecuciones duplicadas detectadas.</div>';
    const processHtml = processes.length
        ? `<div class="runtime-list">${processes.map(renderRuntimeProcess).join('')}</div>`
        : '<div class="empty">No se detectaron procesos de orquestador activos.</div>';
    return `
        <div class="runtime-panel-head">
            <div>
                <div class="section-label">Ejecuciones</div>
                <div class="sub">Detecta el proceso primario, duplicados y locks obsoletos.</div>
            </div>
            <div class="runtime-summary">${summaryBadges}</div>
        </div>
        ${issueHtml}
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <span class="badge">Primary PID ${runtime.primary_pid ?? 'N/A'}</span>
            ${cleanupAvailable ? '<button class="btn-outline" type="button" onclick="cleanupRuntimeExecutions()">Limpiar duplicados</button>' : ''}
            <button class="btn-outline" type="button" onclick="fetchRuntimeSnapshot(true)">Refrescar</button>
        </div>
        ${processHtml}
    `;
}

async function fetchRuntimeSnapshot(force = false) {
    const now = Date.now();
    if (runtimeRequestPromise) return runtimeRequestPromise;
    if (!force && runtimeSnapshot && now - runtimeRequestedAt < 5000) {
        return runtimeSnapshot;
    }
    runtimeRequestedAt = now;
    runtimeRequestPromise = fetch(`${API}/runtime/orchestrators`)
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(data.error || 'No se pudo cargar el runtime');
            runtimeSnapshot = data;
            if (document.getElementById('runtime-panel')) {
                document.getElementById('runtime-panel').innerHTML = renderRuntimePanel(data);
            }
            return data;
        })
        .catch(e => {
            runtimeSnapshot = {
                runtime: { processes: [], duplicates: [], issues: [e.message || 'No se pudo cargar el runtime'], cleanup_available: false },
            };
            if (document.getElementById('runtime-panel')) {
                document.getElementById('runtime-panel').innerHTML = renderRuntimePanel(runtimeSnapshot);
            }
            return runtimeSnapshot;
        })
        .finally(() => {
            runtimeRequestPromise = null;
        });
    return runtimeRequestPromise;
}

async function cleanupRuntimeExecutions() {
    const runtime = runtimeSnapshot?.runtime || {};
    const duplicates = Array.isArray(runtime.duplicates) ? runtime.duplicates : [];
    const lockPid = runtime.lockfile?.pid ?? 'N/A';
    const summary = duplicates.slice(0, 5).map(proc => `P${proc.pid}`).join(', ');
    const more = duplicates.length > 5 ? ` y ${duplicates.length - 5} más` : '';
    const prompt = duplicates.length
        ? `Se limpiarán ${duplicates.length} ejecución(es) duplicada(s) y se conservará la primaria.\n${summary}${more}\nLockfile actual: ${lockPid}`
        : 'Solo se limpiará el lockfile obsoleto si existe.';
    if (!confirm(prompt)) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const r = await fetch(`${API}/runtime/orchestrators/cleanup`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ mode: 'duplicates', force: true }),
        });
        const data = await r.json().catch(() => ({}));
        if (!r.ok) throw new Error(data.error || 'No se pudo limpiar el runtime');
        await fetchRuntimeSnapshot(true);
        await fetchInitialState();
    } catch (e) {
        alert(e.message || 'Error al limpiar ejecuciones duplicadas');
    }
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
            body: JSON.stringify({ pause_running: true }),
        });
        if (!r.ok) throw new Error('No se pudo pausar el proyecto');
    } catch (e) {
        alert(e.message || 'Error al pausar el proyecto');
    }
}

async function pauseTask(taskId) {
    const tasks = Array.isArray(memory?.tasks) ? memory.tasks : [];
    const task = tasks.find(t => t && t.id === taskId);
    if (!task) {
        alert('La tarea ya no está disponible.');
        return;
    }
    if (!confirm(`Pausar la tarea ${task.id} · ${task.title || ''}?`)) return;
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const r = await fetch(`${API}/project/pause`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                task_id: taskId,
                pause_running: true,
                reason: `Pausa solicitada desde la tarea ${taskId}`,
            }),
        });
        const data = await r.json();
        if (!r.ok) throw new Error(data.error || 'No se pudo pausar la tarea');
        fetchInitialState();
    } catch (e) {
        alert(e.message || 'Error al pausar la tarea');
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
        const data = await r.json().catch(() => ({}));
        if (!r.ok) throw new Error(data.error || 'No se pudo eliminar el proyecto');
        memory = null;
        filesSnapshot = null;
        selectedFilePath = null;
        selectedFilePreview = null;
        selectedFileLoading = false;
        selectedFileError = null;
        await fetchInitialState();
        await fetchFilesSnapshot(true);
        await fetchRuntimeSnapshot(true);
    } catch (e) {
        alert(e.message || 'Error al eliminar el proyecto');
    }
}

async function resumeProject(taskId = null) {
    const tasks = Array.isArray(memory?.tasks) ? memory.tasks : [];
    const resumeSet = taskId
        ? tasks.filter(t => t && t.id === taskId)
        : tasks.filter(t => t && ['error', 'pending', 'paused', 'in_progress'].includes(String(t.status || '').toLowerCase()));
    if (!resumeSet.length) {
        alert(taskId ? 'La tarea ya no está disponible para reanudación.' : 'No hay tareas fallidas o pendientes para reanudar.');
        return;
    }
    const summary = resumeSet.slice(0, 6).map(t => `${t.id} · ${t.title || ''} · ${t.status || 'pending'}`).join('\n');
    const more = resumeSet.length > 6 ? `\n... y ${resumeSet.length - 6} más` : '';
    if (!confirm(`Se reanudarán ${resumeSet.length} tarea(s):\n${summary}${more}`)) return;

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
            fetchRuntimeSnapshot(true);
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
            const runtimePanel = document.getElementById('runtime-panel');
            if (runtimePanel) runtimePanel.innerHTML = renderRuntimePanel({ runtime: { processes: [], duplicates: [], issues: ['Sin estado todavía'], cleanup_available: false } });
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
    } else if (tab === 'miniverse') {
        fetchMiniverseSnapshot();
    }
    if (tab === 'gateway') {
        fetchGatewayEventsSnapshot();
    }
}

function renderTab() {
    if (!memory) return;
    const content = document.getElementById('tab-content');
    if (activeTab === 'tasks')      content.innerHTML = renderTasks(memory.tasks||[]);
    else if (activeTab === 'log')   content.innerHTML = `<div class="log-feed">${renderLog(memory.log||[])}</div>`;
    else if (activeTab === 'gateway') content.innerHTML = renderGatewayTab();
    else if (activeTab === 'miniverse') content.innerHTML = renderMiniverse(miniverseSnapshot);
    else                            content.innerHTML = renderFiles(filesSnapshot || memory);
}

function render(mem) {
    memory = mem;
    renderAgents(mem.agents);
    renderProject(mem.project, mem.tasks||[], mem.plan||{});
    renderBlockers(mem.blockers);
    document.getElementById('projects-list').innerHTML = renderProjects(mem.projects||[], mem.project?.id);
    const runtimePanel = document.getElementById('runtime-panel');
    if (runtimePanel) runtimePanel.innerHTML = renderRuntimePanel(runtimeSnapshot || mem);
    renderTab();
    if (activeTab === 'files') {
        fetchFilesSnapshot();
    } else if (activeTab === 'miniverse') {
        fetchMiniverseSnapshot();
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
            } else if (activeTab === 'miniverse') {
                fetchMiniverseSnapshot();
            }
            fetchRuntimeSnapshot();
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
fetchGatewayEventsSnapshot(true);
fetchMiniverseSnapshot(true);
fetchRuntimeSnapshot(true);
setInterval(() => {
    fetchRuntimeSnapshot();
}, 4000);
startStream();
startGatewayStream();
</script>
</body>
</html>
