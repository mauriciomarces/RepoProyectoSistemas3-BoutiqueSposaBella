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
        // El login de clientes ya no está disponible; redirigimos al login de empleados
        return redirect()->route('empleado.login');
    }

    public function loginCliente(Request $request) {
        // El login de clientes ya no está activo. Redirigir al login de empleados.
        return redirect()->route('empleado.login')->with('error', 'El acceso mediante login de clientes ya no está disponible.');
    }

    public function logoutCliente() {
        session()->flush();
        // Redirigir al login de empleados (el antiguo login de clientes ya no existe)
        return redirect()->route('empleado.login')->with('success', 'Has cerrado sesión correctamente.');
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

        // Log the login event
        \App\Models\RegistroInteraccion::create([
            'empleado_id' => $empleado->ID_empleado,
            'accion' => 'login',
            'modulo' => 'auth',
            'registro_id' => null,
            'descripcion' => 'Empleado inició sesión',
            'datos_anteriores' => null,
            'datos_nuevos' => null,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Bienvenida ' . $empleado->nombre . '!');
    }

    public function logoutEmpleado() {
        session()->flush();
        return redirect()->route('empleado.login')->with('success', 'Has cerrado sesión correctamente.')->header('Cache-Control', 'no-cache, no-store, must-revalidate')->header('Pragma', 'no-cache')->header('Expires', '0');
    }
}
