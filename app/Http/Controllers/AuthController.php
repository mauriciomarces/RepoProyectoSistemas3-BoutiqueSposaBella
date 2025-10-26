<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // Validación básica
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar usuario solo por correo primero
        $user = DB::table('cliente')->where('correo', $request->correo)->first();

        if (!$user) {
            // Si no existe el correo
            return back()->with('error', 'Correo no registrado.');
        }

        // Verificar contraseña
        $hashedPassword = hash('sha256', $request->password);

        if ($user->password !== $hashedPassword) {
            return back()->with('error', 'Contraseña incorrecta.');
        }

        // Guardar sesión
        session([
            'cliente_id' => $user->ID_cliente,
            'nombre' => $user->nombre
        ]);

        return redirect('/')->with('success', 'Bienvenido ' . $user->nombre . '!');
    }

    public function logout() {
        session()->flush();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
