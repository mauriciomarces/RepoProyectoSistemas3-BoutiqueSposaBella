<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovimientosFinancierosSeeder extends Seeder
{
    public function run()
    {
        // Limpiar datos existentes
        DB::table('movimiento_financiero')->truncate();

        // Generar datos de 2 años (2023-2024)
        $startDate = Carbon::create(2023, 1, 1);
        $endDate = Carbon::create(2024, 12, 31);

        $movimientos = [];

        // Categorías disponibles
        $categorias = [
            'ventas' => 'ingreso',
            'confecciones' => 'egreso',
            'gastos_operativos' => 'egreso',
            'salarios' => 'egreso',
            'insumos' => 'egreso',
            'otros' => 'ingreso'
        ];

        // Empleados disponibles (IDs 1-5)
        $empleados = [1, 2, 3, 4, 5];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Generar 5-15 movimientos por día
            $numMovimientos = rand(5, 15);

            for ($i = 0; $i < $numMovimientos; $i++) {
                $categoria = array_rand($categorias);
                $tipo = $categorias[$categoria];

                // Montos realistas por categoría
                $monto = $this->generarMontoRealista($categoria);

                $movimiento = [
                    'tipo' => $tipo,
                    'categoria' => $categoria,
                    'monto' => $monto,
                    'concepto' => $this->generarConcepto($categoria),
                    'descripcion' => $this->generarDescripcion($categoria),
                    'fecha' => $currentDate->format('Y-m-d'),
                    'referencia' => $this->generarReferencia($categoria),
                    'ID_empleado' => $categoria === 'salarios' ? $empleados[array_rand($empleados)] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $movimientos[] = $movimiento;
            }

            $currentDate->addDay();
        }

        // Insertar en lotes para mejor rendimiento
        $chunks = array_chunk($movimientos, 1000);
        foreach ($chunks as $chunk) {
            DB::table('movimiento_financiero')->insert($chunk);
        }

        $this->command->info('Se han generado ' . count($movimientos) . ' movimientos financieros.');
    }

    private function generarMontoRealista($categoria)
    {
        switch ($categoria) {
            case 'ventas':
                return rand(5000, 50000) / 100; // Bs. 50 - 500
            case 'confecciones':
                return rand(20000, 150000) / 100; // Bs. 200 - 1500
            case 'gastos_operativos':
                return rand(5000, 30000) / 100; // Bs. 50 - 300
            case 'salarios':
                return rand(200000, 500000) / 100; // Bs. 2000 - 5000
            case 'insumos':
                return rand(10000, 80000) / 100; // Bs. 100 - 800
            case 'otros':
                return rand(1000, 10000) / 100; // Bs. 10 - 100
            default:
                return rand(1000, 10000) / 100;
        }
    }

    private function generarConcepto($categoria)
    {
        $conceptos = [
            'ventas' => ['Venta de vestido de novia', 'Venta de vestido de fiesta', 'Venta de accesorios', 'Venta de zapatos', 'Venta de tocado'],
            'confecciones' => ['Confección vestido personalizado', 'Arreglos y modificaciones', 'Confección vestido de dama', 'Confección terno', 'Confección vestido infantil'],
            'gastos_operativos' => ['Alquiler local', 'Servicios básicos', 'Internet y teléfono', 'Materiales de oficina', 'Mantenimiento equipo'],
            'salarios' => ['Pago mensual empleado', 'Bono productividad', 'Pago horas extra', 'Pago comisión ventas'],
            'insumos' => ['Compra tela', 'Compra hilos y agujas', 'Compra botones y cierres', 'Compra tintes', 'Compra herramientas'],
            'otros' => ['Ingreso extra', 'Devolución', 'Descuento aplicado', 'Ajuste contable']
        ];

        return $conceptos[$categoria][array_rand($conceptos[$categoria])];
    }

    private function generarDescripcion($categoria)
    {
        $descripciones = [
            'ventas' => ['Cliente satisfecho con el producto', 'Venta realizada en tienda', 'Pedido especial personalizado', 'Cliente habitual', 'Venta por recomendación'],
            'confecciones' => ['Trabajo personalizado para cliente', 'Modificaciones según requerimientos', 'Confección urgente', 'Trabajo de alta calidad', 'Cliente VIP'],
            'gastos_operativos' => ['Pago mensual correspondiente', 'Servicio esencial para operaciones', 'Mantenimiento preventivo', 'Actualización de equipos', 'Gasto administrativo'],
            'salarios' => ['Pago correspondiente al mes trabajado', 'Incluye bono por rendimiento', 'Pago de horas extras trabajadas', 'Comisión por ventas realizadas'],
            'insumos' => ['Materiales necesarios para producción', 'Compra de proveedores habituales', 'Stock de materiales básicos', 'Materiales para pedidos especiales'],
            'otros' => ['Movimiento extraordinario', 'Ajuste por error contable', 'Ingreso no planificado', 'Corrección de registro anterior']
        ];

        return $descripciones[$categoria][array_rand($descripciones[$categoria])];
    }

    private function generarReferencia($categoria)
    {
        $prefijos = [
            'ventas' => 'VTA',
            'confecciones' => 'CONF',
            'gastos_operativos' => 'GOP',
            'salarios' => 'SAL',
            'insumos' => 'INS',
            'otros' => 'OTR'
        ];

        return $prefijos[$categoria] . '-' . rand(10000, 99999);
    }
}
