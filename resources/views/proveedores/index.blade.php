@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-truck me-2" style="color: #8E805E;"></i>Gestión de Proveedores</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('proveedores.create') }}" class="btn btn-custom-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Proveedor
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filtros de Búsqueda -->
    <div class="card mb-4" style="background-color: #EDEEE8;">
        <div class="card-header" style="background-color: #8E805E; color: white;">
            <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('proveedores.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Proveedor</label>
                        <input type="text"
                            name="nombre"
                            id="filterNombre"
                            class="form-control"
                            placeholder="Buscar por nombre..."
                            value="{{ request('nombre') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Proveedor</label>
                        <input type="text"
                            name="tipo_proveedor"
                            id="filterTipo"
                            class="form-control"
                            placeholder="Ej: Telas, Accesorios..."
                            value="{{ request('tipo_proveedor') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                            name="telefono"
                            id="filterTelefono"
                            class="form-control"
                            placeholder="Buscar por teléfono..."
                            value="{{ request('telefono') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-custom-secondary w-100" onclick="limpiarFiltros()">
                            <i class="fas fa-eraser me-2"></i>Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Proveedores -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #C1BAA2;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProveedores">
                        @include('proveedores.partials.table_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalle -->
<div class="modal fade" id="detalleProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8E805E; color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle del Proveedor
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

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

@section('scripts')
<script>
    let searchTimeout = null;
    const form = document.getElementById('filterForm');
    const tbody = document.getElementById('tablaProveedores');

    // Búsqueda en tiempo real asíncrona
    document.querySelectorAll('#filterNombre, #filterTipo, #filterTelefono').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 300);
        });
    });

    function performSearch() {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        // Opacidad para indicar carga
        if (tbody) tbody.style.opacity = '0.5';

        fetch(`{{ route('proveedores.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                if (tbody) {
                    tbody.innerHTML = html;
                    tbody.style.opacity = '1';
                    // Re-asignar eventos a los botones recién cargados
                    attachDetailListeners();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (tbody) tbody.style.opacity = '1';
            });
    }

    // Limpiar filtros (AJAX)
    // Se expone globalmente ya que es llamada desde onclick en el HTML
    window.limpiarFiltros = function() {
        document.getElementById('filterNombre').value = '';
        document.getElementById('filterTipo').value = '';
        document.getElementById('filterTelefono').value = '';
        performSearch();
    }

    // Modal de detalle logic
    let detalleModal;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('detalleProveedorModal');
        if (modalEl) {
            detalleModal = new bootstrap.Modal(modalEl);
            attachDetailListeners();
        }
    });

    function attachDetailListeners() {
        document.querySelectorAll('.btn-detalle').forEach(btn => {
            btn.addEventListener('click', function() {
                const proveedorId = this.getAttribute('data-proveedor-id');
                loadProveedorDetalle(proveedorId);
            });
        });
    }

    function loadProveedorDetalle(proveedorId) {
        const modalBody = document.getElementById('modalBody');
        if (!modalBody) return;

        modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    `;

        if (detalleModal) detalleModal.show();

        fetch(`/proveedores/${proveedorId}/show`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(proveedor => {
                const html = `
                <div class="row g-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3" style="color: #8E805E;">
                            <i class="fas fa-building me-2"></i>Información del Proveedor
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">ID del Proveedor</label>
                        <p class="mb-0 fw-medium">#${proveedor.ID_proveedor}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Tipo de Proveedor</label>
                        <p class="mb-0">
                            <span class="badge" style="background-color: #8E805E; color: white;">
                                ${proveedor.tipo_proveedor}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-12">
                        <label class="text-muted small mb-1">Nombre</label>
                        <p class="mb-0 fw-medium">${proveedor.nombre}</p>
                    </div>
                    
                    <div class="col-12">
                        <label class="text-muted small mb-1">Dirección</label>
                        <p class="mb-0">${proveedor.direccion || 'No especificada'}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Teléfono</label>
                        <p class="mb-0">${proveedor.telefono || 'No especificado'}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="text-muted small mb-1">Fecha de Registro</label>
                        <p class="mb-0">${proveedor.created_at ? new Date(proveedor.created_at).toLocaleString('es-ES') : 'N/A'}</p>
                    </div>
                </div>
            `;

                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles del proveedor
                </div>
            `;
            });
    }
</script>
@endsection