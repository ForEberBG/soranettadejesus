<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();

        // Validar que el usuario exista y tenga el permiso usando el guard correcto
        if (!$user || !$user->hasPermissionTo($permission, 'web')) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
