<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Consulta inicial: productos visibles
        $query = DB::table('producto')->whereNotNull('categoria');

        // Aplicar filtros
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
            $productoArray = [
                'id' => $prod->ID_producto,
                'nombre' => $prod->nombre,
                'descripcion' => $prod->descripcion,
                'descripcion_corta' => strlen($prod->descripcion) > 50 ? substr($prod->descripcion, 0, 50) . '...' : $prod->descripcion,
                'cantidad' => $prod->stock,
                'estado' => $prod->stock > 0 ? 'Disponible' : 'Vendido',
                'categoria' => $prod->categoria ?? 'Sin categoría',
                'precio' => isset($prod->precio) ? $prod->precio : 0,
                'imagen' => (!empty($prod->imagen) && file_exists(public_path('images/productos/' . $prod->imagen))) 
                            ? asset('images/productos/' . $prod->imagen) 
                            : asset('images/productos/default.png')
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
