<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiStateless
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Deshabilitar sesiones para API
        config(['session.driver' => 'array']);
        
        // Asegurar que no se use CSRF para API
        if ($request->is('api/*')) {
            $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        }
        
        return $next($request);
    }
}
