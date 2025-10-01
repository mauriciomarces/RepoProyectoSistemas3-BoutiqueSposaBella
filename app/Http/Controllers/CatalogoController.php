<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogoController extends Controller
{
    private function getProducts()
    {
        $json = Storage::get('products.json');
        return json_decode($json, true);
    }

    public function index(Request $request)
    {
        $products = $this->getProducts();
        
        // Aplicar filtros
        if ($request->filled('seccion')) {
            $products = array_filter($products, function($product) use ($request) {
                return $product['seccion'] === $request->seccion;
            });
        }
        
        if ($request->filled('estado')) {
            $products = array_filter($products, function($product) use ($request) {
                return $product['estado'] === $request->estado;
            });
        }
        
        if ($request->filled('stock')) {
            $products = array_filter($products, function($product) use ($request) {
                return $request->stock === 'available' ? $product['cantidad'] > 0 : $product['cantidad'] === 0;
            });
        }

        $sections = [];
        foreach($products as $product){
            $sections[$product['seccion']][] = $product;
        }

        if ($request->ajax()) {
            return view('catalogo-partial', compact('sections'));
        }

        return view('catalogo', compact('sections'));
    }
    
    public function show($id)
    {
        $products = $this->getProducts();
        $product = collect($products)->firstWhere('id', (int)$id);

        if(!$product){
            abort(404);
        }

        return view('detalle', compact('product'));
    }
}
