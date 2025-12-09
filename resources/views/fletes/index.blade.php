@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-truck me-2"></i>Gestión de Fletes</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('fletes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Crear Nuevo Flete
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente / Destinatario</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fletes as $flete)
                        <tr>
                            <td>{{ $flete->id }}</td>
                            <td>
                                <i class="fas fa-user text-primary me-1"></i>
                                <strong>{{ $flete->cliente_nombre }}</strong>
                                @if($flete->cliente_correo)
                                <br><small class="text-muted">{{ $flete->cliente_correo }}</small>
                                @endif
                            </td>
                            <td>
                                <span title="{{ $flete->direccion }}">
                                    {{ Str::limit($flete->direccion, 30) }}
                                </span>
                            </td>
                            <td>{{ $flete->telefono ?? 'No especificado' }}</td>
                            <td>
                                @if($flete->descripcion)
                                <span title="{{ $flete->descripcion }}">
                                    {{ Str::limit($flete->descripcion, 50) }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($flete->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-info btn-detalle"
                                        data-flete-id="{{ $flete->id }}"
                                        title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('fletes.edit', $flete->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fletes.destroy', $flete->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Está seguro de eliminar este flete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No hay fletes registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalle -->
<div class="modal fade" id="detalleFleteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8E805E; color: #fff;">
                <h5 class="modal-title">
                    <i class="fas fa-truck me-2"></i>Detalle del Flete
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detalleModal = new bootstrap.Modal(document.getElementById('detalleFleteModal'));
        const modalBody = document.getElementById('modalBody');

        // Manejar clic en botones de detalle
        document.querySelectorAll('.btn-detalle').forEach(btn => {
            btn.addEventListener('click', function() {
                const fleteId = this.getAttribute('data-flete-id');
                loadFleteDetalle(fleteId);
            });
        });

        function loadFleteDetalle(fleteId) {
            // Mostrar loading
            modalBody.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
        `;

            detalleModal.show();

            // Cargar datos
            fetch(`/fletes/${fleteId}`)
                .then(response => response.json())
                .then(flete => {
                    const html = `
                    <div class="row g-4">
                        <!-- Información del Flete -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-shipping-fast me-2"></i>Información del Flete
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">ID del Flete</label>
                            <p class="mb-0 fw-medium">#${flete.id}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Fecha de Registro</label>
                            <p class="mb-0 fw-medium">${new Date(flete.created_at).toLocaleString('es-ES')}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Cliente / Destinatario</label>
                            <p class="mb-0 fw-medium">${flete.cliente_nombre}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Teléfono de Contacto</label>
                            <p class="mb-0 fw-medium">${flete.telefono || 'No especificado'}</p>
                        </div>
                        
                        <div class="col-12">
                            <label class="text-muted small mb-1">Dirección de Entrega</label>
                            <p class="mb-0 fw-medium">${flete.direccion}</p>
                        </div>
                        
                        ${flete.descripcion ? `
                        <div class="col-12">
                            <label class="text-muted small mb-1">Descripción / Notas</label>
                            <p class="mb-0">${flete.descripcion}</p>
                        </div>
                        ` : ''}
                        
                        <!-- Información del Cliente -->
                        <div class="col-12">
                            <hr>
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Datos del Cliente
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Nombre</label>
                            <p class="mb-0 fw-medium">${flete.cliente_nombre}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Correo</label>
                            <p class="mb-0 fw-medium">${flete.cliente_correo || 'No especificado'}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Teléfono del Cliente</label>
                            <p class="mb-0 fw-medium">${flete.cliente_telefono || 'No especificado'}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Dirección del Cliente</label>
                            <p class="mb-0 fw-medium">${flete.cliente_direccion || 'No especificada'}</p>
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
                        Error al cargar los detalles del flete
                    </div>
                `;
                });
        }
    });
</script>
@endsection