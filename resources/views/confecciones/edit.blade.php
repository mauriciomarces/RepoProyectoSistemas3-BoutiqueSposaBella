@extends('layouts.app')

@section('styles')
<style>
    .btn-custom-primary {
        background-color: #8E805E;
        color: white;
        border: none;
    }

    .btn-custom-primary:hover {
        background-color: #7a6f52;
        color: white;
    }

    .btn-custom-secondary {
        background-color: #C1BAA2;
        color: #333;
        border: none;
    }

    .btn-custom-secondary:hover {
        background-color: #afa593;
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #8E805E; color: white;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Confección #{{ $confeccion->ID_confeccion }}
                    </h3>
                </div>
                <div class="card-body" style="background-color: #EDEEE8;">

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

                    <form action="{{ route('confecciones.update', $confeccion->ID_confeccion) }}" method="POST" id="confeccionForm">
                        @csrf
                        @method('PUT')

                        <!-- Selector de Cliente (Pre-seleccionado) -->
                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2" style="color: #8E805E;">
                                <i class="fas fa-user me-2"></i>Cliente
                            </h5>

                            <div class="row">
                                <div class="col-md-12">

                                    <!-- Cliente seleccionado actual -->
                                    <div id="selectedClienteInfo" class="alert alert-success d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><i class="fas fa-user-check me-2"></i>Cliente Actual:</strong>
                                            <span id="selectedClienteNombre" class="ms-2">
                                                {{ $confeccion->cliente->nombre ?? 'N/A' }}
                                                @if($confeccion->cliente && $confeccion->cliente->correo)
                                                ({{ $confeccion->cliente->correo }})
                                                @endif
                                            </span>
                                            <input type="hidden" name="ID_cliente" id="clienteId" value="{{ old('ID_cliente', $confeccion->ID_cliente) }}" required>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="enableSearch()">
                                            <i class="fas fa-exchange-alt me-1"></i> Cambiar Cliente
                                        </button>
                                    </div>

                                    <!-- Búsqueda (oculta inicialmente) -->
                                    <div id="searchContainer" class="position-relative" style="display: none;">
                                        <label class="form-label">Buscar Nuevo Cliente</label>
                                        <input type="text"
                                            class="form-control"
                                            id="clienteSearch"
                                            placeholder="Escriba el nombre del cliente..."
                                            autocomplete="off">

                                        <div id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>

                                        <button type="button" class="btn btn-sm btn-link mt-1" onclick="cancelSearch()">
                                            Cancelar cambio
                                        </button>
                                    </div>
                                    @error('ID_cliente')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Estado y Transacción -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="mb-3 border-bottom pb-2" style="color: #8E805E;">
                                    <i class="fas fa-tasks me-2"></i>Estado del Pedido
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado Actual</label>
                                <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                                    <option value="pendiente" {{ old('estado', $confeccion->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="en_proceso" {{ old('estado', $confeccion->estado) == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="completado" {{ old('estado', $confeccion->estado) == 'completado' ? 'selected' : '' }}>Completado (Genera Ingreso)</option>
                                    <option value="cancelado" {{ old('estado', $confeccion->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado (Genera Pérdida)</option>
                                </select>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Al cambiar a <strong>Completado</strong> o <strong>Cancelado</strong>, se generará el movimiento financiero automáticamente.
                                </small>
                            </div>
                            @if($confeccion->ID_transaccion)
                            <div class="col-md-6">
                                <label class="form-label">Transacción Asociada</label>
                                <div class="alert alert-info py-2">
                                    <i class="fas fa-receipt me-2"></i>
                                    Movimiento ID: #{{ $confeccion->ID_transaccion }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Información de Confección -->
                        <h5 class="mb-3 border-bottom pb-2" style="color: #8E805E;">
                            <i class="fas fa-tshirt me-2"></i>Detalles de la Confección
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Tipo de Confección <span class="text-danger">*</span></label>
                                <input type="text" name="tipo_confeccion" class="form-control"
                                    value="{{ old('tipo_confeccion', $confeccion->tipo_confeccion) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="{{ old('fecha_inicio', $confeccion->fecha_inicio ? $confeccion->fecha_inicio->format('Y-m-d') : '') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fecha Entrega</label>
                                <input type="date" name="fecha_entrega" class="form-control"
                                    value="{{ old('fecha_entrega', $confeccion->fecha_entrega ? $confeccion->fecha_entrega->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Costo Estimado (Bs.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" step="0.01" name="costo" class="form-control"
                                        value="{{ old('costo', $confeccion->costo) }}">
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3 border-bottom pb-2" style="color: #8E805E;">
                            <i class="fas fa-ruler-combined me-2"></i>Medidas (cm)
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Busto</label>
                                <input type="number" step="0.1" name="medidas[busto]" class="form-control"
                                    value="{{ old('medidas.busto', $confeccion->medidas['busto'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cintura</label>
                                <input type="number" step="0.1" name="medidas[cintura]" class="form-control"
                                    value="{{ old('medidas.cintura', $confeccion->medidas['cintura'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cadera</label>
                                <input type="number" step="0.1" name="medidas[cadera]" class="form-control"
                                    value="{{ old('medidas.cadera', $confeccion->medidas['cadera'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Largo Talle</label>
                                <input type="number" step="0.1" name="medidas[largo_talle]" class="form-control"
                                    value="{{ old('medidas.largo_talle', $confeccion->medidas['largo_talle'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Largo Falda</label>
                                <input type="number" step="0.1" name="medidas[largo_falda]" class="form-control"
                                    value="{{ old('medidas.largo_falda', $confeccion->medidas['largo_falda'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ancho Espalda</label>
                                <input type="number" step="0.1" name="medidas[ancho_espalda]" class="form-control"
                                    value="{{ old('medidas.ancho_espalda', $confeccion->medidas['ancho_espalda'] ?? '') }}">
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('confecciones.index') }}" class="btn btn-custom-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-custom-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Actualizar Confección
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
    const searchContainer = document.getElementById('searchContainer');
    const selectedInfo = document.getElementById('selectedClienteInfo');
    const selectedNombre = document.getElementById('selectedClienteNombre');

    function enableSearch() {
        selectedInfo.style.display = 'none';
        searchContainer.style.display = 'block';
        searchInput.focus();
    }

    function cancelSearch() {
        searchContainer.style.display = 'none';
        selectedInfo.style.display = 'flex'; // Use flex to match bootstrap d-flex
        searchInput.value = '';
        searchResults.style.display = 'none';
    }

    function selectCliente(id, nombre, correo) {
        clienteIdInput.value = id;
        selectedNombre.textContent = nombre + (correo ? ' (' + correo + ')' : '');
        cancelSearch();
    }

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
                .then(clientes => {
                    let html = '';
                    if (clientes.length === 0) {
                        html = '<div class="list-group-item text-muted">No se encontraron clientes</div>';
                    } else {
                        clientes.forEach(cliente => {
                            html += `
                                <a href="#" class="list-group-item list-group-item-action" 
                                   onclick="selectCliente(${cliente.ID_cliente}, '${escapeHtml(cliente.nombre)}', '${escapeHtml(cliente.correo || '')}'); return false;">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${escapeHtml(cliente.nombre)}</h6>
                                    </div>
                                    <small class="text-muted">
                                        ${cliente.telefono ? '<i class="fas fa-phone me-1"></i>' + escapeHtml(cliente.telefono) : ''}
                                    </small>
                                </a>
                            `;
                        });
                    }
                    searchResults.innerHTML = html;
                    searchResults.style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }, 300);
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
</script>
@endsection