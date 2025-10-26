<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = json_decode(Storage::get('employees.json'), true);
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $employees = json_decode(Storage::get('employees.json'), true);

        $newEmployee = [
            'id' => count($employees) + 1,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono,
            'rol' => $request->rol, // admin, vendedor, costurero
        ];

        $employees[] = $newEmployee;
        Storage::put('employees.json', json_encode($employees, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Empleado agregado correctamente', 'data' => $newEmployee], 201);
    }

    public function destroy($id)
    {
        $employees = json_decode(Storage::get('employees.json'), true);
        $employees = array_filter($employees, fn($e) => $e['id'] != $id);
        Storage::put('employees.json', json_encode(array_values($employees), JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Empleado eliminado correctamente']);
    }
}
