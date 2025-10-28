<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
public function index()
{
    $productos = Producto::with('proveedor')->get();
    
    // Calcular alertas - CORREGIDO
    $productosAgotados = $productos->where('stock', 0)->count();
    
    // CORRECCIÓN: Productos con stock bajo (mayor que 0 pero menor o igual al mínimo)
    $productosBajos = $productos->filter(function($producto) {
        return $producto->stock > 0 && $producto->stock <= $producto->stock_minimo;
    })->count();
    
    return view('productos.index', compact('productos', 'productosAgotados', 'productosBajos'));
}

    public function create()
    {
        $proveedores = Proveedor::all();
        $categorias = ['Gala', 'Casual', 'Fiesta', 'Trabajo', 'Novia'];
        return view('productos.create', compact('proveedores', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'nombre' => 'required|string|max:100',
    'descripcion_corta' => 'required|string|max:255',
    'descripcion' => 'nullable|string',
    'categoria' => 'required|string',
    'precio' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'stock_minimo' => 'required|integer|min:0', // AGREGAR ESTA LÍNEA
    'ID_proveedor' => 'required|exists:proveedor,ID_proveedor',
    'imagen' => 'nullable|string|max:255'
]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $proveedores = Proveedor::all();
        $categorias = ['Gala', 'Casual', 'Fiesta', 'Trabajo', 'Novia'];
        return view('productos.edit', compact('producto', 'proveedores', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
    'nombre' => 'required|string|max:100',
    'descripcion_corta' => 'required|string|max:255',
    'descripcion' => 'nullable|string',
    'categoria' => 'required|string',
    'precio' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'stock_minimo' => 'required|integer|min:0', // AGREGAR ESTA LÍNEA
    'ID_proveedor' => 'required|exists:proveedor,ID_proveedor',
    'imagen' => 'nullable|string|max:255'
]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}