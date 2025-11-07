@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">Nuevo Cliente</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filtros -->
        <div class="filter-container">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" 
                        class="form-control" 
                        name="nombre" 
                        value="{{ request('nombre') }}"
                        placeholder="Buscar por nombre">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Busto (cm)</label>
                    <input type="number" 
                        class="form-control" 
                        name="busto" 
                        value="{{ request('busto') }}"
                        placeholder="Ej: 90" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cintura (cm)</label>
                    <input type="number" 
                        class="form-control" 
                        name="cintura" 
                        value="{{ request('cintura') }}"
                        placeholder="Ej: 70" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cadera (cm)</label>
                    <input type="number" 
                        class="form-control" 
                        name="cadera" 
                        value="{{ request('cadera') }}"
                        placeholder="Ej: 95" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
                </div>
                <div class="col-12 d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <button type="button" class="btn btn-outline-secondary" id="clearFilters">Limpiar</button>
                </div>
            </form>
        </div>

        <!-- Tabla de clientes -->
        <div id="clientsContainer" class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Busto</th>
                        <th>Cintura</th>
                        <th>Cadera</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->correo }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->busto }} cm</td>
                        <td>{{ $cliente->cintura }} cm</td>
                        <td>{{ $cliente->cadera }} cm</td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Botón Detalle -->
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm btn-detalle" 
                                        data-cliente-id="{{ $cliente->id }}"
                                        data-cliente-nombre="{{ $cliente->nombre }}"
                                        data-cliente-apellido=""
                                        data-cliente-email="{{ $cliente->email }}"
                                        data-cliente-telefono="{{ $cliente->telefono }}"
                                        data-cliente-busto="{{ $cliente->busto }}"
                                        data-cliente-cintura="{{ $cliente->cintura }}"
                                        data-cliente-cadera="{{ $cliente->cadera }}">
                                    Detalle
                                </button>

                                <!-- Botón Editar -->
                                <a href="{{ route('clientes.edit', $cliente->ID_cliente) }}"
                                   class="btn btn-primary btn-sm">
                                    Editar
                                </a>

                                <!-- Botón Eliminar -->
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-eliminar"
                                        data-cliente-id="{{ $cliente->ID_cliente }}"
                                        data-cliente-nombre="{{ $cliente->nombre }} {{ $cliente->apellido }}">
                                    Eliminar
                                </button>
                                
                                <!-- Form oculto para eliminación -->
                                <form id="delete-form-{{ $cliente->ID_cliente }}"
                                      action="{{ route('clientes.destroy', $cliente->ID_cliente) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay clientes registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para detalle de cliente -->
    <div class="modal fade" id="detalleClienteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #8E805E; color: #fff;">
                    <h5 class="modal-title" id="modalTitle">Detalle del Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #D9534F; color: #fff;">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#D9534F" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                        </svg>
                    </div>
                    <h5 class="mb-3">¿Está seguro que desea eliminar este cliente?</h5>
                    <p class="text-muted mb-1">Cliente:</p>
                    <p class="fw-bold mb-3" id="deleteClienteName" style="color: #8E805E; font-size: 1.1rem;"></p>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill flex-shrink-0 me-2" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                        </svg>
                        <small>Esta acción no se puede deshacer</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                        <i class="bi bi-trash me-1"></i>
                        Sí, Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const clearBtn = document.getElementById('clearFilters');
        const detalleModal = new bootstrap.Modal(document.getElementById('detalleClienteModal'));
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        let clienteIdToDelete = null;

        // Manejar filtros - Recarga página (método más confiable)
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });

        clearBtn.addEventListener('click', function() {
            window.location.href = '/clientes';
        });

        function applyFilters() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();

            // Solo agregar valores no vacíos
            for (let [key, value] of formData.entries()) {
                if (value.trim() !== '') {
                    params.append(key, value);
                }
            }

            // Construir URL y recargar
            const url = params.toString() ? `/clientes?${params.toString()}` : '/clientes';
            window.location.href = url;
        }

        // Manejar botones de detalle
        function attachDetalleListeners() {
            document.querySelectorAll('.btn-detalle').forEach(btn => {
                btn.addEventListener('click', function() {
                    const clienteData = {
                        id: this.getAttribute('data-cliente-id'),
                        nombre: this.getAttribute('data-cliente-nombre'),
                        apellido: this.getAttribute('data-cliente-apellido') || '',
                        email: this.getAttribute('data-cliente-email'),
                        telefono: this.getAttribute('data-cliente-telefono'),
                        busto: this.getAttribute('data-cliente-busto'),
                        cintura: this.getAttribute('data-cliente-cintura'),
                        cadera: this.getAttribute('data-cliente-cadera')
                    };
                    loadClienteDetalle(clienteData);
                });
            });
        }

        function loadClienteDetalle(clienteData) {
            const modalBody = document.getElementById('modalBody');
            
            const detalleHTML = `
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="text-white fs-4 fw-bold">${clienteData.nombre.charAt(0)}${clienteData.apellido ? clienteData.apellido.charAt(0) : ''}</span>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0">${clienteData.nombre} ${clienteData.apellido}</h5>
                                <small class="text-muted">ID: ${clienteData.id}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <hr>
                    </div>
                    
                    <div class="col-6">
                        <label class="text-muted small mb-1">Email</label>
                        <p class="mb-0 fw-medium">${clienteData.email || 'No especificado'}</p>
                    </div>
                    
                    <div class="col-6">
                        <label class="text-muted small mb-1">Teléfono</label>
                        <p class="mb-0 fw-medium">${clienteData.telefono || 'No especificado'}</p>
                    </div>
                    
                    <div class="col-12">
                        <hr>
                        <h6 class="mb-3">Medidas</h6>
                    </div>
                    
                    <div class="col-4">
                        <label class="text-muted small mb-1">Busto</label>
                        <p class="mb-0 fw-medium">${clienteData.busto} cm</p>
                    </div>
                    
                    <div class="col-4">
                        <label class="text-muted small mb-1">Cintura</label>
                        <p class="mb-0 fw-medium">${clienteData.cintura} cm</p>
                    </div>
                    
                    <div class="col-4">
                        <label class="text-muted small mb-1">Cadera</label>
                        <p class="mb-0 fw-medium">${clienteData.cadera} cm</p>
                    </div>
                </div>
            `;
            
            modalBody.innerHTML = detalleHTML;
            detalleModal.show();
        }

        // Manejar botones de eliminación
        function attachDeleteListeners() {
            document.querySelectorAll('.btn-eliminar').forEach(btn => {
                btn.addEventListener('click', function() {
                    clienteIdToDelete = this.getAttribute('data-cliente-id');
                    const clienteNombre = this.getAttribute('data-cliente-nombre');
                    document.getElementById('deleteClienteName').textContent = clienteNombre;
                    deleteModal.show();
                });
            });
        }

        // Confirmar eliminación
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (clienteIdToDelete) {
                document.getElementById('delete-form-' + clienteIdToDelete).submit();
            }
        });

        // Adjuntar listeners iniciales
        attachDetalleListeners();
        attachDeleteListeners();
    });
    </script>
@endsection