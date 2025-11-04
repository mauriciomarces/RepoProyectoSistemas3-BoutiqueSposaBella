<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckEmployee
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        return $next($request);
    }
}