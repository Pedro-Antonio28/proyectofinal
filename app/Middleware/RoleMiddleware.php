<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Verifica si el usuario está autenticado
        if (!$request->user()) {
            abort(403, 'Acceso denegado: No autenticado.');
        }

        // Verifica si el usuario tiene el rol necesario
        if (!$request->user()->hasRole($role)) {
            abort(403, 'Acceso denegado: No tienes permisos.');
        }

        // Permite continuar con la solicitud
        return $next($request);
    }
}
