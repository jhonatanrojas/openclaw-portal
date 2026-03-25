<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DevSquadController extends Controller
{
    private string $apiBase = 'http://127.0.0.1:8001';
    private string $apiKey  = 'dev-squad-api-key-2026';
    private string $agentBase = '/var/www/openclaw-multi-agents';

    private function headers(): array
    {
        return ['X-API-Key' => $this->apiKey];
    }

    public function index()
    {
        return view('devsquad.dashboard');
    }

    private function state(): array
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->apiBase}/api/state");

            return $response->successful() ? ($response->json() ?: []) : [];
        } catch (\Throwable) {
            return [];
        }
    }

    private function safeRoots(array $state = []): array
    {
        $roots = [$this->agentBase];

        $projectOutput = data_get($state, 'project.output_dir');
        if (is_string($projectOutput) && $projectOutput !== '') {
            $roots[] = $projectOutput;
        }

        $workspaceRoot = $this->agentBase . '/workspaces';
        $roots[] = $workspaceRoot;

        $normalized = [];
        foreach ($roots as $root) {
            $resolved = realpath($root) ?: $root;
            if ($resolved && !in_array($resolved, $normalized, true)) {
                $normalized[] = $resolved;
            }
        }

        return $normalized;
    }

    private function resolveSafePath(string $path, array $state = []): ?string
    {
        $path = trim(str_replace("\0", '', $path));
        if ($path === '') {
            return null;
        }

        $candidate = realpath($path);
        if ($candidate === false) {
            $relative = ltrim($path, '/');
            $candidate = realpath($this->agentBase . DIRECTORY_SEPARATOR . $relative);
        }

        if ($candidate === false) {
            return null;
        }

        foreach ($this->safeRoots($state) as $root) {
            $prefix = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($candidate === $root || str_starts_with($candidate, $prefix)) {
                return $candidate;
            }
        }

        return null;
    }

    private function fileEntry(string $path, string $group, array $state = []): ?array
    {
        $full = $this->resolveSafePath($path, $state);
        if (!$full || !is_file($full)) {
            return null;
        }

        $relative = $full;
        foreach ($this->safeRoots($state) as $root) {
            $prefix = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($full === $root) {
                $relative = basename($full);
                break;
            }
            if (str_starts_with($full, $prefix)) {
                $relative = ltrim(substr($full, strlen($prefix)), DIRECTORY_SEPARATOR);
                break;
            }
        }

        return [
            'path' => $full,
            'relative' => $relative,
            'name' => basename($full),
            'group' => $group,
            'size' => filesize($full) ?: 0,
            'modified_at' => @date('c', filemtime($full) ?: time()),
            'extension' => pathinfo($full, PATHINFO_EXTENSION) ?: '',
        ];
    }

    private function listDirectoryFiles(string $root, array $state = [], int $maxDepth = 3, int $maxFiles = 200): array
    {
        $fullRoot = $this->resolveSafePath($root, $state);
        if (!$fullRoot || !is_dir($fullRoot)) {
            return [];
        }

        $entries = [];
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($fullRoot, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                if (!$item instanceof \SplFileInfo || !$item->isFile()) {
                    continue;
                }

                $path = $item->getPathname();
                if (str_contains($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR)) {
                    continue;
                }

                $relative = ltrim(substr($path, strlen(rtrim($fullRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR);
                $depth = $relative === '' ? 0 : substr_count(str_replace('\\', '/', $relative), '/');
                if ($depth >= $maxDepth) {
                    continue;
                }

                $entry = $this->fileEntry($path, 'project', $state);
                if ($entry) {
                    $entries[] = $entry;
                }

                if (count($entries) >= $maxFiles) {
                    break;
                }
            }
        } catch (\Throwable) {
            return [];
        }

        usort($entries, function (array $a, array $b): int {
            return strcmp($b['modified_at'] ?? '', $a['modified_at'] ?? '');
        });

        return $entries;
    }

    private function projectFileTrees(array $state): array
    {
        $projects = data_get($state, 'projects', []);
        if (!is_array($projects)) {
            return [];
        }

        $trees = [];
        foreach ($projects as $project) {
            if (!is_array($project)) {
                continue;
            }

            $projectId = (string) ($project['id'] ?? $project['name'] ?? 'project');
            $roots = [];
            foreach (['repo_path', 'output_dir'] as $key) {
                $candidate = $project[$key] ?? null;
                if (is_string($candidate) && $candidate !== '') {
                    $resolved = $this->resolveSafePath($candidate, $state);
                    if ($resolved && is_dir($resolved)) {
                        $roots[$resolved] = [
                            'label' => $key,
                            'path' => $resolved,
                            'files' => $this->listDirectoryFiles($resolved, $state),
                        ];
                    }
                }
            }

            $trees[] = [
                'id' => $projectId,
                'name' => (string) ($project['name'] ?? $projectId),
                'status' => (string) ($project['status'] ?? 'unknown'),
                'branch' => $project['branch'] ?? null,
                'created_at' => $project['created_at'] ?? null,
                'updated_at' => $project['updated_at'] ?? null,
                'repo_path' => $project['repo_path'] ?? null,
                'output_dir' => $project['output_dir'] ?? null,
                'roots' => array_values($roots),
                'total_files' => array_sum(array_map(fn (array $root) => count($root['files'] ?? []), array_values($roots))),
            ];
        }

        return $trees;
    }

    private function collectFileEntries(array $state): array
    {
        $paths = [
            'generated' => data_get($state, 'files_produced', []),
            'progress' => data_get($state, 'progress_files', []),
        ];

        $tasks = data_get($state, 'tasks', []);
        if (is_array($tasks)) {
            foreach ($tasks as $task) {
                if (is_array($task)) {
                    if (!empty($task['workspace_context'])) {
                        $paths['progress'][] = $task['workspace_context'];
                    }
                    if (!empty($task['progress_file'])) {
                        $paths['progress'][] = $task['progress_file'];
                    }
                }
            }
        }

        $entries = [];
        foreach ($paths as $group => $groupPaths) {
            foreach (array_unique(array_filter($groupPaths)) as $path) {
                $entry = $this->fileEntry((string) $path, $group, $state);
                if ($entry) {
                    $entries[] = $entry;
                }
            }
        }

        usort($entries, function (array $a, array $b): int {
            return strcmp($b['modified_at'] ?? '', $a['modified_at'] ?? '');
        });

        return $entries;
    }

    /**
     * Proxy SSE stream from the Dev Squad API.
     */
    public function stream(Request $request): StreamedResponse
    {
        $url = "{$this->apiBase}/api/stream";

        return response()->stream(function () use ($url): void {
            if (!function_exists('curl_init')) {
                echo "event: error\n";
                echo 'data: ' . json_encode(['error' => 'cURL no está disponible en el servidor']) . "\n\n";
                @ob_flush();
                @flush();
                return;
            }

            $ch = curl_init($url);
            if ($ch === false) {
                echo "event: error\n";
                echo 'data: ' . json_encode(['error' => 'No se pudo iniciar la conexión SSE']) . "\n\n";
                @ob_flush();
                @flush();
                return;
            }

            $headers = [
                'Accept: text/event-stream',
                'Cache-Control: no-cache',
                'X-API-Key: ' . $this->apiKey,
            ];

            curl_setopt_array($ch, [
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_WRITEFUNCTION => function ($ch, string $chunk): int {
                    echo $chunk;
                    if (function_exists('ob_flush')) {
                        @ob_flush();
                    }
                    @flush();
                    return strlen($chunk);
                },
                CURLOPT_HEADERFUNCTION => static function ($ch, string $header): int {
                    return strlen($header);
                },
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_BUFFERSIZE => 1024,
            ]);

            curl_exec($ch);

            if (curl_errno($ch)) {
                echo "event: error\n";
                echo 'data: ' . json_encode(['error' => curl_error($ch)]) . "\n\n";
                @ob_flush();
                @flush();
            }

            curl_close($ch);
        }, 200, [
            'Content-Type' => 'text/event-stream; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    // Proxy: GET /devsquad/api/{path}
    public function proxyGet(Request $request, string $path)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->get("{$this->apiBase}/api/{$path}", $request->query());

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }

    // Proxy: POST /devsquad/api/{path}
    public function proxyPost(Request $request, string $path)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(30)
                ->post("{$this->apiBase}/api/{$path}", $request->all());

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }

    // Proxy: PUT /devsquad/api/{path}
    public function proxyPut(Request $request, string $path)
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->put("{$this->apiBase}/api/{$path}", $request->all());

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }

    // Health (sin auth)
    public function health()
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiBase}/health");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 503);
        }
    }

    public function files(Request $request)
    {
        $state = $this->state();
        $entries = $this->collectFileEntries($state);
        $projectTrees = $this->projectFileTrees($state);

        return response()->json([
            'status' => 'success',
            'roots' => $this->safeRoots($state),
            'files' => $entries,
            'generated' => array_values(array_filter($entries, fn (array $entry) => $entry['group'] === 'generated')),
            'progress' => array_values(array_filter($entries, fn (array $entry) => $entry['group'] === 'progress')),
            'projects' => $projectTrees,
        ]);
    }

    public function viewFile(Request $request)
    {
        $path = $request->query('path');
        if (!$path || !is_string($path)) {
            return response()->json(['error' => 'Ruta de archivo inválida'], 400);
        }

        $state = $this->state();
        $full = $this->resolveSafePath($path, $state);
        if (!$full || !is_file($full)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $content = @file_get_contents($full);
        if ($content === false) {
            return response()->json(['error' => 'No se pudo leer el archivo'], 500);
        }

        $mime = @mime_content_type($full) ?: 'application/octet-stream';
        $previewLimit = 60000;
        $truncated = strlen($content) > $previewLimit;
        if ($truncated) {
            $content = substr($content, 0, $previewLimit) . "\n\n[contenido truncado]";
        }

        return response()->json([
            'status' => 'success',
            'file' => [
                'path' => $full,
                'name' => basename($full),
                'mime' => $mime,
                'size' => filesize($full) ?: strlen($content),
                'modified_at' => @date('c', filemtime($full) ?: time()),
                'content' => $content,
                'truncated' => $truncated,
            ],
        ]);
    }

    public function downloadFile(Request $request)
    {
        $path = $request->query('path');
        if (!$path || !is_string($path)) {
            return response()->json(['error' => 'Ruta de archivo inválida'], 400);
        }

        $full = $this->resolveSafePath($path, $this->state());
        if (!$full) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        if (!is_file($full)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        return response()->download($full, basename($full));
    }
}
