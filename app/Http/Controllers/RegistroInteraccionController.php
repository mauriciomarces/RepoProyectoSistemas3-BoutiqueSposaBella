<?php

namespace App\Http\Controllers;

use App\Models\RegistroInteraccion;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Models\Seccion;
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

        // For filter dropdowns - predefined lists for all possible modules and actions, translated
        $empleados = Empleado::select('ID_empleado', 'nombre')->orderBy('nombre')->get();

        // All possible actions in the system with user-friendly translated labels
        $acciones = [
            'login' => 'Ingreso',
            'create' => 'Creado',
            'edit' => 'Editado',
            'delete' => 'Eliminado',
            'venta' => 'Venta',
            'compra' => 'Compra',
            'movimiento_financiero' => 'Movimiento Financiero',
            'flete' => 'Flete',
            'analisis_financiero' => 'Análisis Financiero'
        ];

        // All possible modules in the system with translated labels
        $modulos = [
            'empleados' => 'Empleados',
            'productos' => 'Productos',
            'clientes' => 'Clientes',
            'proveedores' => 'Proveedores',
            'ventas' => 'Ventas',
            'compras' => 'Compras',
            'movimientos_financieros' => 'Movimientos Financieros',
            'fletes' => 'Fletes',
            'categorias' => 'Categorías',
            'analisis_financiero' => 'Análisis Financiero',
            'trash' => 'Papelera'
        ];

        // Get lookup data for foreign keys
        $roles = Rol::all()->keyBy('ID_rol');
        $sucursales = Sucursal::all()->keyBy('ID_sucursal');
        $secciones = Seccion::all()->keyBy('ID_seccion');

        return view('registros_interaccion.index', compact('registros', 'empleados', 'acciones', 'modulos', 'roles', 'sucursales', 'secciones'));
    }

    public function printReport(Request $request)
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

        $registros = $query->orderBy('created_at', 'desc')->get();

        // For filter dropdowns - predefined lists for all possible modules and actions, translated
        $empleados = Empleado::select('ID_empleado', 'nombre')->orderBy('nombre')->get();

        // All possible actions in the system with user-friendly translated labels
        $acciones = [
            'login' => 'Ingreso',
            'create' => 'Creado',
            'edit' => 'Editado',
            'delete' => 'Eliminado',
            'venta' => 'Venta',
            'compra' => 'Compra',
            'movimiento_financiero' => 'Movimiento Financiero',
            'flete' => 'Flete',
            'analisis_financiero' => 'Análisis Financiero'
        ];

        // All possible modules in the system with translated labels
        $modulos = [
            'empleados' => 'Empleados',
            'productos' => 'Productos',
            'clientes' => 'Clientes',
            'proveedores' => 'Proveedores',
            'ventas' => 'Ventas',
            'compras' => 'Compras',
            'movimientos_financieros' => 'Movimientos Financieros',
            'fletes' => 'Fletes',
            'categorias' => 'Categorías',
            'analisis_financiero' => 'Análisis Financiero',
            'trash' => 'Papelera'
        ];

        $data = [
            'registros' => $registros,
            'empleados' => $empleados,
            'acciones' => $acciones,
            'modulos' => $modulos,
            'filtros' => $request->only(['empleado_id', 'accion', 'modulo', 'fecha_desde', 'fecha_hasta'])
        ];

        // Renderizar la vista PDF-friendly en el navegador (limpia, sin barra de navegación)
        return view('registros_interaccion.report', $data);
    }
}
