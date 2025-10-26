<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployee
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si hay sesión de empleado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debes iniciar sesión como empleado para acceder.');
        }

        // Verificar que el rol sea válido (1=Admin, 2=Vendedor, 3=Costurera)
        $rolId = session('empleado_rol_id');
        if (!in_array($rolId, [1, 2, 3])) {
            session()->flush();
            return redirect()->route('empleado.login')
                ->with('error', 'No tienes permisos para acceder.');
        }

        return $next($request);
    }
}