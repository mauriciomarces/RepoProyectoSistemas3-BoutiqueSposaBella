<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h6 class="fw-bold" style="color: #8E805E;">Información Personal</h6>
            <table class="table table-sm">
                <tr>
                    <td class="fw-bold">Nombre:</td>
                    <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Email:</td>
                    <td>{{ $cliente->email }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Teléfono:</td>
                    <td>{{ $cliente->telefono }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Dirección:</td>
                    <td>{{ $cliente->direccion ?? 'No registrada' }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <h6 class="fw-bold" style="color: #8E805E;">Medidas</h6>
            <table class="table table-sm">
                <tr>
                    <td class="fw-bold">Busto:</td>
                    <td>{{ $cliente->medidas->busto }} cm</td>
                </tr>
                <tr>
                    <td class="fw-bold">Cintura:</td>
                    <td>{{ $cliente->medidas->cintura }} cm</td>
                </tr>
                <tr>
                    <td class="fw-bold">Cadera:</td>
                    <td>{{ $cliente->medidas->cadera }} cm</td>
                </tr>
            </table>
        </div>
    </div>

    <hr class="my-3">

    <h6 class="fw-bold mb-3" style="color: #8E805E;">
        <i class="bi bi-bag-check-fill me-2"></i>Historial de Compras
    </h6>

    @if(count($cliente->historial_compras) > 0)
        <div class="accordion" id="historialAccordion">
            @foreach($cliente->historial_compras as $index => $compra)
            <div class="accordion-item mb-2">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#compra{{ $index }}" aria-expanded="false">
                        <div class="d-flex justify-content-between w-100 pe-3">
                            <span>
                                <strong>Pedido #{{ $compra->id }}</strong> - {{ date('d/m/Y', strtotime($compra->fecha)) }}
                            </span>
                            <span class="badge bg-{{ $compra->estado == 'entregado' ? 'success' : ($compra->estado == 'pendiente' ? 'warning' : 'info') }}">
                                {{ ucfirst($compra->estado) }}
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="compra{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#historialAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tipo:</strong> {{ $compra->tipo }}</p>
                                <p><strong>Fecha de Entrega:</strong> {{ date('d/m/Y', strtotime($compra->fecha_entrega)) }}</p>
                                <p><strong>Estado de Pago:</strong> 
                                    <span class="badge bg-{{ $compra->estado_pago == 'completado' ? 'success' : 'warning' }}">
                                        {{ ucfirst($compra->estado_pago) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Monto Total:</strong> <span class="text-success fw-bold">Bs. {{ number_format($compra->monto_total, 2) }}</span></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="fw-bold mb-2">Productos:</h6>
                        <ul class="list-group">
                            @foreach($compra->productos as $producto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $producto->nombre }}</span>
                                <span>
                                    <span class="badge bg-secondary">Cantidad: {{ $producto->cantidad }}</span>
                                    <span class="badge bg-primary">Bs. {{ number_format($producto->precio_unitario, 2) }}</span>
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>Este cliente aún no tiene compras registradas.
        </div>
    @endif
</div>

<style>
    .cliente-detalle .section-title {
        font-weight: 600;
        color: #8E805E;
        border-bottom: 2px solid #EDEEE8;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .cliente-detalle .info-item {
        background-color: #F8F9FA;
        padding: 0.75rem;
        border-radius: 0.25rem;
        border-left: 3px solid #8E805E;
    }

    .cliente-detalle .info-item p {
        font-size: 0.95rem;
        color: #333;
    }

    .cliente-detalle .medida-card {
        background-color: #F8F9FA;
        padding: 1rem 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #E0E0E0;
    }

    .cliente-detalle .list-group-item {
        border-bottom: 1px solid #EDEEE8;
    }

    .cliente-detalle .list-group-item:last-child {
        border-bottom: none;
    }

    .info-section {
        margin-bottom: 1.5rem;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }
</style>