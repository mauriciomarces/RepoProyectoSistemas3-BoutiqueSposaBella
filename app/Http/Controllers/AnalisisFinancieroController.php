<?php

namespace App\Http\Controllers;

// Usamos directamente MovimientoFinanciero (no crear tablas nuevas)
use App\Models\MovimientoFinanciero;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalisisFinancieroController extends Controller
{
    public function index(Request $request)
    {
        // Construir consulta y aplicar filtros (from/to/categoria/tipo)
        $query = MovimientoFinanciero::with('empleado');
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));
        if ($request->filled('tipo')) $query->where('tipo', $request->input('tipo'));

        $movimientos = $query->orderBy('fecha', 'desc')->orderBy('created_at', 'desc')->get();

        // Totales
        $ingresosMes = $movimientos->where('tipo', 'ingreso')->sum('monto');
        $egresosMes = $movimientos->where('tipo', 'egreso')->sum('monto');
        $comprasMes = $movimientos->where('tipo', 'compra')->sum('monto');
        // Por compatibilidad antigua consideramos compras dentro de gastos si existen
        $totalGastos = $egresosMes + $comprasMes;
        $balanceMes = $ingresosMes - $totalGastos;

        // Categorías para gráficos (misma lógica que en movimientos)
        $categorias = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')
                ->groupBy('categoria')
                ->map(fn($items) => $items->sum('monto'))
                ->toArray(),
            'egresos' => $movimientos->where('tipo', 'egreso')
                ->groupBy('categoria')
                ->map(fn($items) => $items->sum('monto'))
                ->toArray(),
        ];

        // Filtrar solo las categorías que son gastos reales (excluir ventas y otros ingresos)
        $categoriasGastos = ['confecciones', 'gastos_operativos', 'salarios', 'insumos'];
        $distribucionGastos = collect($categorias['egresos'])
            ->filter(function ($total, $categoria) use ($categoriasGastos) {
                return in_array($categoria, $categoriasGastos);
            })
            ->map(function ($total, $categoria) {
                return ['tipo_gasto' => ucfirst(str_replace('_', ' ', $categoria)), 'total' => $total];
            })->values();

        $distribucionIngresos = collect($categorias['ingresos'])->map(function ($total, $categoria) {
            return ['categoria' => ucfirst(str_replace('_', ' ', $categoria)), 'total' => $total];
        })->values();

        // Costos y ventas para punto de equilibrio
        $costosFijos = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['salarios', 'gastos_operativos'])->sum('monto');
        $costosVariables = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['insumos', 'confecciones'])->sum('monto');
        $ventas = $movimientos->where('tipo', 'ingreso')->where('categoria', 'ventas')->sum('monto');

        // Unidades vendidas (desde pedidos)
        $unidadesVendidas = DB::table('detalle_pedido')
            ->join('pedido', 'detalle_pedido.ID_pedido', '=', 'pedido.ID_pedido')
            ->when($request->filled('from'), fn($q) => $q->where('pedido.fecha_pedido', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->where('pedido.fecha_pedido', '<=', $request->input('to')))
            ->sum('detalle_pedido.cantidad');

        // Precio promedio por unidad (si hay unidades vendidas)
        $precioPromedioVenta = ($unidadesVendidas > 0) ? ($ventas / $unidadesVendidas) : 0;
        $costoVariableUnitario = ($unidadesVendidas > 0) ? ($costosVariables / $unidadesVendidas) : 0;

        // Punto de equilibrio (unidades) = costos fijos / (precio promedio - costo variable unitario)
        if ($precioPromedioVenta - $costoVariableUnitario > 0) {
            $unidadesNecesarias = (int) ceil($costosFijos / max(0.0001, ($precioPromedioVenta - $costoVariableUnitario)));
        } else {
            // No es posible calcular unidades por falta de margen
            $unidadesNecesarias = 0;
        }

        // Punto de equilibrio en valor (ventas necesarias en Bs.)
        $ventasNecesariasValor = ($ventas > 0) ? ($costosFijos / max(0.0001, (1 - ($costosVariables / $ventas)))) : 0;
        $porcentajeVariable = ($ventas > 0) ? ($costosVariables / $ventas) : 0;

        // Unidades necesarias para alcanzar el valor de ventas necesarias (si hay precio promedio)
        $unidadesParaVentasNecesarias = ($precioPromedioVenta > 0) ? (int) ceil($ventasNecesariasValor / $precioPromedioVenta) : 0;

        // Tendencia de ventas (últimos 6 meses) para el gráfico
        $tendenciaVentas = MovimientoFinanciero::select(DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"), DB::raw('SUM(monto) as total'))
            ->where('tipo', 'ingreso')
            ->where('fecha', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(fn($item) => ['mes' => $item->mes, 'total' => (float) $item->total]);

        // Preparar variables para la vista
        $gastosMes = $egresosMes;
        $ingresosTotalesPeriodo = $ventas;

        return view('analisis_financiero.index', compact(
            'ingresosMes',
            'comprasMes',
            'gastosMes',
            'totalGastos',
            'balanceMes',
            'tendenciaVentas',
            'distribucionGastos',
            'distribucionIngresos',
            'costosFijos',
            'costosVariables',
            'ingresosTotalesPeriodo',
            'porcentajeVariable',
            'ventasNecesariasValor',
            'unidadesVendidas',
            'unidadesNecesarias',
            'ventas',
            'categorias',
            'precioPromedioVenta',
            'unidadesParaVentasNecesarias'
        ));
    }

    public function reporteMensual(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        // Si no se proporcionan fechas, mostrar el formulario vacío
        if (!$from || !$to) {
            $mes = Carbon::now()->format('Y-m');
            return view('analisis_financiero.reporte_mensual', compact('mes'));
        }

        $inicio = Carbon::parse($from);
        $fin = Carbon::parse($to);

        // Validar que la fecha desde no sea mayor que hasta
        if ($inicio > $fin) {
            return redirect()->back()->with('error', 'La fecha "Desde" no puede ser mayor que la fecha "Hasta".');
        }

        // Obtener movimientos desde la tabla movimiento_financiero
        $movimientos = MovimientoFinanciero::whereBetween('fecha', [$inicio, $fin])
            ->orderBy('fecha', 'desc')
            ->get();

        $ventas = $movimientos->where('tipo', 'ingreso')->values();
        $compras = $movimientos->where('tipo', 'compra')->values();
        $gastos = $movimientos->where('tipo', 'egreso')->values();

        $resumen = [
            'total_ventas' => $ventas->sum('monto'),
            'total_compras' => $compras->sum('monto'),
            'total_gastos' => $gastos->sum('monto'),
            'balance' => $ventas->sum('monto') - ($compras->sum('monto') + $gastos->sum('monto'))
        ];

        // Usar el mes actual como fallback para compatibilidad
        $mes = Carbon::now()->format('Y-m');

        return view('analisis_financiero.reporte_mensual', compact(
            'ventas',
            'compras',
            'gastos',
            'resumen',
            'mes',
            'from',
            'to'
        ));
    }

    public function graficosTendencias()
    {
        // Tendencia de ingresos por mes (último año)
        $ventasPorMes = MovimientoFinanciero::select(
            DB::raw("DATE_FORMAT(fecha, '%Y-%m') as mes"),
            DB::raw('COUNT(*) as total_transacciones'),
            DB::raw('SUM(monto) as total_ventas')
        )
            ->where('tipo', 'ingreso')
            ->where('fecha', '>=', Carbon::now()->subYear())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Gastos por categoría (últimos 6 meses) - solo categorías de gastos reales
        $categoriasGastos = ['confecciones', 'gastos_operativos', 'salarios', 'insumos'];
        $gastosPorCategoria = MovimientoFinanciero::select(
            DB::raw("categoria as tipo_gasto"),
            DB::raw('SUM(monto) as total')
        )
            ->where('tipo', 'egreso')
            ->whereIn('categoria', $categoriasGastos)
            ->where('fecha', '>=', Carbon::now()->subMonths(6))
            ->groupBy('categoria')
            ->get();

        // Comparativa mensual ingresos vs gastos (últimos 6 meses)
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $mesKey = Carbon::now()->subMonths($i)->format('Y-m');
            $meses[] = $mesKey;
        }

        $comparativaMensual = [];
        foreach ($meses as $m) {
            $inicioMes = Carbon::createFromFormat('Y-m', $m)->startOfMonth();
            $finMes = Carbon::createFromFormat('Y-m', $m)->endOfMonth();

            $ingresos = MovimientoFinanciero::whereBetween('fecha', [$inicioMes, $finMes])
                ->where('tipo', 'ingreso')
                ->sum('monto');

            $gastos = MovimientoFinanciero::whereBetween('fecha', [$inicioMes, $finMes])
                ->where('tipo', 'egreso')
                ->sum('monto');

            $comparativaMensual[] = [
                'mes' => $m,
                'ingresos' => $ingresos,
                'gastos' => $gastos
            ];
        }

        return view('analisis_financiero.graficos', compact(
            'ventasPorMes',
            'gastosPorCategoria',
            'comparativaMensual'
        ));
    }
}
