<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DevSquadController extends Controller
{
    private string $apiBase = 'http://127.0.0.1:8001';
    private string $apiKey  = 'dev-squad-api-key-2026';

    private function headers(): array
    {
        return ['X-API-Key' => $this->apiKey];
    }

    public function index()
    {
        return view('devsquad.dashboard');
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
}
