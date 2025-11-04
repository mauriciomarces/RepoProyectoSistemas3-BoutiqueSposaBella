<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear empleados de ejemplo si no existen
        $empleados = DB::table('empleado')->limit(1)->get();
        if ($empleados->isEmpty()) {
            $ids = [];
            $ids[] = DB::table('empleado')->insertGetId([
                'nombre' => 'Admin Demo',
                'correo' => 'admin@sposabella.test',
                'password' => bcrypt('password'),
                'puesto' => 'Administrador',
                'ID_rol' => 1,
                'ID_sucursal' => 1,
            ]);
            $ids[] = DB::table('empleado')->insertGetId([
                'nombre' => 'Vendedora Demo',
                'correo' => 'vendedora@sposabella.test',
                'password' => bcrypt('password'),
                'puesto' => 'Vendedora',
                'ID_rol' => 2,
                'ID_sucursal' => 1,
            ]);
            $empleadoIds = $ids;
        } else {
            $empleadoIds = DB::table('empleado')->pluck('ID_empleado')->take(3)->toArray();
            if (empty($empleadoIds)) $empleadoIds = [1];
        }

        // Crear fletes de ejemplo
        $fletesCount = DB::table('fletes')->count();
        if ($fletesCount == 0) {
            $destinos = [
                ['destinatario' => 'María López', 'direccion' => 'Av. 9 de Julio 123', 'telefono' => '70010010'],
                ['destinatario' => 'Carla Fernández', 'direccion' => 'Calle Falsa 456', 'telefono' => '70020020'],
                ['destinatario' => 'Lucía Gómez', 'direccion' => 'Pje. Angel 23', 'telefono' => '70030030'],
                ['destinatario' => 'Ana Ruiz', 'direccion' => 'Av. Siempre Viva 742', 'telefono' => '70040040'],
                ['destinatario' => 'Paola Díaz', 'direccion' => 'C. Real 11', 'telefono' => '70050050'],
            ];

            foreach ($destinos as $d) {
                DB::table('fletes')->insert(array_merge($d, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]));
            }
        }

        // Crear movimientos de ejemplo
        $movCount = 20;
        $now = Carbon::now();
        $categorias = ['ventas', 'confecciones', 'gastos_operativos', 'salarios', 'insumos', 'otros'];

        for ($i = 0; $i < $movCount; $i++) {
            $tipo = (rand(0, 1) === 1) ? 'ingreso' : 'egreso';
            $categoria = $categorias[array_rand($categorias)];
            $monto = rand(5000, 200000) / 100.0; // entre 50.00 y 2000.00
            $fecha = $now->copy()->subDays(rand(0, 30))->format('Y-m-d');
            $empleado = $empleadoIds[array_rand($empleadoIds)];

            DB::table('movimiento_financiero')->insert([
                'tipo' => $tipo,
                'monto' => $monto,
                'concepto' => ucfirst($categoria) . ' - ejemplo',
                'descripcion' => 'Registro de prueba generado por el seeder',
                'fecha' => $fecha,
                'categoria' => $categoria,
                'referencia' => Str::upper(Str::random(8)),
                'ID_empleado' => $empleado,
                'created_at' => $fecha . ' 12:00:00',
                'updated_at' => $fecha . ' 12:00:00',
            ]);
        }
    }
}
