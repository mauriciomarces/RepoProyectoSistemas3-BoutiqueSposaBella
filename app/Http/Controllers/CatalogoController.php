<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Use Eloquent to fetch productos (so we can use accessor imagen_data)
        $query = Producto::query();

        if ($request->filled('seccion')) {
            $query->where('categoria', $request->input('seccion'));
        }
        if ($request->filled('estado')) {
            if ($request->input('estado') === 'disponible') {
                $query->where('stock', '>', 0);
            } elseif ($request->input('estado') === 'vendido') {
                $query->where('stock', '<=', 0);
            }
        }

        $productos = $query->get();

        $sections = [];

        foreach ($productos as $prod) {
            $descripcion = $prod->descripcion ?? '';
            $productoArray = [
                'id' => $prod->ID_producto,
                'nombre' => $prod->nombre,
                'descripcion' => $descripcion,
                'descripcion_corta' => strlen($descripcion) > 50 ? substr($descripcion, 0, 50) . '...' : $descripcion,
                'cantidad' => $prod->stock,
                'estado' => $prod->stock > 0 ? 'Disponible' : 'Vendido',
                'categoria' => $prod->categoria ?? 'Sin categoría',
                'precio' => isset($prod->precio) ? $prod->precio : 0,
                'imagen' => $prod->imagen_data // accessor in model
            ];

            $categoria = $productoArray['categoria'];
            if (!isset($sections[$categoria])) {
                $sections[$categoria] = [];
            }
            $sections[$categoria][] = $productoArray;
        }

        ksort($sections);

        if ($request->ajax()) {
            return view('catalogo-partial', compact('sections'))->render();
        }

        return view('catalogo', compact('sections'));
    }
}
