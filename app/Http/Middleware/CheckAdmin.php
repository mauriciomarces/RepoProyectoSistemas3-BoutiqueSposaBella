<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        return $next($request);
    }
}
