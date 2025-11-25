<?php

namespace App\Http\Controllers;

use App\Models\RegistroInteraccion;
use App\Models\Empleado;
use Illuminate\Http\Request;

class RegistroInteraccionController extends Controller
{
    public function index(Request $request)
    {
        // Validate fecha_hasta to not exceed current date
        if ($request->filled('fecha_hasta') && $request->fecha_hasta > now()->toDateString()) {
            $request->merge(['fecha_hasta' => now()->toDateString()]);
        }

        $query = RegistroInteraccion::with('empleado');

        // Apply filters independently
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $registros = $query->orderBy('created_at', 'desc')->paginate(20);

        // For filter dropdowns - predefined lists for all possible modules and actions
        $empleados = Empleado::select('ID_empleado', 'nombre')->orderBy('nombre')->get();

        // All possible actions in the system
        $acciones = collect([
            'login',
            'create',
            'edit',
            'delete',
            'venta',
            'compra',
            'movimiento_financiero',
            'flete',
            'analisis_financiero'
        ]);

        // All possible modules in the system
        $modulos = collect([
            'empleados',
            'productos',
            'clientes',
            'proveedores',
            'ventas',
            'compras',
            'movimientos_financieros',
            'fletes',
            'categorias',
            'analisis_financiero',
            'trash'
        ]);

        return view('registros_interaccion.index', compact('registros', 'empleados', 'acciones', 'modulos'));
    }
}
