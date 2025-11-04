<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfeccionController extends Controller
{
    public function index()
    {
        $confecciones = DB::table('confeccion')->orderBy('fecha_inicio', 'desc')->get();
        return view('confecciones.index', compact('confecciones'));
    }

    public function create()
    {
        $clientes = DB::table('cliente')->orderBy('nombre')->get();
        return view('confecciones.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ID_cliente' => 'required|integer',
            'tipo_confeccion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_entrega' => 'nullable|date|after_or_equal:fecha_inicio',
            'costo' => 'nullable|numeric|min:0',
            'medidas' => 'required|array'
        ]);

        DB::table('confeccion')->insert([
            'tipo_confeccion' => $validated['tipo_confeccion'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_entrega' => $validated['fecha_entrega'] ?? null,
            'estado' => 'pendiente',
            'costo' => $validated['costo'] ?? 0,
            'medidas' => json_encode($validated['medidas']),
            'ID_transaccion' => null
        ]);

        return redirect()->route('confecciones.index')->with('success', 'Confección registrada correctamente');
    }
}
