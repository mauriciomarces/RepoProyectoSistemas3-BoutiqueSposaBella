<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\DeviceHelper;
use App\Models\Empleado;
use App\Models\RegistroInteraccion;

class AuthController extends Controller
{
    // ==========================================
    // LOGIN DE CLIENTES (Sitio Web Público)
    // ==========================================

    public function showLoginCliente()
    {
        // El login de clientes ya no está disponible; redirigimos al login de empleados
        return redirect()->route('empleado.login');
    }

    public function loginCliente(Request $request)
    {
        // El login de clientes ya no está activo. Redirigir al login de empleados.
        return redirect()->route('empleado.login')->with('error', 'El acceso mediante login de clientes ya no está disponible.');
    }

    public function logoutCliente()
    {
        session()->flush();
        // Redirigir al login de empleados (el antiguo login de clientes ya no existe)
        return redirect()->route('empleado.login')->with('success', 'Has cerrado sesión correctamente.');
    }

    // ==========================================
    // LOGIN DE EMPLEADOS (Sistema Administrativo)
    // ==========================================

    public function showLoginEmpleado()
    {
        return view('auth.login_empleado');
    }

    public function loginEmpleado(Request $request)
    {
        // Enhanced input validation
        $request->validate([
            'correo' => 'required|email|max:255',
            'password' => 'required|string|min:1|max:255'
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Por favor, introduce una dirección de correo válida.',
            'correo.max' => 'El correo no puede tener más de 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser texto.',
            'password.min' => 'La contraseña debe tener al menos 1 carácter.',
            'password.max' => 'La contraseña no puede tener más de 255 caracteres.',
        ]);

        // Sanitize email input
        $correo = filter_var($request->correo, FILTER_SANITIZE_EMAIL);

        // Use Eloquent ORM with eager loading for better security
        $empleado = Empleado::with('rol')
            ->where('correo', $correo)
            ->first();

        // Generic error message to prevent user enumeration
        $genericError = 'Credenciales inválidas. Por favor, verifica tu correo y contraseña.';

        if (!$empleado) {
            // Log failed login attempt - email not found
            RegistroInteraccion::create([
                'empleado_id' => null,
                'ID_dispositivo' => DeviceHelper::getDeviceId(),
                'accion' => 'login_failed',
                'modulo' => 'auth',
                'registro_id' => null,
                'descripcion' => 'Intento de login fallido: correo no registrado (' . $correo . ')',
                'datos_anteriores' => null,
                'datos_nuevos' => ['correo' => $correo, 'ip' => $request->ip()],
            ]);

            return back()->with('error', $genericError);
        }

        // Verify password using SHA256 hash
        $hashedPassword = hash('sha256', $request->password);

        if ($empleado->password !== $hashedPassword) {
            // Log failed login attempt - wrong password
            RegistroInteraccion::create([
                'empleado_id' => $empleado->ID_empleado,
                'ID_dispositivo' => DeviceHelper::getDeviceId(),
                'accion' => 'login_failed',
                'modulo' => 'auth',
                'registro_id' => null,
                'descripcion' => 'Intento de login fallido: contraseña incorrecta',
                'datos_anteriores' => null,
                'datos_nuevos' => ['correo' => $correo, 'ip' => $request->ip()],
            ]);

            return back()->with('error', $genericError);
        }

        // Verify role permissions (Admin=1, Vendedora=2, Costurera=3)
        if (!in_array($empleado->ID_rol, [1, 2, 3])) {
            // Log failed login attempt - insufficient permissions
            RegistroInteraccion::create([
                'empleado_id' => $empleado->ID_empleado,
                'ID_dispositivo' => DeviceHelper::getDeviceId(),
                'accion' => 'login_failed',
                'modulo' => 'auth',
                'registro_id' => null,
                'descripcion' => 'Intento de login fallido: permisos insuficientes (Rol: ' . $empleado->ID_rol . ')',
                'datos_anteriores' => null,
                'datos_nuevos' => ['correo' => $correo, 'rol_id' => $empleado->ID_rol],
            ]);

            return back()->with('error', 'No tienes permisos para acceder al sistema.');
        }

        // Create employee session
        session([
            'empleado_id' => $empleado->ID_empleado,
            'empleado_nombre' => $empleado->nombre,
            'empleado_rol_id' => $empleado->ID_rol,
            'empleado_rol_nombre' => $empleado->rol->cargo,
            'empleado_permisos' => $empleado->rol->permisos,
            'tipo_usuario' => 'empleado'
        ]);

        // Log successful login
        RegistroInteraccion::create([
            'empleado_id' => $empleado->ID_empleado,
            'ID_dispositivo' => DeviceHelper::getDeviceId(),
            'accion' => 'login',
            'modulo' => 'auth',
            'registro_id' => null,
            'descripcion' => 'Empleado inició sesión exitosamente',
            'datos_anteriores' => null,
            'datos_nuevos' => ['ip' => $request->ip()],
        ]);

        return redirect()->route('clientes.index')->with('success', 'Bienvenida ' . $empleado->nombre . '!');
    }

    public function logoutEmpleado()
    {
        session()->flush();
        return redirect()->route('empleado.login')->with('success', 'Has cerrado sesión correctamente.')->header('Cache-Control', 'no-cache, no-store, must-revalidate')->header('Pragma', 'no-cache')->header('Expires', '0');
    }
}
