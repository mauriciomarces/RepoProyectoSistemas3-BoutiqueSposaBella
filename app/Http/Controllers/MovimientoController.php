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
        return view('movimientos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'descripcion' => 'nullable|string'
        ]);

        DB::table('movimientos')->insert([
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado correctamente.');
    }
}
