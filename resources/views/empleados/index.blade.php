@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-sposabella">Gestión de Empleados</h1>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo Empleado
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Filtros de Búsqueda -->
<div class="filtros-container mb-4">
    <form id="searchForm" class="row g-3">
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchNombre" name="nombre" placeholder="Buscar por Nombre">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchCI" name="CI" placeholder="Buscar por CI">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="searchPuesto" name="puesto" placeholder="Buscar por Puesto">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="searchRol" name="ID_rol">
                <option value="">Todos los Roles</option>
                @foreach($roles as $rol)
                <option value="{{ $rol->ID_rol }}">{{ $rol->cargo }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>CI</th>
                        <th>Teléfono</th>
                        <th>Puesto</th>
                        <th>Rol</th>
                        <th>Sucursal</th>
                        <th>Sección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="empleadosTableBody">
                    @include('empleados.partials.table_rows')
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #8e805e;">
                <h5 class="modal-title">Detalle del Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle fa-4x text-muted"></i>
                    <h4 id="modalNombre" class="mt-2 text-sposabella"></h4>
                    <p id="modalPuesto" class="text-muted"></p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">CI:</label>
                        <p id="modalCI"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Correo:</label>
                        <p id="modalCorreo"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Teléfono:</label>
                        <p id="modalTelefono"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Fecha Contratación:</label>
                        <p id="modalFecha"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Dirección:</label>
                        <p id="modalDireccion"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Salario:</label>
                        <p id="modalSalario"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Rol:</label>
                        <p id="modalRol"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Sucursal:</label>
                        <p id="modalSucursal"></p>
                    </div>
                    <div class="col-12">
                        <label class="fw-bold">Experiencia:</label>
                        <p id="modalExperiencia" class="text-muted fst-italic"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmación Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                <p class="fs-5">¿Estás seguro de que deseas eliminar a:</p>
                <p class="fw-bold fs-4" id="deleteNombre"></p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">Eliminar Empleado</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Variables globales para la eliminación
    let formToDeleteId = null;

    function confirmarEliminacion(id, nombre) {
        formToDeleteId = 'delete-form-' + id;
        document.getElementById('deleteNombre').textContent = nombre;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        if (formToDeleteId) {
            document.getElementById(formToDeleteId).submit();
        }
    });

    // Búsqueda AJAX
    const searchInputs = ['searchNombre', 'searchCI', 'searchPuesto', 'searchRol'];
    let timeout = null;

    searchInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchEmpleados();
            }, 300);
        });
    });

    document.getElementById('searchRol').addEventListener('change', fetchEmpleados);

    function fetchEmpleados() {
        const formData = new FormData(document.getElementById('searchForm'));
        const params = new URLSearchParams(formData);

        const tableBody = document.getElementById('empleadosTableBody');
        tableBody.style.opacity = '0.5';

        fetch(`{{ route('empleados.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                tableBody.innerHTML = html;
                tableBody.style.opacity = '1';
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.style.opacity = '1';
            });
    }

    // Modal Detalle
    function verDetalles(id) {
        fetch(`/empleados/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalNombre').textContent = data.nombre;
                document.getElementById('modalPuesto').textContent = data.puesto;
                document.getElementById('modalCI').textContent = data.CI;
                document.getElementById('modalCorreo').textContent = data.correo;
                document.getElementById('modalTelefono').textContent = data.telefono;
                document.getElementById('modalFecha').textContent = data.fecha_contratacion;
                document.getElementById('modalDireccion').textContent = data.direccion;
                document.getElementById('modalSalario').textContent = data.salario + ' Bs';
                document.getElementById('modalRol').textContent = data.rol ? data.rol.cargo : 'N/A';
                document.getElementById('modalSucursal').textContent = data.sucursal ? data.sucursal.nombre : 'N/A';
                document.getElementById('modalExperiencia').textContent = data.experiencia || 'Sin experiencia registrada';

                const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
                modal.show();
            })
            .catch(error => console.error('Error cargando detalles:', error));
    }
</script>
@endsection