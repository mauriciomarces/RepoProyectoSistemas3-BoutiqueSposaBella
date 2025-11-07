@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado del reporte -->
    <div class="row mb-4 print-header">
        <div class="col-12 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="print-logo mb-3" style="height: 100px">
            <h1 style="color: #8E805E">Reporte de Movimientos Financieros</h1>
            <p class="text-muted">
                Periodo: {{ request('from') ?: 'Sin límite' }} al {{ request('to') ?: now()->toDateString() }}
            </p>
        </div>
    </div>

    <!-- Resumen financiero en tarjetas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Ingresos Totales</h5>
                    <h3 class="text-success mb-0">Bs. {{ number_format($totales['ingresos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Egresos Totales</h5>
                    <h3 class="text-danger mb-0">Bs. {{ number_format($totales['egresos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Balance Neto</h5>
                    <h3 class="{{ $totales['balance'] >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        Bs. {{ number_format($totales['balance'], 2) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <canvas id="ingresosPorCategoria"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <canvas id="egresosPorCategoria"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla de movimientos -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th class="text-end">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->fecha }}</td>
                    <td>
                        <span class="badge {{ $movimiento->tipo == 'ingreso' ? 'badge-primary' : 'badge-danger' }}">
                            {{ ucfirst($movimiento->tipo) }}
                        </span>
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $movimiento->categoria)) }}</td>
                    <td>{{ $movimiento->descripcion }}</td>
                    <td class="text-end">Bs. {{ number_format($movimiento->monto, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .container-fluid {
        padding: 2rem;
    }
    
    .print-header {
        margin-bottom: 2rem;
    }
    
    .print-logo {
        max-height: 80px;
        margin-bottom: 1rem;
    }
    
    h1 {
        color: #8E805E !important;
        font-size: 24px;
        margin-bottom: 0.5rem;
    }
    
    .card {
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 1rem;
        border: 1px solid #C1BAA2;
    }
    
    .chart-container {
        break-inside: avoid;
        page-break-inside: avoid;
        margin-bottom: 2rem;
    }
    
    .table {
        break-inside: auto;
        page-break-inside: auto;
        margin-top: 2rem;
    }
    
    tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    thead {
        background-color: #8E805E !important;
        color: #EDEEE8 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .badge-primary {
        background-color: #8E805E !important;
        color: #EDEEE8 !important;
    }
    
    .badge-danger {
        background-color: #C14444 !important;
        color: #EDEEE8 !important;
    }
    
    @page {
        margin: 1cm;
        size: portrait;
    }
}
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración común para los gráficos
    const commonOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    color: '#8E805E'
                }
            }
        }
    };

    // Paleta de colores para los gráficos
    const colors = [
        '#8E805E', '#A19E94', '#C1BAA2', '#EDEEE8',
        '#7A6E4E', '#8F8C83', '#ADA692', '#D6D7D1'
    ];

    // Gráfico de Ingresos por Categoría
    new Chart(document.getElementById('ingresosPorCategoria'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(<?php echo json_encode($categorias['ingresos']); ?>).map(function(cat){ return cat.replace('_', ' '); }),
            datasets: [{
                data: Object.values(<?php echo json_encode($categorias['ingresos']); ?>),
                backgroundColor: colors
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: 'Ingresos por Categoría',
                    color: '#8E805E',
                    font: {
                        size: 16,
                        family: "'Playfair Display', serif"
                    }
                }
            }
        }
    });

    // Gráfico de Egresos por Categoría
    new Chart(document.getElementById('egresosPorCategoria'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(<?php echo json_encode($categorias['egresos']); ?>).map(function(cat){ return cat.replace('_', ' '); }),
            datasets: [{
                data: Object.values(<?php echo json_encode($categorias['egresos']); ?>),
                backgroundColor: colors
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                title: {
                    display: true,
                    text: 'Egresos por Categoría',
                    color: '#8E805E',
                    font: {
                        size: 16,
                        family: "'Playfair Display', serif"
                    }
                }
            }
        }
    });
});
</script>
@endsection
        }
    </style>
    <div class="report-header mb-3" style="position:relative; padding: 10px 0 20px;">
        <div class="report-logo" style="position:absolute; left:0; top:0; width:100px;">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="max-width:100%; height:auto;" />
        </div>
        <div class="text-center">
            <h1 class="mb-0" style="font-family: 'Playfair Display', serif;">Reporte Financiero</h1>
            <small class="text-muted">Boutique Sposa Bella</small>
        </div>
    </div>

    <!-- Período del Reporte -->
    <div class="mb-3" style="background:#f7f6f2; padding:10px; border-left:4px solid #C1BAA2;">
        <strong>Período del Reporte:</strong>
        <span>
            Desde: {{ $periodo['desde'] ? \Carbon\Carbon::parse($periodo['desde'])->format('d/m/Y') : ($movimientos->count() ? \Carbon\Carbon::parse($movimientos->min('fecha'))->format('d/m/Y') : 'Sin datos') }}
            - Hasta: {{ $periodo['hasta'] ? \Carbon\Carbon::parse($periodo['hasta'])->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}
        </span>
    </div>

    <!-- Resumen Financiero -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card ingreso">
                <div class="card-body">
                    <h5 class="card-title">Total Ingresos</h5>
                    <h3 class="card-text">Bs. {{ number_format($totales['ingresos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card egreso">
                <div class="card-body">
                    <h5 class="card-title">Total Egresos</h5>
                    <h3 class="card-text">Bs. {{ number_format($totales['egresos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card balance">
                <div class="card-body">
                    <h5 class="card-title">Balance</h5>
                    <h3 class="card-text">Bs. {{ number_format($totales['balance'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis de Punto de Equilibrio -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Análisis de Punto de Equilibrio</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Costos Fijos</h5>
                    <p>Bs. {{ number_format($analisis['costos_fijos'], 2) }}</p>

                    <h5>Costos Variables</h5>
                    <p>Bs. {{ number_format($analisis['costos_variables'], 2) }}</p>

                    <h5>Ventas Totales</h5>
                    <p>Bs. {{ number_format($analisis['ventas'], 2) }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Unidades Vendidas</h5>
                    <p>{{ $analisis['unidades_vendidas'] }}</p>

                    <h5>Punto de Equilibrio (Unidades)</h5>
                    <p>{{ round($analisis['punto_equilibrio']) }} unidades</p>

                    <h5>Punto de Equilibrio (Valor)</h5>
                    <p>Bs. {{ number_format($analisis['punto_equilibrio_valor'] ?? 0, 2) }}</p>

                    @if($analisis['unidades_vendidas'] > 0)
                        <div class="alert {{ $analisis['unidades_vendidas'] >= $analisis['punto_equilibrio'] ? 'alert-success' : 'alert-warning' }}">
                            @if($analisis['unidades_vendidas'] >= $analisis['punto_equilibrio'])
                                Has superado el punto de equilibrio por {{ $analisis['unidades_vendidas'] - round($analisis['punto_equilibrio']) }} unidades.
                            @else
                                Necesitas vender {{ round($analisis['punto_equilibrio']) - $analisis['unidades_vendidas'] }} unidades más para alcanzar el punto de equilibrio.
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="card">
        <div class="card-header">
            <h3>Detalle de Movimientos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>Empleado</th>
                            <th>Referencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $movimiento)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    @if($movimiento->tipo == 'ingreso')
                                        <span class="badge-ingreso">{{ ucfirst($movimiento->tipo) }}</span>
                                    @else
                                        <span class="badge-egreso">{{ ucfirst($movimiento->tipo) }}</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $movimiento->categoria)) }}</td>
                                <td>{{ $movimiento->concepto }}</td>
                                <td>Bs. {{ number_format($movimiento->monto, 2) }}</td>
                                <td>{{ $movimiento->empleado->nombre ?? 'N/A' }}</td>
                                <td>{{ $movimiento->referencia ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(request()->query('print'))
<script>
    // Esperar a que la ventana cargue y luego imprimir
    window.addEventListener('load', function(){
        setTimeout(function(){ window.print(); }, 500);
    });
</script>
@endif
@endsection