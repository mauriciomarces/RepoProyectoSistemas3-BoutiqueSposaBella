<div class="cliente-detalle p-2">
    <div class="mb-3">
        <h5 class="text-center mb-3" style="color: #8E805E;">
            {{ $cliente->nombre }} {{ $cliente->apellido }}
        </h5>
    </div>
    
    <div class="info-section mb-3">
        <h6 class="section-title">Información de Contacto</h6>
        <div class="row g-2">
            <div class="col-md-6">
                <div class="info-item">
                    <small class="text-muted">Email</small>
                    <p class="mb-0">{{ $cliente->email }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-item">
                    <small class="text-muted">Teléfono</small>
                    <p class="mb-0">{{ $cliente->telefono }}</p>
                </div>
            </div>
        </div>

        @if($cliente->direccion)
        <div class="row g-2 mt-2">
            <div class="col-12">
                <div class="info-item">
                    <small class="text-muted">Dirección</small>
                    <p class="mb-0">{{ $cliente->direccion }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="info-section mb-3">
        <h6 class="section-title">Medidas Corporales</h6>
        <div class="row g-2">
            <div class="col-4">
                <div class="medida-card text-center">
                    <small class="text-muted d-block">Busto</small>
                    <strong class="fs-5" style="color: #8E805E;">{{ $cliente->busto }}</strong>
                    <small class="text-muted d-block">cm</small>
                </div>
            </div>
            <div class="col-4">
                <div class="medida-card text-center">
                    <small class="text-muted d-block">Cintura</small>
                    <strong class="fs-5" style="color: #8E805E;">{{ $cliente->cintura }}</strong>
                    <small class="text-muted d-block">cm</small>
                </div>
            </div>
            <div class="col-4">
                <div class="medida-card text-center">
                    <small class="text-muted d-block">Cadera</small>
                    <strong class="fs-5" style="color: #8E805E;">{{ $cliente->cadera }}</strong>
                    <small class="text-muted d-block">cm</small>
                </div>
            </div>
        </div>
    </div>

    @if(isset($cliente->historial_compras) && is_array($cliente->historial_compras) && count($cliente->historial_compras) > 0)
    <div class="info-section">
        <h6 class="section-title">Historial de Compras</h6>
        <div class="list-group list-group-flush">
            @foreach($cliente->historial_compras as $index => $compra)
            <div class="list-group-item px-0">
                <small class="text-muted">Compra #{{ $index + 1 }}</small>
                <p class="mb-0">
                    @if(is_array($compra))
                        @foreach($compra as $key => $value)
                            <span class="d-block"><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</span>
                        @endforeach
                    @else
                        {{ $compra }}
                    @endif
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="alert alert-info mb-0">
        <small>Este cliente aún no tiene compras registradas.</small>
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