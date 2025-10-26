<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Clientes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EDEEE8;
            font-family: 'Playfair Display', serif;
        }
        .navbar {
            background-color: #EDEEE8;
            border-bottom: 2px solid #C1BAA2;
            margin-bottom: 2rem;
        }
        .filter-container {
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #8E805E;
            border-color: #8E805E;
        }
        .btn-primary:hover {
            background-color: #A19E94;
            border-color: #A19E94;
        }
        .btn-outline-secondary {
            border-color: #8E805E;
            color: #8E805E;
        }
        .btn-outline-secondary:hover {
            background-color: #8E805E;
            color: #fff;
        }
        .table-container {
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        thead {
            background-color: #8E805E;
            color: #fff;
        }
        .btn-danger {
            background-color: #D9534F;
            border-color: #D43F3A;
        }
        .btn-danger:hover {
            background-color: #C9302C;
            border-color: #AC2925;
        }
        .modal-content {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .btn-close-white {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container position-relative">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60">
            </a>
            <h1 class="navbar-title position-absolute start-50 translate-middle-x mb-0">Gestión de Clientes</h1>
        </div>
    </nav>

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
                    <input type="text" class="form-control" name="nombre" placeholder="Buscar por nombre">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Busto (cm)</label>
                    <input type="number" class="form-control" name="busto" placeholder="Ej: 90">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cintura (cm)</label>
                    <input type="number" class="form-control" name="cintura" placeholder="Ej: 70">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cadera (cm)</label>
                    <input type="number" class="form-control" name="cadera" placeholder="Ej: 95">
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
                        <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->busto }} cm</td>
                        <td>{{ $cliente->cintura }} cm</td>
                        <td>{{ $cliente->cadera }} cm</td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Botón Detalle -->
                                <button type="button" 
                                        class="btn btn-outline-secondary btn-sm btn-detalle" 
                                        data-cliente-id="{{ $cliente->id }}">
                                    Detalle
                                </button>

                                <!-- Botón Editar -->
                                <a href="{{ route('clientes.edit', $cliente->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    Editar
                                </a>

                                <!-- Botón Eliminar -->
                                <button type="button" 
                                        class="btn btn-danger btn-sm btn-eliminar" 
                                        data-cliente-id="{{ $cliente->id }}"
                                        data-cliente-nombre="{{ $cliente->nombre }} {{ $cliente->apellido }}">
                                    Eliminar
                                </button>
                                
                                <!-- Form oculto para eliminación -->
                                <form id="delete-form-{{ $cliente->id }}" 
                                      action="{{ route('clientes.destroy', $cliente->id) }}" 
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const clientsContainer = document.getElementById('clientsContainer');
            const clearBtn = document.getElementById('clearFilters');
            const detalleModal = new bootstrap.Modal(document.getElementById('detalleClienteModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            let clienteIdToDelete = null;

            // Manejar filtros
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                applyFilters();
            });

            clearBtn.addEventListener('click', function() {
                filterForm.reset();
                applyFilters();
            });

            function applyFilters() {
                const params = new URLSearchParams(new FormData(filterForm)).toString();
                fetch(`/clientes?${params}`, { 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(r => r.text())
                .then(html => {
                    clientsContainer.innerHTML = html;
                    // Re-attach event listeners to new buttons
                    attachDetalleListeners();
                    attachDeleteListeners();
                })
                .catch(err => console.error('Error al filtrar:', err));
            }

            // Manejar botones de detalle
            function attachDetalleListeners() {
                document.querySelectorAll('.btn-detalle').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const clienteId = this.getAttribute('data-cliente-id');
                        loadClienteDetalle(clienteId);
                    });
                });
            }

            function loadClienteDetalle(clienteId) {
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
                
                detalleModal.show();

                fetch(`/clientes/${clienteId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(r => r.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(err => {
                    modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles del cliente</div>';
                    console.error('Error:', err);
                });
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

            // Initial attachment
            attachDetalleListeners();
            attachDeleteListeners();
        });
    </script>
</body>
</html>