<?php

namespace App\Http\Middleware;

use App\Models\Caja;
use Closure;
use Illuminate\Http\Request;

class CajaAbierta
{
    public function handle(Request $request, Closure $next)
    {
        if (!Caja::where('estado', 'abierta')->exists()) {
            return redirect()->route('admin.caja.index')
                ->with('error', '⚠️ Debe abrir la caja antes de registrar movimientos.');
        }

        return $next($request);
    }
}
