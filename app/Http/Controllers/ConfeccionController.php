<?php

namespace App\Http\Controllers;

use App\Models\Confeccion;
use App\Models\Cliente;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConfeccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Confeccion::with('cliente');

        // Búsqueda general
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('cliente', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            })->orWhere('tipo_confeccion', 'like', "%{$search}%");
        }

        $confecciones = $query->orderBy('fecha_inicio', 'desc')->get();

        if ($request->ajax()) {
            return view('confecciones.partials.table_rows', compact('confecciones'));
        }

        return view('confecciones.index', compact('confecciones'));
    }

    public function create()
    {
        // No pasamos clientes porque usaremos búsqueda AJAX como en Fletes
        return view('confecciones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ID_cliente' => 'required|integer|exists:cliente,ID_cliente',
            'tipo_confeccion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'nullable|date|after_or_equal:fecha_inicio',
            'costo' => 'nullable|numeric|min:0',
            'medidas' => 'required|array'
        ]);

        $confeccion = new Confeccion();
        $confeccion->ID_cliente = $validated['ID_cliente'];
        $confeccion->tipo_confeccion = $validated['tipo_confeccion'];
        $confeccion->fecha_inicio = $validated['fecha_inicio'];
        $confeccion->fecha_entrega = $validated['fecha_entrega'] ?? null;
        $confeccion->costo = $validated['costo'] ?? 0;
        $confeccion->estado = 'pendiente';
        $confeccion->medidas = $validated['medidas']; // Cast handling in model
        $confeccion->save();

        return redirect()->route('confecciones.index')
            ->with('success', 'Confección registrada correctamente');
    }

    public function edit($id)
    {
        $confeccion = Confeccion::with('cliente')->findOrFail($id);
        return view('confecciones.edit', compact('confeccion'));
    }

    public function update(Request $request, $id)
    {
        $confeccion = Confeccion::findOrFail($id);
        $old_estado = $confeccion->estado;

        $validated = $request->validate([
            'ID_cliente' => 'required|integer|exists:cliente,ID_cliente',
            'tipo_confeccion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'nullable|date|after_or_equal:fecha_inicio',
            'costo' => 'nullable|numeric|min:0',
            'medidas' => 'required|array',
            'estado' => 'required|string|in:pendiente,en_proceso,completado,cancelado'
        ]);

        $confeccion->fill($validated);
        $confeccion->save();

        // Lógica financiera por cambio de estado
        if ($old_estado !== $confeccion->estado) {
            $this->handleFinancialTransaction($confeccion);
        }

        return redirect()->route('confecciones.index')
            ->with('success', 'Confección actualizada correctamente');
    }

    public function show($id)
    {
        $confeccion = Confeccion::with(['cliente', 'movimientoFinanciero'])->findOrFail($id);
        if (request()->ajax()) {
            return response()->json($confeccion);
        }
        return view('confecciones.show', compact('confeccion'));
    }

    public function destroy($id)
    {
        $confeccion = Confeccion::findOrFail($id);

        // Si tiene transacción financiera asociada, ¿la eliminamos o la mantenemos?
        // Por integridad, si se elimina la confección, se debería revisar la transacción.
        // Por ahora, solo eliminamos la confección.

        $confeccion->delete();

        return redirect()->route('confecciones.index')
            ->with('success', 'Confección eliminada correctamente');
    }

    private function handleFinancialTransaction(Confeccion $confeccion)
    {
        // Si ya tiene transacción, no creamos otra (o deberíamos anularla? Asumimos flujo simple por ahora)
        if ($confeccion->ID_transaccion) {
            return;
        }

        $empleado_id = Auth::check() ? Auth::user()->ID_empleado : null; // Asignar al usuario actual si es posible

        if ($confeccion->estado === 'completado') {
            // Registrar INGRESO
            $movimiento = new MovimientoFinanciero();
            $movimiento->tipo = 'ingreso';
            $movimiento->monto = $confeccion->costo;
            $movimiento->concepto = 'Pago por Confección #' . $confeccion->ID_confeccion;
            $movimiento->descripcion = 'Ingreso generado por confección terminada: ' . $confeccion->tipo_confeccion;
            $movimiento->fecha = now();
            $movimiento->categoria = 'Ventas'; // O Confecciones
            $movimiento->referencia = 'CONF-' . $confeccion->ID_confeccion;
            $movimiento->ID_empleado = $empleado_id;
            $movimiento->save();

            $confeccion->ID_transaccion = $movimiento->id;
            $confeccion->save();
        } elseif ($confeccion->estado === 'cancelado') {
            // Registrar PÉRDIDA/GASTO
            // El usuario dijo: "si se pone como cancelado, pues que se registre como una pérdida"
            $movimiento = new MovimientoFinanciero();
            $movimiento->tipo = 'egreso';
            $movimiento->monto = $confeccion->costo; // Asumimos que el costo es lo que se invirtió y se perdió
            $movimiento->concepto = 'Pérdida por Cancelación de Confección #' . $confeccion->ID_confeccion;
            $movimiento->descripcion = 'Pérdida registrada por cancelación de trabajo: ' . $confeccion->tipo_confeccion;
            $movimiento->fecha = now();
            $movimiento->categoria = 'Pérdidas';
            $movimiento->referencia = 'CONF-CANCEL-' . $confeccion->ID_confeccion;
            $movimiento->ID_empleado = $empleado_id;
            $movimiento->save();

            $confeccion->ID_transaccion = $movimiento->id;
            $confeccion->save();
        }
    }
}
