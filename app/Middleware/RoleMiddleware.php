<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            Log::error('Middleware Role: Usuario no autenticado');
            abort(403, 'Acceso denegado: No autenticado.');
        }

        if (!$request->user()->hasRole($role)) {
            Log::error('Middleware Role: Usuario ' . $request->user()->email . ' no tiene el rol ' . $role);
            abort(403, 'Acceso denegado: No tienes permisos.');
        }

        Log::info('Middleware Role: Usuario ' . $request->user()->email . ' tiene acceso.');

        return $next($request);
    }
}
