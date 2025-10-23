<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    protected $productosJsonPath;

    public function __construct()
    {
        // Ruta al archivo JSON en storage/app/productos.json
        $this->productosJsonPath = storage_path('app/products.json');
    }

    /**
     * Mostrar catálogo completo
     */
    public function index(Request $request)
    {
        $products = $this->getProductos();

        // Filtrado por stock
        if ($request->has('stock')) {
            $stock = $request->stock;
            $products = array_filter($products, function ($product) use ($stock) {
                return match($stock) {
                    'available' => $product['cantidad'] > 0,
                    'sold'      => $product['cantidad'] === 0,
                    default     => true,
                };
            });
        }

        // Agrupar productos por secciones
        $sections = [];
        foreach ($products as $product) {
            $sections[$product['seccion']][] = $product;
        }

        // Retornar vista parcial si es AJAX, o la vista completa
        if ($request->ajax()) {
            return view('catalogo-partial', compact('sections'));
        }

        return view('catalogo', compact('sections'));
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show($id)
    {
        $products = $this->getProductos();

        $product = collect($products)->firstWhere('id', (int)$id);

        if (!$product) {
            abort(404, "Producto no encontrado.");
        }

        return view('catalogo-show', compact('product'));
    }

    /**
     * Leer y decodificar JSON de productos
     */
    private function getProductos(): array
    {
        if (!file_exists($this->productosJsonPath)) {
            abort(404, "Archivo de productos no encontrado.");
        }

        $products = json_decode(file_get_contents($this->productosJsonPath), true);

        if ($products === null) {
            abort(500, "Error al decodificar el JSON de productos.");
        }

        return $products;
    }
}
