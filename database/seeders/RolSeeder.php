<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run()
    {
        // Ensure Administradora is ID 1
        $roles = [
            ['ID_rol' => 1, 'nombre' => 'Administradora', 'descripcion' => 'Acceso total al sistema'],
            ['ID_rol' => 2, 'nombre' => 'Vendedora', 'descripcion' => 'Encargada de ventas y atención al cliente'],
            ['ID_rol' => 3, 'nombre' => 'Costurera', 'descripcion' => 'Encargada de confecciones y arreglos'],
        ];

        foreach ($roles as $rol) {
            DB::table('rol')->updateOrInsert(
                ['ID_rol' => $rol['ID_rol']],
                $rol
            );
        }
    }
}
