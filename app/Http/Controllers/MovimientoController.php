<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * Mostrar listado de movimientos con buscador.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');

        $movimientos = Movimiento::with('producto')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('producto', function ($p) use ($query) {
                    $p->where('nombre', 'LIKE', "%{$query}%");
                })
                ->orWhere('tipo', 'LIKE', "%{$query}%")
                ->orWhere('observacion', 'LIKE', "%{$query}%");
            })
            ->paginate(10);

        return view('movimientos.index', compact('movimientos', 'query'));
    }

    /**
     * Mostrar formulario para crear un movimiento.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('movimientos.create', compact('productos'));
    }

    /**
     * Guardar nuevo movimiento en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1|max:9999',
            'observacion' => 'nullable|string|max:255',
        ]);

        // Evitar duplicidad: mismo producto y tipo
        $exists = Movimiento::where('producto_id', $request->producto_id)
            ->where('tipo', $request->tipo)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['duplicado' => 'Ya existe un movimiento igual para este producto.'])
                ->withInput();
        }

        Movimiento::create($request->all());

        return redirect()->route('movimientos.index')
                         ->with('success', 'Movimiento registrado correctamente.');
    }

    /**
     * Mostrar formulario para editar un movimiento.
     */
    public function edit(Movimiento $movimiento)
    {
        $productos = Producto::all();
        return view('movimientos.edit', compact('movimiento', 'productos'));
    }

    /**
     * Actualizar movimiento en la base de datos.
     */
    public function update(Request $request, Movimiento $movimiento)
    {
        // Validación de datos
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1|max:9999',
            'observacion' => 'nullable|string|max:255',
        ]);

        // Evitar duplicidad en edición
        $exists = Movimiento::where('producto_id', $request->producto_id)
            ->where('tipo', $request->tipo)
            ->where('id', '<>', $movimiento->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['duplicado' => 'Ya existe un movimiento igual para este producto.'])
                ->withInput();
        }

        $movimiento->update($request->all());

        return redirect()->route('movimientos.index')
                         ->with('success', 'Movimiento actualizado correctamente.');
    }

    /**
     * Eliminar un movimiento.
     */
    public function destroy(Movimiento $movimiento)
    {
        $movimiento->delete();
        return redirect()->route('movimientos.index')
                         ->with('success', 'Movimiento eliminado correctamente.');
    }
}
