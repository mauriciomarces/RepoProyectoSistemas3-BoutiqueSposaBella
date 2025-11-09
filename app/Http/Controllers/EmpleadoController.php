<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index()
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        return view('empleados.create');
    }

    public function store(Request $request)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:empleado,correo',
            'password' => 'required|string|min:8',
            'puesto' => 'required|string|max:100',
            'ID_rol' => 'required|integer',
            'ID_sucursal' => 'required|integer',
            'ID_seccion' => 'required|integer',
        ]);

        Empleado::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'puesto' => $request->puesto,
            'ID_rol' => $request->ID_rol,
            'ID_sucursal' => $request->ID_sucursal,
            'ID_seccion' => $request->ID_seccion,
        ]);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function edit($id)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:empleado,correo,' . $id . ',ID_empleado',
            'password' => 'nullable|string|min:8',
            'puesto' => 'required|string|max:100',
            'ID_rol' => 'required|integer',
            'ID_sucursal' => 'required|integer',
            'ID_seccion' => 'required|integer',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'puesto' => $request->puesto,
            'ID_rol' => $request->ID_rol,
            'ID_sucursal' => $request->ID_sucursal,
            'ID_seccion' => $request->ID_seccion,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $empleado->update($data);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Verificar si el empleado está autenticado
        if (!session()->has('empleado_id')) {
            return redirect()->route('empleado.login')
                ->with('error', 'Debe iniciar sesión como empleado para acceder a esta sección.');
        }

        // Verificar que sea administrador (ID_rol == 1)
        $empleadoId = session('empleado_id');
        $empleado = \DB::table('empleado')->where('ID_empleado', $empleadoId)->first();

        if (!$empleado || $empleado->ID_rol != 1) {
            abort(403, 'Acceso no autorizado. Solo los administradores pueden gestionar empleados.');
        }

        $empleado = Empleado::findOrFail($id);

        // Verificar que no se esté eliminando a sí mismo
        if ($id == session('empleado_id')) {
            return redirect()->route('empleados.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
