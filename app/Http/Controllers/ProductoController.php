<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // 📋 Mostrar lista de productos (con filtro de búsqueda)
    public function index(Request $request)
    {
        $query = $request->input('search');

        $productos = Producto::with('categoria')
            ->when($query, function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhereHas('categoria', function ($cat) use ($query) {
                      $cat->where('nombre', 'LIKE', "%{$query}%");
                  });
            })
            ->paginate(10);

        return view('productos.index', compact('productos', 'query'));
    }

    // ➕ Formulario para crear producto
    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    // 💾 Guardar producto nuevo
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:productos,nombre',
            'categoria_id' => 'nullable|exists:categorias,id',
            'cantidad' => 'required|integer|min:0|max:9999', // máximo 4 cifras
            'precio_unitario' => 'required|numeric|min:0|max:999999.99', // máximo 6 cifras
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')
                         ->with('success', 'Producto agregado correctamente.');
    }

    // ✏️ Formulario para editar producto
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    // 🔄 Actualizar producto existente
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|unique:productos,nombre,' . $producto->id,
            'categoria_id' => 'nullable|exists:categorias,id',
            'cantidad' => 'required|integer|min:0|max:9999',
            'precio_unitario' => 'required|numeric|min:0|max:999999.99',
        ]);

        $producto->update($request->all());
        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    // 🗑️ Eliminar producto
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado correctamente.');
    }

    // ⚠️ Vista de productos con stock bajo
    public function bajoStock()
    {
        $productos = Producto::whereColumn('cantidad', '<=', 'stock_minimo')->get();
        return view('productos.bajoStock', compact('productos'));
    }
}
