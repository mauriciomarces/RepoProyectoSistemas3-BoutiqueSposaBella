<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ==========================================
    // LOGIN DE CLIENTES (Sitio Web Público)
    // ==========================================
    
    public function showLoginCliente() {
        return view('auth.login');
    }

    public function loginCliente(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = DB::table('cliente')->where('correo', $request->correo)->first();

        if (!$user) {
            return back()->with('error', 'Correo no registrado.');
        }

        $hashedPassword = hash('sha256', $request->password);

        if ($user->password !== $hashedPassword) {
            return back()->with('error', 'Contraseña incorrecta.');
        }

        // Guardar sesión de CLIENTE
        session([
            'cliente_id' => $user->ID_cliente,
            'nombre' => $user->nombre,
            'tipo_usuario' => 'cliente'
        ]);

        return redirect('/')->with('success', 'Bienvenido ' . $user->nombre . '!');
    }

    public function logoutCliente() {
        session()->flush();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }

    // ==========================================
    // LOGIN DE EMPLEADOS (Sistema Administrativo)
    // ==========================================
    
    public function showLoginEmpleado() {
        return view('auth.login_empleado');
    }

    public function loginEmpleado(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string'
        ]);

        // Buscar en tabla empleado con su rol
        $empleado = DB::table('empleado')
            ->join('rol', 'empleado.ID_rol', '=', 'rol.ID_rol')
            ->where('empleado.correo', $request->correo)
            ->select('empleado.*', 'rol.cargo', 'rol.permisos')
            ->first();

        if (!$empleado) {
            return back()->with('error', 'Correo no registrado en el sistema.');
        }

        // Verificar contraseña (usa el mismo hash SHA256)
        $hashedPassword = hash('sha256', $request->password);

        if ($empleado->password !== $hashedPassword) {
            return back()->with('error', 'Contraseña incorrecta.');
        }

        // Verificar que sea Admin (1), Vendedora (2) o Costurera (3)
        if (!in_array($empleado->ID_rol, [1, 2, 3])) {
            return back()->with('error', 'No tienes permisos para acceder al sistema.');
        }

        // Guardar sesión de EMPLEADO
        session([
            'empleado_id' => $empleado->ID_empleado,
            'empleado_nombre' => $empleado->nombre,
            'empleado_rol_id' => $empleado->ID_rol,
            'empleado_rol_nombre' => $empleado->cargo,
            'empleado_permisos' => $empleado->permisos,
            'tipo_usuario' => 'empleado'
        ]);

        return redirect()->route('clientes.index')->with('success', 'Bienvenida ' . $empleado->nombre . '!');
    }

    public function logoutEmpleado() {
        session()->flush();
        return redirect()->route('empleado.login')->with('success', 'Has cerrado sesión correctamente.');
    }
}