<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroController extends Controller
{
    // Mostrar formulario
    public function mostrarFormulario() {
        return view('auth.registro');
    }

    // Guardar nuevo usuario
    public function registrar(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:cliente,correo',
            'telefono' => 'required|digits_between:8,9|regex:/^[67]/',
            'direccion' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);

        $hashedPassword = hash('sha256', $request->password);

        $id = DB::table('cliente')->insertGetId([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'password' => $hashedPassword
        ]);

        // Guardar sesión automáticamente después del registro
        session([
            'cliente_id' => $id,
            'nombre' => $request->nombre
        ]);

        return redirect()->route('welcome')->with('success', 'Bienvenido ' . $request->nombre . '!');
    }

}
