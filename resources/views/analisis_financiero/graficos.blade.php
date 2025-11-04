@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gráficos y Tendencias</h2>

    <div class="row">
        <!-- Tendencia de Ventas -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Tendencia de Ventas - Último Año
                </div>
                <div class="card-body">
                    <canvas id="ventasAnualesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gastos por Categoría -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Distribución de Gastos por Categoría
                </div>
                <div class="card-body">
                    <canvas id="gastosCategoriaChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Comparativa Ingresos vs Gastos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Comparativa Ingresos vs Gastos
                </div>
                <div class="card-body">
                    <canvas id="comparativaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="application/json" id="ventas-por-mes">{!! json_encode($ventasPorMes) !!}</script>
<script type="application/json" id="gastos-por-categoria">{!! json_encode($gastosPorCategoria) !!}</script>
<script type="application/json" id="comparativa-mensual">{!! json_encode($comparativaMensual) !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para los gráficos
    const ventasData = JSON.parse(document.getElementById('ventas-por-mes').textContent || '[]');
    const gastosData = JSON.parse(document.getElementById('gastos-por-categoria').textContent || '[]');
    const comparativaData = JSON.parse(document.getElementById('comparativa-mensual').textContent || '[]');

    // Gráfico de Tendencia de Ventas Anuales
    new Chart(document.getElementById('ventasAnualesChart'), {
        type: 'line',
        data: {
            labels: ventasData.map(item => item.mes),
            datasets: [{
                label: 'Total Ventas',
                data: ventasData.map(item => item.total_ventas),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: 'Número de Transacciones',
                data: ventasData.map(item => item.total_transacciones),
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Monto Total ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Número de Transacciones'
                    }
                }
            }
        }
    });

    // Gráfico de Gastos por Categoría
    new Chart(document.getElementById('gastosCategoriaChart'), {
        type: 'pie',
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

    // Gráfico Comparativa Ingresos vs Gastos
    new Chart(document.getElementById('comparativaChart'), {
        type: 'bar',
        data: {
            labels: comparativaData.map(item => item.mes),
            datasets: [{
                label: 'Ingresos',
                data: comparativaData.map(item => item.ingresos),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }, {
                label: 'Gastos',
                data: comparativaData.map(item => item.gastos),
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Monto ($)'
                    }
                }
            }
        }
    });
});
</script>
@endsection