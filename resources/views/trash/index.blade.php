@extends('layouts.app')

@section('title', 'Papelera de Reciclaje')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Papelera de Reciclaje</h3>
                    <p class="card-subtitle">Registros eliminados temporalmente</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="trashTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="clientes-tab" data-bs-toggle="tab" data-bs-target="#clientes" type="button" role="tab">Clientes ({{ $clientes->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="empleados-tab" data-bs-toggle="tab" data-bs-target="#empleados" type="button" role="tab">Empleados ({{ $empleados->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos" type="button" role="tab">Productos ({{ $productos->count() }})</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="proveedores-tab" data-bs-toggle="tab" data-bs-target="#proveedores" type="button" role="tab">Proveedores ({{ $proveedores->count() }})</button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="trashTabsContent">
                        <!-- Clientes Tab -->
                        <div class="tab-pane fade show active" id="clientes" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Teléfono</th>
                                            <th>Fecha Eliminación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($clientes as $cliente)
                                            <tr>
                                                <td>{{ $cliente->ID_cliente }}</td>
                                                <td>{{ $cliente->nombre }}</td>
                                                <td>{{ $cliente->correo }}</td>
                                                <td>{{ $cliente->telefono }}</td>
                                                <td>{{ $cliente->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" onclick="confirmRestore('cliente', {{ $cliente->ID_cliente }}, '{{ $cliente->nombre }}')">
                                                        <i class="fas fa-undo"></i> Restaurar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmForceDelete('cliente', {{ $cliente->ID_cliente }}, '{{ $cliente->nombre }}')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No hay clientes eliminados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Empleados Tab -->
                        <div class="tab-pane fade" id="empleados" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Puesto</th>
                                            <th>Fecha Eliminación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($empleados as $empleado)
                                            <tr>
                                                <td>{{ $empleado->ID_empleado }}</td>
                                                <td>{{ $empleado->nombre }}</td>
                                                <td>{{ $empleado->correo }}</td>
                                                <td>{{ $empleado->puesto }}</td>
                                                <td>{{ $empleado->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" onclick="confirmRestore('empleado', {{ $empleado->ID_empleado }}, '{{ $empleado->nombre }}')">
                                                        <i class="fas fa-undo"></i> Restaurar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmForceDelete('empleado', {{ $empleado->ID_empleado }}, '{{ $empleado->nombre }}')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No hay empleados eliminados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Productos Tab -->
                        <div class="tab-pane fade" id="productos" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Precio</th>
                                            <th>Fecha Eliminación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productos as $producto)
                                            <tr>
                                                <td>{{ $producto->ID_producto }}</td>
                                                <td>{{ $producto->nombre }}</td>
                                                <td>{{ Str::limit($producto->descripcion, 50) }}</td>
                                                <td>Bs. {{ number_format($producto->precio, 2) }}</td>
                                                <td>{{ $producto->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" onclick="confirmRestore('producto', {{ $producto->ID_producto }}, '{{ $producto->nombre }}')">
                                                        <i class="fas fa-undo"></i> Restaurar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmForceDelete('producto', {{ $producto->ID_producto }}, '{{ $producto->nombre }}')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No hay productos eliminados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Proveedores Tab -->
                        <div class="tab-pane fade" id="proveedores" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Fecha Eliminación</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($proveedores as $proveedor)
                                            <tr>
                                                <td>{{ $proveedor->ID_proveedor }}</td>
                                                <td>{{ $proveedor->nombre }}</td>
                                                <td>{{ Str::limit($proveedor->direccion, 50) }}</td>
                                                <td>{{ $proveedor->telefono }}</td>
                                                <td>{{ $proveedor->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" onclick="confirmRestore('proveedor', {{ $proveedor->ID_proveedor }}, '{{ $proveedor->nombre }}')">
                                                        <i class="fas fa-undo"></i> Restaurar
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmForceDelete('proveedor', {{ $proveedor->ID_proveedor }}, '{{ $proveedor->nombre }}')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No hay proveedores eliminados</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Restaurar -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Restauración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea restaurar el registro <strong id="restoreName"></strong>?</p>
                <p class="text-muted">El registro volverá a estar disponible en el sistema.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="restoreForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Restaurar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminación Permanente -->
<div class="modal fade" id="forceDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación Permanente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">¿Está seguro de que desea eliminar permanentemente el registro <strong id="deleteName"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="forceDeleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Permanentemente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmRestore(type, id, name) {
    document.getElementById('restoreName').textContent = name;
    document.getElementById('restoreForm').action = `/trash/${type}/${id}/restore`;
    new bootstrap.Modal(document.getElementById('restoreModal')).show();
}

function confirmForceDelete(type, id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('forceDeleteForm').action = `/trash/${type}/${id}/force-delete`;
    new bootstrap.Modal(document.getElementById('forceDeleteModal')).show();
}
</script>
@endsection
