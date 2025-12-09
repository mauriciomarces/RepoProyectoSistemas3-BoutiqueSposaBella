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
                        <i class="fas fa-cut me-2"></i>Registrar Nueva Confección
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

                    <form action="{{ route('confecciones.store') }}" method="POST" id="confeccionForm">
                        @csrf

                        <!-- Selector de Cliente (REQUERIDO) -->
                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2" style="color: #8E805E;">
                                <i class="fas fa-user me-2"></i>Cliente
                            </h5>

                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Buscar Cliente <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text"
                                            class="form-control @error('ID_cliente') is-invalid @enderror"
                                            id="clienteSearch"
                                            placeholder="Escriba el nombre del cliente..."
                                            autocomplete="off">
                                        <input type="hidden" name="ID_cliente" id="clienteId" value="{{ old('ID_cliente') }}" required>

                                        <!-- Dropdown de resultados -->
                                        <div id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;"></div>
                                    </div>
                                    @error('ID_cliente')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <a href="{{ route('clientes.create') }}" class="btn btn-outline-primary w-100" target="_blank">
                                        <i class="fas fa-user-plus me-2"></i>Agregar Nuevo Cliente
                                    </a>
                                </div>
                            </div>

                            <!-- Cliente seleccionado -->
                            <div id="selectedClienteInfo" class="mt-3" style="display: none;">
                                <div class="alert alert-success d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-user-check me-2"></i>Cliente Seleccionado:</strong>
                                        <span id="selectedClienteNombre" class="ms-2"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearCliente()">
                                        <i class="fas fa-times me-1"></i> Cambiar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Confección -->
                        <h5 class="mb-3 border-bottom pb-2" style="color: #8E805E;">
                            <i class="fas fa-tshirt me-2"></i>Detalles de la Confección
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Tipo de Confección <span class="text-danger">*</span></label>
                                <input type="text" name="tipo_confeccion" class="form-control"
                                    value="{{ old('tipo_confeccion') }}" placeholder="Ej: Vestido de Novia, Traje de Gala..." required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Fecha Entrega</label>
                                <input type="date" name="fecha_entrega" class="form-control" value="{{ old('fecha_entrega') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Costo Estimado (Bs.)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Bs.</span>
                                    <input type="number" step="0.01" name="costo" class="form-control" value="{{ old('costo') }}" placeholder="0.00">
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
                                    value="{{ old('medidas.busto') }}" placeholder="0.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cintura</label>
                                <input type="number" step="0.1" name="medidas[cintura]" class="form-control"
                                    value="{{ old('medidas.cintura') }}" placeholder="0.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cadera</label>
                                <input type="number" step="0.1" name="medidas[cadera]" class="form-control"
                                    value="{{ old('medidas.cadera') }}" placeholder="0.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Largo Talle</label>
                                <input type="number" step="0.1" name="medidas[largo_talle]" class="form-control"
                                    value="{{ old('medidas.largo_talle') }}" placeholder="0.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Largo Falda</label>
                                <input type="number" step="0.1" name="medidas[largo_falda]" class="form-control"
                                    value="{{ old('medidas.largo_falda') }}" placeholder="0.0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ancho Espalda</label>
                                <input type="number" step="0.1" name="medidas[ancho_espalda]" class="form-control"
                                    value="{{ old('medidas.ancho_espalda') }}" placeholder="0.0">
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('confecciones.index') }}" class="btn btn-custom-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-custom-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Guardar Confección
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
    const selectedInfo = document.getElementById('selectedClienteInfo');
    const selectedNombre = document.getElementById('selectedClienteNombre');
    const submitBtn = document.getElementById('submitBtn');

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
                    ${cliente.telefono ? '<i class="fas fa-phone me-1"></i>' + escapeHtml(cliente.telefono) : ''}
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
    }

    function clearCliente() {
        clienteIdInput.value = '';
        selectedInfo.style.display = 'none';
        searchInput.value = '';
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Validar antes de enviar
    document.getElementById('confeccionForm').addEventListener('submit', function(e) {
        if (!clienteIdInput.value) {
            e.preventDefault();
            alert('Por favor, seleccione un cliente antes de guardar la confección.');
            searchInput.focus();
        }
    });
</script>
@endsection