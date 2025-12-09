@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #8E805E; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-tshirt me-2"></i>Detalle de Confección #{{ $confeccion->ID_confeccion }}
                    </h5>
                    <a href="{{ route('confecciones.index') }}" class="btn btn-sm btn-light text-dark">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
                <div class="card-body" style="background-color: #f8f9fa;">

                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Información del Cliente</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $confeccion->cliente->nombre ?? 'N/A' }}</h5>
                                    <p class="text-muted mb-0 small">{{ $confeccion->cliente->correo ?? '' }}</p>
                                    <p class="text-muted mb-0 small">{{ $confeccion->cliente->telefono ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Estado del Pedido</h6>
                            <span class="badge bg-{{ match($confeccion->estado) { 'completado' => 'success', 'cancelado' => 'danger', 'pendiente' => 'warning', 'en_proceso' => 'info', default => 'secondary' } }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $confeccion->estado)) }}
                            </span>
                            @if($confeccion->ID_transaccion)
                            <div class="mt-3 alert alert-info py-2 small">
                                <i class="fas fa-check-circle me-1"></i>
                                Movimiento Financiero Registrado
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Detalles</h6>
                            <table class="table table-bordered bg-white">
                                <tr>
                                    <th width="30%">Tipo</th>
                                    <td>{{ $confeccion->tipo_confeccion }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Inicio</th>
                                    <td>{{ $confeccion->fecha_inicio ? $confeccion->fecha_inicio->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha Entrega</th>
                                    <td>{{ $confeccion->fecha_entrega ? $confeccion->fecha_entrega->format('d/m/Y') : 'Sin fecha' }}</td>
                                </tr>
                                <tr>
                                    <th>Costo</th>
                                    <td class="fw-bold">Bs. {{ number_format($confeccion->costo, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if(!empty($confeccion->medidas))
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Medidas</h6>
                            <div class="card card-body border-0 bg-white">
                                <div class="row">
                                    @foreach($confeccion->medidas as $key => $value)
                                    <div class="col-md-4 mb-3">
                                        <label class="text-muted small text-capitalize">{{ str_replace('_', ' ', $key) }}</label>
                                        <div class="fw-bold">{{ $value }} cm</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="text-end mt-4">
                        <a href="{{ route('confecciones.edit', $confeccion->ID_confeccion) }}" class="btn btn-custom-primary">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection