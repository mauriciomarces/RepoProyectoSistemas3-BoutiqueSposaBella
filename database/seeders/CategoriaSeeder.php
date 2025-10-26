<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Producto;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = ['Casual', 'Fiesta', 'Gala', 'Novia', 'Trabajo'];
        
        foreach ($categorias as $cat) {
            Categoria::create(['nombre' => $cat]);
        }

        // Asignar productos a cada categoría según su nombre de imagen
        $productos = Producto::all();
        foreach ($productos as $producto) {
            if (str_contains($producto->nombre, 'casual')) {
                $producto->categoria_id = Categoria::where('nombre', 'Casual')->first()->id;
            } elseif (str_contains($producto->nombre, 'fiesta')) {
                $producto->categoria_id = Categoria::where('nombre', 'Fiesta')->first()->id;
            } elseif (str_contains($producto->nombre, 'gala')) {
                $producto->categoria_id = Categoria::where('nombre', 'Gala')->first()->id;
            } elseif (str_contains($producto->nombre, 'novia')) {
                $producto->categoria_id = Categoria::where('nombre', 'Novia')->first()->id;
            } elseif (str_contains($producto->nombre, 'trabajo')) {
                $producto->categoria_id = Categoria::where('nombre', 'Trabajo')->first()->id;
            } else {
                $producto->categoria_id = null;
            }
            $producto->save();
        }
    }
}
