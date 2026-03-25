<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCsrfMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // No hacer ninguna verificación CSRF
        return $next($request);
    }
}