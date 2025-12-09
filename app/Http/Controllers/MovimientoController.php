<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = DB::table('movimientos')->orderBy('created_at', 'desc')->get();
        return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $categorias = [
            'ventas' => 'Ventas',
            'confecciones' => 'Confecciones',
            'gastos_operativos' => 'Gastos Operativos',
            'salarios' => 'Salarios',
            'insumos' => 'Insumos',
            'otros' => 'Otros'
        ];

        return view('movimientos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:50',
            'tipo' => 'required|string|in:ingreso,egreso',
            'monto' => 'nullable|numeric|min:0',
            'concepto' => 'nullable|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
            'ID_empleado' => 'nullable|exists:empleados,ID_empleado'
        ]);

        DB::table('movimientos')->insert([
            'categoria' => $request->categoria,
            'tipo' => $request->tipo,
            'monto' => $request->monto ?? 0,
            'concepto' => $request->concepto ?? null,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion ?? null,
            'ID_empleado' => $request->ID_empleado ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado correctamente.');
    }
}
