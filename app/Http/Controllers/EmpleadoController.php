<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
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
        $empleado = Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
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
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
