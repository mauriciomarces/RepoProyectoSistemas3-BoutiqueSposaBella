@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Análisis Financiero</h2>

    <div class="row">
        <!-- Resumen del Mes -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Resumen del Mes Actual
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Ingresos</h6>
                                <h4 class="text-success">Bs. {{ number_format($ingresosMes, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Gastos</h6>
                                <h4 class="text-danger">Bs. {{ number_format($totalGastos, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Balance</h6>
                                <h4 class="{{ $balanceMes >= 0 ? 'text-success' : 'text-danger' }}">
                                    Bs. {{ number_format($balanceMes, 2) }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6>Margen (Bs.)</h6>
                                <h4 class="{{ $balanceMes >= 0 ? 'text-success' : 'text-danger' }}">
                                    Bs. {{ number_format($balanceMes, 2) }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                    <!-- Punto de Equilibrio -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Punto de Equilibrio (estimado)</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <h6>Costos fijos</h6>
                                            <p>Bs. {{ number_format($costosFijos ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h6>Costos variables</h6>
                                            <p>Bs. {{ number_format($costosVariables ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h6>Ventas necesarias (valor)</h6>
                                            <p>Bs. {{ number_format($ventasNecesariasValor ?? 0, 2) }}</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h6>Unidades vendidas</h6>
                                            <p>{{ number_format($unidadesVendidas ?? 0, 0) }}</p>
                                            <small class="text-muted d-block">Precio promedio: @if(isset($precioPromedioVenta) && $precioPromedioVenta>0) Bs. {{ number_format($precioPromedioVenta,2) }} @else N/A @endif</small>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h6>Unidades necesarias (p.e.)</h6>
                                            <p>{{ number_format($unidadesNecesarias ?? 0, 0) }}</p>
                                            <small class="text-muted d-block">(basado en margen unitario)</small>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h6>Unidades necesarias (por valor)</h6>
                                            <p>{{ number_format($unidadesParaVentasNecesarias ?? 0, 0) }}</p>
                                            <small class="text-muted d-block">(ventas necesarias Bs. / precio promedio)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- Gráfico de Tendencia de Ventas -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    Tendencia de Ventas - Últimos 6 Meses
                </div>
                <div class="card-body">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución de Gastos -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    Distribución de Gastos
                </div>
                <div class="card-body">
                    <canvas id="gastosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces a Reportes -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Reportes Detallados
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('analisis.reporte-mensual') }}" class="btn btn-primary btn-block">
                                Reporte Mensual
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('analisis.graficos') }}" class="btn btn-info btn-block">
                                Gráficos y Tendencias
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="application/json" id="ventas-data">{!! json_encode($tendenciaVentas) !!}</script>
<script type="application/json" id="gastos-data">{!! json_encode($distribucionGastos) !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Tendencia de Ventas
    const ventasData = JSON.parse(document.getElementById('ventas-data').textContent || '[]');
    new Chart(document.getElementById('ventasChart'), {
        type: 'line',
        data: {
            labels: ventasData.map(item => item.mes),
            datasets: [{
                label: 'Ventas Mensuales',
                data: ventasData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico de Distribución de Gastos
    const gastosData = JSON.parse(document.getElementById('gastos-data').textContent || '[]');
    new Chart(document.getElementById('gastosChart'), {
        type: 'doughnut',
        data: {
            labels: gastosData.map(item => item.tipo_gasto),
            datasets: [{
                data: gastosData.map(item => item.total),
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection