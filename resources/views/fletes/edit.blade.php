@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-edit me-2"></i>Editar Flete #{{ $flete->id }}</h1>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Por favor, corrija los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('fletes.update', $flete->id) }}" method="POST" id="fleteForm">
                @csrf
                @method('PUT')

                <!-- Selector de Cliente (REQUERIDO) -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-user me-2"></i>Cliente / Destinatario
                        <span class="text-danger">*</span>
                    </h5>
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Buscar Cliente</label>
                            <div class="position-relative">
                                <input type="text"
                                    class="form-control @error('cliente_id') is-invalid @enderror"
                                    id="clienteSearch"
                                    placeholder="Escriba el nombre del cliente..."
                                    autocomplete="off">
                                <input type="hidden" name="cliente_id" id="clienteId" value="{{ old('cliente_id', $flete->cliente_id) }}" required>

                                <!-- Dropdown de resultados -->
                                <div id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>
                            </div>
                            @error('cliente_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Comience a escribir para buscar clientes existentes
                            </small>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <a href="{{ route('clientes.create') }}" class="btn btn-outline-primary w-100" target="_blank">
                                <i class="fas fa-user-plus me-2"></i>Agregar Nuevo Cliente
                            </a>
                        </div>
                    </div>

                    <!-- Cliente seleccionado -->
                    <div id="selectedClienteInfo" class="mt-3" style="display: {{ $flete->cliente_id ? 'block' : 'none' }};">
                        <div class="alert alert-success">
                            <strong><i class="fas fa-user-check me-2"></i>Cliente Seleccionado:</strong>
                            <span id="selectedClienteNombre">
                                @if($flete->cliente_id)
                                {{ DB::table('cliente')->where('ID_cliente', $flete->cliente_id)->value('nombre') }}
                                @endif
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-secondary float-end" onclick="clearCliente()">
                                <i class="fas fa-times"></i> Cambiar
                            </button>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Información del Flete -->
                <h5 class="mb-3"><i class="fas fa-shipping-fast me-2"></i>Información del Flete</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono de Contacto</label>
                        <input type="tel"
                            name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono', $flete->telefono) }}"
                            pattern="^[67]\d{7}$"
                            maxlength="8"
                            placeholder="Ej: 77123456"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 8);">
                        @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Debe comenzar con 6 o 7 y tener 8 dígitos</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dirección de Entrega <span class="text-danger">*</span></label>
                        <textarea name="direccion"
                            class="form-control @error('direccion') is-invalid @enderror"
                            rows="2"
                            required
                            placeholder="Dirección completa de entrega">{{ old('direccion', $flete->direccion) }}</textarea>
                        @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción / Notas</label>
                    <textarea name="descripcion"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        rows="3"
                        maxlength="1000"
                        placeholder="Detalles adicionales sobre el flete (opcional)">{{ old('descripcion', $flete->descripcion) }}</textarea>
                    @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Máximo 1000 caracteres</small>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Actualizar Flete
                    </button>
                    <a href="{{ route('fletes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let searchTimeout = null;
    const searchInput = document.getElementById('clienteSearch');
    const searchResults = document.getElementById('searchResults');
    const clienteIdInput = document.getElementById('clienteId');
    const selectedInfo = document.getElementById('selectedClienteInfo');
    const selectedNombre = document.getElementById('selectedClienteNombre');
    const submitBtn = document.getElementById('submitBtn');

    // Si ya hay cliente seleccionado, habilitar botón
    if (clienteIdInput.value) {
        submitBtn.disabled = false;
    }

    // Búsqueda en tiempo real
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/clientes/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchResults.innerHTML = '<div class="list-group-item text-danger">Error al buscar clientes</div>';
                    searchResults.style.display = 'block';
                });
        }, 300);
    });

    function displayResults(clientes) {
        if (clientes.length === 0) {
            searchResults.innerHTML = `
            <div class="list-group-item">
                <div class="text-muted">No se encontraron clientes</div>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    ¿Cliente nuevo? 
                    <a href="{{ route('clientes.create') }}" target="_blank">Agregar cliente</a>
                </small>
            </div>
        `;
            searchResults.style.display = 'block';
            return;
        }

        let html = '';
        clientes.forEach(cliente => {
            html += `
            <a href="#" class="list-group-item list-group-item-action" onclick="selectCliente(${cliente.ID_cliente}, '${escapeHtml(cliente.nombre)}', '${escapeHtml(cliente.correo || '')}'); return false;">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${escapeHtml(cliente.nombre)}</h6>
                </div>
                <small class="text-muted">
                    ${cliente.correo ? '<i class="fas fa-envelope me-1"></i>' + escapeHtml(cliente.correo) : ''}
                    ${cliente.telefono ? '<i class="fas fa-phone ms-2 me-1"></i>' + escapeHtml(cliente.telefono) : ''}
                </small>
            </a>
        `;
        });

        searchResults.innerHTML = html;
        searchResults.style.display = 'block';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function selectCliente(id, nombre, correo) {
        clienteIdInput.value = id;
        searchInput.value = '';
        searchResults.style.display = 'none';

        selectedNombre.textContent = nombre + (correo ? ' (' + correo + ')' : '');
        selectedInfo.style.display = 'block';
        submitBtn.disabled = false;
    }

    function clearCliente() {
        clienteIdInput.value = '';
        selectedInfo.style.display = 'none';
        searchInput.value = '';
        submitBtn.disabled = true;
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Validar antes de enviar
    document.getElementById('fleteForm').addEventListener('submit', function(e) {
        if (!clienteIdInput.value) {
            e.preventDefault();
            alert('Por favor, seleccione un cliente antes de actualizar el flete.');
            searchInput.focus();
        }
    });
</script>
@endsection