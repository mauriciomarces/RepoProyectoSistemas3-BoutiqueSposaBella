<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with('producto')->get();
        return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $productos = Producto::all();
        return view('movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'observacion' => 'nullable|string'
        ]);

        $movimiento = Movimiento::create($request->all());

        // Actualizar cantidad del producto
        $producto = $movimiento->producto;
        if ($movimiento->tipo == 'entrada') {
            $producto->cantidad += $movimiento->cantidad;
        } else {
            $producto->cantidad -= $movimiento->cantidad;
        }
        $producto->save();

        return redirect()->route('movimientos.index');
    }
}
