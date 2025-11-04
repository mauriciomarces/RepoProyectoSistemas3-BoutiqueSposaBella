@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Reporte Mensual - {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('F Y') }}</h2>

    <!-- Resumen -->
    <div class="card mb-4">
        <div class="card-header">
            Resumen Financiero
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h6>Total Ventas</h6>
                        <h4 class="text-success">Bs. {{ number_format($resumen['total_ventas'], 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6>Total Compras</h6>
                        <h4 class="text-primary">Bs. {{ number_format($resumen['total_compras'], 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6>Total Gastos</h6>
                        <h4 class="text-danger">Bs. {{ number_format($resumen['total_gastos'], 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6>Balance</h6>
                        <h4 class="{{ $resumen['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Bs. {{ number_format($resumen['balance'], 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs para detalles -->
    <ul class="nav nav-tabs" id="detallesTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="ventas-tab" data-bs-toggle="tab" href="#ventas" role="tab">
                Ventas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="compras-tab" data-bs-toggle="tab" href="#compras" role="tab">
                Compras
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="gastos-tab" data-bs-toggle="tab" href="#gastos" role="tab">
                Gastos
            </a>
        </li>
    </ul>

    <div class="tab-content" id="detallesTabsContent">
        <!-- Ventas -->
        <div class="tab-pane fade show active" id="ventas" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Empleado</th>
                            <th>Concepto</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventas as $venta)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $venta->referencia ?? ($venta->descripcion ?? 'N/A') }}</td>
                            <td>{{ $venta->empleado->nombre ?? 'N/A' }}</td>
                            <td>{{ $venta->concepto ?? ($venta->descripcion ?? '') }}</td>
                            <td class="text-right">Bs. {{ number_format($venta->monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compras -->
        <div class="tab-pane fade" id="compras" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Empleado</th>
                            <th>Concepto</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compras as $compra)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $compra->referencia ?? ($compra->descripcion ?? 'N/A') }}</td>
                            <td>{{ $compra->empleado->nombre ?? 'N/A' }}</td>
                            <td>{{ $compra->concepto ?? ($compra->descripcion ?? '') }}</td>
                            <td class="text-right">Bs. {{ number_format($compra->monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gastos -->
        <div class="tab-pane fade" id="gastos" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Categoría</th>
                            <th>Empleado</th>
                            <th>Concepto</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gastos as $gasto)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $gasto->categoria ?? 'N/A' }}</td>
                            <td>{{ $gasto->empleado->nombre ?? 'N/A' }}</td>
                            <td>{{ $gasto->concepto ?? ($gasto->descripcion ?? '') }}</td>
                            <td class="text-right">Bs. {{ number_format($gasto->monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection