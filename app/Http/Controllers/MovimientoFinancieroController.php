<?php

namespace App\Http\Controllers;

use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovimientoFinancieroController extends Controller
{
    public function index(Request $request)
    {
        // Construir consulta y aplicar filtros (from/to/categoria/tipo)
        $query = MovimientoFinanciero::with('empleado');
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));
        if ($request->filled('tipo')) $query->where('tipo', $request->input('tipo'));

        $movimientos = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'balance' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
        ];

        // Calcular totales por categoría para los gráficos
        $categorias = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray(),
            'egresos' => $movimientos->where('tipo', 'egreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray()
        ];

        return view('movimientos.index', compact('movimientos', 'totales', 'categorias'));
    }

    public function create()
    {
        $categorias = [
            'ventas' => 'Ventas',
            'confecciones' => 'Confecciones',
            'gastos_operativos' => 'Gastos Operativos',
            'salarios' => 'Salarios',
            'insumos' => 'Insumos',
            'otros' => 'Otros'
        ];

        return view('movimientos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0',
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'categoria' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:255'
        ]);

        // asignar empleado si hay sesión
        if (session()->has('empleado_id')) {
            $validated['ID_empleado'] = session('empleado_id');
        }

        MovimientoFinanciero::create($validated);

        return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado correctamente');
    }

    public function generateReport(Request $request)
    {
        // Restringir acceso: solo administradora (ID_rol == 1)
        $empleadoId = session('empleado_id');
        if (!$empleadoId || DB::table('empleado')->where('ID_empleado', $empleadoId)->value('ID_rol') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        $from = $request->input('from');
        $to = $request->input('to');
        $categoria = $request->input('categoria');

        $query = MovimientoFinanciero::with('empleado');

        if ($from) {
            $query->where('fecha', '>=', $from);
        }
        if ($to) {
            $query->where('fecha', '<=', $to);
        }
        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->get()->map(function($m){
            if (!($m->fecha instanceof \Carbon\Carbon)) {
                $m->fecha = \Carbon\Carbon::parse($m->fecha);
            }
            return $m;
        });

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'balance' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
        ];

        // análisis simplificado de punto de equilibrio (si hay datos suficientes)
        $costosFijos = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['salarios', 'gastos_operativos'])->sum('monto');
        $costosVariables = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['insumos', 'confecciones'])->sum('monto');
        $ventas = $movimientos->where('tipo', 'ingreso')->where('categoria', 'ventas')->sum('monto');

    // inicializar variables por defecto
    $puntoEquilibrioValor = 0;
    $periodoDesde = null;
    $periodoHasta = now()->toDateString();

        // Calcular unidades vendidas uniendo pedido -> detalle_pedido usando fecha_pedido
        $unidadesVendidas = DB::table('detalle_pedido')
            ->join('pedido', 'detalle_pedido.ID_pedido', '=', 'pedido.ID_pedido')
            ->when($from, fn($q) => $q->where('pedido.fecha_pedido', '>=', $from))
            ->when($to, fn($q) => $q->where('pedido.fecha_pedido', '<=', $to))
            ->sum('detalle_pedido.cantidad');

        if ($unidadesVendidas > 0) {
            $precioPromedioVenta = $ventas / $unidadesVendidas;
            $costoVariableUnitario = $costosVariables / $unidadesVendidas;
            $puntoEquilibrio = $costosFijos / max(0.0001, ($precioPromedioVenta - $costoVariableUnitario));
        } else {
            $puntoEquilibrio = 0;
        }

        // Calcular punto de equilibrio en valor (monetario) usando la misma fórmula que en generateReport
        if ($ventas > 0) {
            $puntoEquilibrioValor = $costosFijos / max(0.0001, (1 - ($costosVariables / $ventas)));
        } else {
            $puntoEquilibrioValor = 0;
        }

        // Ajustar periodo por defecto para exportPdf si no vienen parámetros
        $periodoDesde = $request->input('from') ?: ($movimientos->count() ? $movimientos->min('fecha')->toDateString() : null);
        $periodoHasta = $request->input('to') ?: now()->toDateString();

        // Calcular punto de equilibrio en valor (monetario) usando la fórmula:
        // Punto de Equilibrio (en ventas) = Costos Fijos / (1 - (Costos Variables / Ventas))
        if ($ventas > 0) {
            $puntoEquilibrioValor = $costosFijos / max(0.0001, (1 - ($costosVariables / $ventas)));
        } else {
            $puntoEquilibrioValor = 0;
        }

        // Establecer periodo por defecto para la vista: desde = fecha mínima de movimientos si no se pasó; hasta = hoy si no se pasó
        $periodoDesde = $from ?: ($movimientos->count() ? $movimientos->min('fecha')->toDateString() : null);
        $periodoHasta = $to ?: now()->toDateString();

        $data = [
            'movimientos' => $movimientos,
            'totales' => $totales,
            'analisis' => [
                'costos_fijos' => $costosFijos,
                'costos_variables' => $costosVariables,
                'ventas' => $ventas,
                'unidades_vendidas' => $unidadesVendidas,
                'punto_equilibrio' => $puntoEquilibrio,
                'punto_equilibrio_valor' => $puntoEquilibrioValor,
            ],
            'periodo' => ['desde' => $periodoDesde, 'hasta' => $periodoHasta]
        ];

        return view('movimientos.report', $data);
    }

    // API: devuelve últimos movimientos y totales en JSON (para polling)
    public function latest(Request $request)
    {
        $limit = intval($request->query('limit', 50));
        $query = MovimientoFinanciero::with('empleado');
        if ($request->filled('tipo')) $query->where('tipo', $request->input('tipo'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));

        $movimientos = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'balance' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
        ];

        return response()->json([ 'movimientos' => $movimientos, 'totales' => $totales ]);
    }

    // Exportar CSV simple con movimientos filtrados
    public function exportCsv(Request $request)
    {
        $query = MovimientoFinanciero::query();
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));

        $movimientos = $query->orderBy('fecha', 'desc')->get();

        $filename = 'movimientos_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($movimientos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Fecha','Tipo','Categoria','Concepto','Monto','Empleado','Referencia']);
            foreach ($movimientos as $m) {
                $fecha = $m->fecha instanceof \Carbon\Carbon ? $m->fecha->format('Y-m-d') : \Carbon\Carbon::parse($m->fecha)->format('Y-m-d');
                fputcsv($out, [
                    $fecha,
                    $m->tipo,
                    $m->categoria,
                    $m->concepto,
                    number_format($m->monto,2,'.',''),
                    $m->empleado->nombre ?? '',
                    $m->referencia ?? '',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Exportar PDF (usa barryvdh/laravel-dompdf si está instalado)
    public function exportPdf(Request $request)
    {
        // Restringir acceso: solo administradora (ID_rol == 1)
        $empleadoId = session('empleado_id');
        if (!$empleadoId || DB::table('empleado')->where('ID_empleado', $empleadoId)->value('ID_rol') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        $query = MovimientoFinanciero::with('empleado');
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));

        $movimientos = $query->orderBy('fecha', 'desc')->get()->map(function($m) {
            if (!$m->fecha instanceof \Carbon\Carbon) {
                $m->fecha = \Carbon\Carbon::parse($m->fecha);
            }
            return $m;
        });

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'balance' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
        ];

        // preparar datos similares a generateReport
        $costosFijos = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['salarios', 'gastos_operativos'])->sum('monto');
        $costosVariables = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['insumos', 'confecciones'])->sum('monto');
        $ventas = $movimientos->where('tipo', 'ingreso')->where('categoria', 'ventas')->sum('monto');

    // inicializar variables por defecto
    $puntoEquilibrioValor = 0;
    $periodoDesde = null;
    $periodoHasta = now()->toDateString();

        $unidadesVendidas = DB::table('detalle_pedido')
            ->join('pedido', 'detalle_pedido.ID_pedido', '=', 'pedido.ID_pedido')
            ->when($request->filled('from'), fn($q) => $q->where('pedido.fecha_pedido', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->where('pedido.fecha_pedido', '<=', $request->input('to')))
            ->sum('detalle_pedido.cantidad');

        if ($unidadesVendidas > 0) {
            $precioPromedioVenta = $ventas / $unidadesVendidas;
            $costoVariableUnitario = $costosVariables / $unidadesVendidas;
            $puntoEquilibrio = $costosFijos / max(0.0001, ($precioPromedioVenta - $costoVariableUnitario));
        } else {
            $puntoEquilibrio = 0;
        }

        // Calcular totales por categoría para los gráficos
        $categorias = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray(),
            'egresos' => $movimientos->where('tipo', 'egreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray()
        ];

        // permitir seleccionar qué gráficos incluir (array de ids en request 'charts[]')
        $selectedCharts = $request->input('charts', []);

        $includeAnalysis = $request->boolean('include_analysis', true);

        $data = [
            'movimientos' => $movimientos,
            'totales' => $totales,
            'categorias' => $categorias,
            'selected_charts' => $selectedCharts,
            'include_analysis' => $includeAnalysis,
            'analisis' => [
                'costos_fijos' => $costosFijos,
                'costos_variables' => $costosVariables,
                'ventas' => $ventas,
                'unidades_vendidas' => $unidadesVendidas,
                'punto_equilibrio' => $puntoEquilibrio,
                'punto_equilibrio_valor' => $puntoEquilibrioValor,
            ],
            'periodo' => ['desde' => $periodoDesde, 'hasta' => $periodoHasta]
        ];

        // Si está disponible la clase Pdf (barryvdh/laravel-dompdf), intentar generar PDF y descargar
        if (class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf')) {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('movimientos.pdf_report', $data)->setPaper('a4', 'portrait');
                $filename = 'reporte_movimientos_' . now()->format('Ymd_His') . '.pdf';
                return $pdf->download($filename);
            } catch (\Exception $e) {
                // Si DomPDF falla (p.ej. falta la extensión GD), hacer fallback a la vista HTML limpia para imprimir
                Log::error('DomPDF error: ' . $e->getMessage());
                return response(view('movimientos.pdf_report', $data), 200);
            }
        }

        // Si no está instalado dompdf, devolver la vista PDF-friendly (html standalone) con cabeceras para descarga HTML
        $html = view('movimientos.pdf_report', $data)->render();
        $filename = 'reporte_movimientos_' . now()->format('Ymd_His') . '.html';
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    // Vista imprimible limpia: abre la plantilla PDF-friendly en el navegador (no intenta generar con DomPDF)
    public function printReport(Request $request)
    {
        // Restringir acceso: solo administradora (ID_rol == 1)
        $empleadoId = session('empleado_id');
        if (!$empleadoId || DB::table('empleado')->where('ID_empleado', $empleadoId)->value('ID_rol') != 1) {
            abort(403, 'Acceso no autorizado');
        }

        $query = MovimientoFinanciero::with('empleado');
        if ($request->filled('from')) $query->where('fecha', '>=', $request->input('from'));
        if ($request->filled('to')) $query->where('fecha', '<=', $request->input('to'));
        if ($request->filled('categoria')) $query->where('categoria', $request->input('categoria'));

        $movimientos = $query->orderBy('fecha', 'desc')->get()->map(function($m) {
            if (!$m->fecha instanceof \Carbon\Carbon) {
                $m->fecha = \Carbon\Carbon::parse($m->fecha);
            }
            return $m;
        });

        $totales = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')->sum('monto'),
            'egresos' => $movimientos->where('tipo', 'egreso')->sum('monto'),
            'balance' => $movimientos->where('tipo', 'ingreso')->sum('monto') - $movimientos->where('tipo', 'egreso')->sum('monto'),
        ];

        // preparar datos similares a generateReport
        $costosFijos = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['salarios', 'gastos_operativos'])->sum('monto');
        $costosVariables = $movimientos->where('tipo', 'egreso')->whereIn('categoria', ['insumos', 'confecciones'])->sum('monto');
        $ventas = $movimientos->where('tipo', 'ingreso')->where('categoria', 'ventas')->sum('monto');

        $unidadesVendidas = DB::table('detalle_pedido')
            ->join('pedido', 'detalle_pedido.ID_pedido', '=', 'pedido.ID_pedido')
            ->when($request->filled('from'), fn($q) => $q->where('pedido.fecha_pedido', '>=', $request->input('from')))
            ->when($request->filled('to'), fn($q) => $q->where('pedido.fecha_pedido', '<=', $request->input('to')))
            ->sum('detalle_pedido.cantidad');

        if ($unidadesVendidas > 0) {
            $precioPromedioVenta = $ventas / $unidadesVendidas;
            $costoVariableUnitario = $costosVariables / $unidadesVendidas;
            $puntoEquilibrio = $costosFijos / max(0.0001, ($precioPromedioVenta - $costoVariableUnitario));
        } else {
            $puntoEquilibrio = 0;
        }

        $categorias = [
            'ingresos' => $movimientos->where('tipo', 'ingreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray(),
            'egresos' => $movimientos->where('tipo', 'egreso')
                ->groupBy('categoria')
                ->map(function ($items) {
                    return $items->sum('monto');
                })->toArray()
        ];

        $selectedCharts = $request->input('charts', []);
        $includeAnalysis = $request->boolean('include_analysis', true);

        $data = [
            'movimientos' => $movimientos,
            'totales' => $totales,
            'categorias' => $categorias,
            'selected_charts' => $selectedCharts,
            'include_analysis' => $includeAnalysis,
            'analisis' => [
                'costos_fijos' => $costosFijos,
                'costos_variables' => $costosVariables,
                'ventas' => $ventas,
                'unidades_vendidas' => $unidadesVendidas,
                'punto_equilibrio' => $puntoEquilibrio,
                'punto_equilibrio_valor' => ($ventas > 0) ? ($costosFijos / max(0.0001, (1 - ($costosVariables / $ventas)))) : 0,
            ],
            'periodo' => ['desde' => $request->input('from') ?: null, 'hasta' => $request->input('to') ?: now()->toDateString()]
        ];

        // Renderizar la vista PDF-friendly en el navegador (limpia, sin barra de navegación)
        return view('movimientos.pdf_report', $data);
    }
}
