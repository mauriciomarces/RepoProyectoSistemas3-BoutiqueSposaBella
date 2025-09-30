<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    private $products = [
        // Categoría Gala
        [
            "id" => 1,
            "nombre" => "Vestido de gala rojo",
            "seccion" => "Gala",
            "precio" => 1500.00,
            "cantidad" => 5,
            "estado" => "disponible",
            "descripcion" => "Elegante vestido largo para eventos de gala.",
            "imagen" => "/images/gala1.jpg"
        ],
        [
            "id" => 2,
            "nombre" => "Vestido de gala azul",
            "seccion" => "Gala",
            "precio" => 1600.00,
            "cantidad" => 3,
            "estado" => "disponible",
            "descripcion" => "Vestido de gala con detalles brillantes y corte ajustado.",
            "imagen" => "/images/gala2.jpg"
        ],

        // Categoría Casual
        [
            "id" => 3,
            "nombre" => "Vestido casual estampado",
            "seccion" => "Casual",
            "precio" => 800.00,
            "cantidad" => 2,
            "estado" => "disponible",
            "descripcion" => "Vestido cómodo para uso diario, con estampado floral.",
            "imagen" => "/images/casual1.jpg"
        ],
        [
            "id" => 4,
            "nombre" => "Vestido casual liso",
            "seccion" => "Casual",
            "precio" => 750.00,
            "cantidad" => 0,
            "estado" => "vendido",
            "descripcion" => "Vestido liso de algodón ideal para verano.",
            "imagen" => "/images/casual2.jpg"
        ],

        // Categoría Fiesta
        [
            "id" => 5,
            "nombre" => "Vestido de fiesta plateado",
            "seccion" => "Fiesta",
            "precio" => 1200.00,
            "cantidad" => 4,
            "estado" => "disponible",
            "descripcion" => "Vestido de fiesta corto con brillo plateado.",
            "imagen" => "/images/fiesta1.jpg"
        ],
        [
            "id" => 6,
            "nombre" => "Vestido de fiesta negro",
            "seccion" => "Fiesta",
            "precio" => 1300.00,
            "cantidad" => 1,
            "estado" => "disponible",
            "descripcion" => "Vestido negro elegante para eventos nocturnos.",
            "imagen" => "/images/fiesta2.jpg"
        ],

        // Categoría Trabajo
        [
            "id" => 7,
            "nombre" => "Vestido oficina azul",
            "seccion" => "Trabajo",
            "precio" => 900.00,
            "cantidad" => 2,
            "estado" => "disponible",
            "descripcion" => "Vestido formal para oficina con corte recto.",
            "imagen" => "/images/trabajo1.jpg"
        ],
        [
            "id" => 8,
            "nombre" => "Vestido oficina gris",
            "seccion" => "Trabajo",
            "precio" => 950.00,
            "cantidad" => 3,
            "estado" => "disponible",
            "descripcion" => "Vestido gris clásico, ideal para reuniones de trabajo.",
            "imagen" => "/images/trabajo2.jpg"
        ]
    ];

    public function index()
    {
        $sections = [];
        foreach($this->products as $product){
            $sections[$product['seccion']][] = $product;
        }
        return view('catalogo', compact('sections'));
    }

    public function show($id)
    {
        $product = collect($this->products)->firstWhere('id', (int)$id);

        if(!$product){
            abort(404);
        }

        return view('detalle', compact('product'));
    }
}
