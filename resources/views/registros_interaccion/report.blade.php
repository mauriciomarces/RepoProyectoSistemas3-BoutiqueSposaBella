@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Encabezado del reporte -->
    <div class="row mb-4 print-header">
        <div class="col-12 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="print-logo mb-3" style="height: 100px">
            <h1 style="color: #8E805E">Reporte de Registros de Interacciones</h1>
            <p class="text-muted">
                Periodo: {{ $filtros['fecha_desde'] ?: 'Sin límite' }} al {{ $filtros['fecha_hasta'] ?: now()->toDateString() }}
            </p>
        </div>
    </div>

    <!-- Resumen de registros -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Total Registros</h5>
                    <h3 class="text-primary mb-0">{{ $registros->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Eliminaciones</h5>
                    <h3 class="text-danger mb-0">{{ $registros->where('accion', 'delete')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3" style="color: #8E805E">Empleados Activos</h5>
                    <h3 class="text-success mb-0">{{ $registros->pluck('empleado_id')->unique()->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Descripción</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registros as $registro)
                <tr class="{{ $registro->accion === 'delete' ? 'table-danger' : '' }}">
                    <td>{{ $registro->empleado->nombre ?? 'N/A' }}</td>
                    <td>
                        @if($registro->accion === 'delete' && $registro->modulo === 'empleados')
                        Despedido
                        @elseif($registro->accion === 'delete')
                        Eliminado
                        @else
                        {{ $acciones[$registro->accion] ?? ucfirst($registro->accion) }}
                        @endif
                    </td>
                    <td>{{ ucfirst($registro->modulo) }}</td>
                    <td>{{ $registro->descripcion }}</td>
                    <td>{{ $registro->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No se encontraron registros.</td>
                </tr>
                @endforelse
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

        .table-danger {
            background-color: #f8d7da !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @page {
            margin: 1cm;
            size: portrait;
        }
    }
</style>

@endsection

@section('scripts')
@if(request()->query('print'))
<script>
    // Esperar a que la ventana cargue y luego imprimir
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
</script>
@endif
@endsection