<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class JudgeMiddleware
{
    public function handle($request, Closure $next)
    {
        // Usuario debe estar autenticado y tener is_admin = 2
        if (!Auth::check() || Auth::user()->is_admin = 2) {
            return redirect()->route('login')->with('error', 'No tienes permiso para acceder.');
        }

        return $next($request);
    }
}
