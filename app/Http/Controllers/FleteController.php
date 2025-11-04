<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FleteController extends Controller
{
    public function index()
    {
        $fletes = DB::table('fletes')->orderBy('created_at', 'desc')->get();
        return view('fletes.index', compact('fletes'));
    }

    public function create()
    {
        return view('fletes.create');
    }

    public function edit($id)
    {
        $flete = DB::table('fletes')->where('id', $id)->first();
        if (!$flete) {
            return redirect()->route('fletes.index')->with('error', 'Flete no encontrado.');
        }
        return view('fletes.edit', compact('flete'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'destinatario' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20'
        ]);

        DB::table('fletes')->insert([
            'direccion' => $request->direccion,
            'destinatario' => $request->destinatario,
            'telefono' => $request->telefono ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('fletes.index')->with('success', 'Flete registrado correctamente.');
    }

    public function destroy($id)
    {
        DB::table('fletes')->where('id', $id)->delete();
        return redirect()->route('fletes.index')->with('success', 'Flete eliminado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'destinatario' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20'
        ]);

        DB::table('fletes')->where('id', $id)->update([
            'direccion' => $request->direccion,
            'destinatario' => $request->destinatario,
            'telefono' => $request->telefono ?? null,
            'updated_at' => now()
        ]);

        return redirect()->route('fletes.index')->with('success', 'Flete actualizado correctamente.');
    }
}
