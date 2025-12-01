<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Models\Seccion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

        $empleados = Empleado::with(['rol', 'sucursal', 'seccion'])->get();
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

        $roles = Rol::all();
        $sucursales = Sucursal::all();
        $secciones = Seccion::all();
        return view('empleados.create', compact('roles', 'sucursales', 'secciones'));
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
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'CI' => 'required|string|max:20|unique:empleado,CI',
            'puesto' => 'required|string|max:100',
            'experiencia' => 'nullable|string|max:255',
            'fecha_contratacion' => 'required|date',
            'salario' => 'required|numeric|min:0',
            'correo' => 'required|email|unique:empleado,correo',
            'password' => 'required|string|min:8',
            'ID_rol' => 'required|exists:rol,ID_rol',
            'ID_sucursal' => 'required|exists:sucursal,ID_sucursal',
            'ID_seccion' => 'required|exists:seccion,ID_seccion',
        ]);

        Empleado::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'CI' => $request->CI,
            'puesto' => $request->puesto,
            'experiencia' => $request->experiencia,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
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
        $roles = Rol::all();
        $sucursales = Sucursal::all();
        $secciones = Seccion::all();
        return view('empleados.edit', compact('empleado', 'roles', 'sucursales', 'secciones'));
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
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'CI' => 'required|string|max:20|unique:empleado,CI,' . $id . ',ID_empleado',
            'puesto' => 'required|string|max:100',
            'experiencia' => 'nullable|string|max:255',
            'fecha_contratacion' => 'required|date',
            'salario' => 'required|numeric|min:0',
            'correo' => 'required|email|unique:empleado,correo,' . $id . ',ID_empleado',
            'password' => 'nullable|string|min:8',
            'ID_rol' => 'required|exists:rol,ID_rol',
            'ID_sucursal' => 'required|exists:sucursal,ID_sucursal',
            'ID_seccion' => 'required|exists:seccion,ID_seccion',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'CI' => $request->CI,
            'puesto' => $request->puesto,
            'experiencia' => $request->experiencia,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'correo' => $request->correo,
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
