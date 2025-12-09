<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Proveedor::query();

        // Filtro por nombre (búsqueda multi-palabra)
        if ($request->filled('nombre')) {
            $palabras = explode(' ', trim($request->nombre));
            foreach ($palabras as $palabra) {
                if (!empty($palabra)) {
                    $query->where('nombre', 'like', '%' . $palabra . '%');
                }
            }
        }

        // Filtro por tipo de proveedor
        if ($request->filled('tipo_proveedor')) {
            $query->where('tipo_proveedor', 'like', '%' . $request->tipo_proveedor . '%');
        }

        // Filtro por teléfono
        if ($request->filled('telefono')) {
            $query->where('telefono', 'like', '%' . $request->telefono . '%');
        }

        $proveedores = $query->orderBy('nombre')->get();

        if ($request->ajax()) {
            return view('proveedores.partials.table_rows', compact('proveedores'))->render();
        }

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'tipo_proveedor' => 'required|string|max:100'
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return response()->json($proveedor);
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'tipo_proveedor' => 'required|string|max:100'
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
