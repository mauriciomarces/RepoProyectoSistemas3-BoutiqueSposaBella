@extends('layouts.app')

@section('content')
<div class="container">
    <form method="GET" action="{{ route('registros_interaccion.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="empleado_id" class="col-form-label">Empleado</label>
                <select name="empleado_id" id="empleado_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->ID_empleado }}" {{ request('empleado_id') == $empleado->ID_empleado ? 'selected' : '' }}>{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="accion" class="col-form-label">Acción</label>
                <select name="accion" id="accion" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($acciones as $key => $label)
                    <option value="{{ $key }}" {{ request('accion') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="ID_dispositivo" class="col-form-label">ID Dispositivo</label>
                <select name="ID_dispositivo" id="ID_dispositivo" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($dispositivos as $dispositivo)
                    <option value="{{ $dispositivo }}" {{ request('ID_dispositivo') == $dispositivo ? 'selected' : '' }}>{{ $dispositivo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="fecha_desde" class="col-form-label">Desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
            </div>

            <div class="col-auto">
                <label for="fecha_hasta" class="col-form-label">Hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}" max="{{ date('Y-m-d') }}" class="form-control">
            </div>

            <div class="col-auto align-self-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('registros_interaccion.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Registros de Interacciones</h1>
        <button type="button" id="btn-print" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#printOptionsModal">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ $registros->where('accion', 'delete')->count() }}</h5>
                    <p class="card-text">Eliminaciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $registros->where('accion', 'create')->count() }}</h5>
                    <p class="card-text">Creaciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $registros->where('accion', 'edit')->count() }}</h5>
                    <p class="card-text">Ediciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $registros->count() }}</h5>
                    <p class="card-text">Total Registros</p>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>Descripción</th>
                    <th>ID Dispositivo</th>
                    <th>Fecha y Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registros as $registro)
                <tr class="{{ $registro->accion === 'delete' ? 'table-danger' : ($registro->accion === 'edit' ? 'table-warning' : ($registro->accion === 'create' ? 'table-success' : '')) }}">
                    <td>{{ $registro->empleado->nombre ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $registro->accion === 'delete' ? 'bg-danger' : ($registro->accion === 'edit' ? 'bg-warning text-dark' : ($registro->accion === 'create' ? 'bg-success' : 'bg-secondary')) }}">
                            @if($registro->accion === 'delete' && $registro->modulo === 'empleados')
                            Despedido
                            @else
                            {{ $acciones[$registro->accion] ?? ucfirst($registro->accion) }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $modulos[$registro->modulo] ?? ucfirst($registro->modulo) }}</span>
                    </td>
                    <td>
                        @if($registro->accion === 'delete')
                        <strong class="text-danger">{{ $registro->descripcion }}</strong>
                        @elseif($registro->accion === 'create')
                        <strong class="text-success">{{ $registro->descripcion }}</strong>
                        @elseif($registro->accion === 'edit')
                        <strong class="text-warning">{{ $registro->descripcion }}</strong>
                        @else
                        {{ $registro->descripcion }}
                        @endif
                    </td>
                    <td><span class="badge bg-info text-dark">{{ $registro->ID_dispositivo ?? 'N/A' }}</span></td>
                    <td>{{ $registro->created_at->format('d-m-Y H:i:s') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detalleModal" data-registro='{{ json_encode($registro) }}'>
                            <i class="fas fa-eye"></i> Ver Detalles
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No se encontraron registros.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación con Bootstrap 5 -->
    <div class="d-flex justify-content-center mt-4">
        {{ $registros->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Modal para opciones de impresión -->
<div class="modal fade" id="printOptionsModal" tabindex="-1" aria-labelledby="printOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printOptionsModalLabel">Opciones de Impresión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('registros_interaccion.print') }}" method="GET" target="_blank">
                    <div class="mb-3">
                        <label for="print_empleado_id" class="form-label">Empleado</label>
                        <select name="empleado_id" id="print_empleado_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->ID_empleado }}">{{ $empleado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="print_accion" class="form-label">Acción</label>
                        <select name="accion" id="print_accion" class="form-select">
                            <option value="">Todas</option>
                            @foreach ($acciones as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="print_ID_dispositivo" class="form-label">ID Dispositivo</label>
                        <select name="ID_dispositivo" id="print_ID_dispositivo" class="form-select">
                            <option value="">Todos</option>
                            @foreach ($dispositivos as $dispositivo)
                            <option value="{{ $dispositivo }}">{{ $dispositivo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="print_fecha_desde" class="form-label">Desde</label>
                            <input type="date" id="print_fecha_desde" name="fecha_desde" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="print_fecha_hasta" class="form-label">Hasta</label>
                            <input type="date" id="print_fecha_hasta" name="fecha_hasta" max="{{ date('Y-m-d') }}" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" name="print" value="1">
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Imprimir Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleModalLabel">Detalles de la Interacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido se carga dinámicamente con JavaScript -->
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
        var detalleModal = document.getElementById('detalleModal');
        detalleModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var registro = JSON.parse(button.getAttribute('data-registro'));

            var modalBody = detalleModal.querySelector('.modal-body');

            // Helper function to format object data
            function formatData(data) {
                if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) return '<span class="text-muted">N/A</span>';

                var html = '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">';
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var label = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) {
                            return l.toUpperCase();
                        });
                        var value = data[key];

                        if (typeof value === 'object' && value !== null) {
                            value = '<pre class="m-0">' + JSON.stringify(value, null, 2) + '</pre>';
                        } else if (value === null) {
                            value = '<span class="text-muted">null</span>';
                        }

                        html += '<tr><th class="bg-light" style="width: 30%;">' + label + '</th><td>' + value + '</td></tr>';
                    }
                }
                html += '</table></div>';
                return html;
            }

            var content = '<div class="row">';

            content += '<div class="col-md-6"><h5>Información General</h5>';
            content += '<p><strong>ID Registro:</strong> ' + (registro.registro_id || 'N/A') + '</p>';
            content += '<p><strong>Empleado:</strong> ' + (registro.empleado ? registro.empleado.nombre : 'N/A') + '</p>';

            var accionLabel = registro.accion.charAt(0).toUpperCase() + registro.accion.slice(1);
            if (registro.accion === 'delete') {
                accionLabel = (registro.modulo === 'empleados') ? 'Despedido' : 'Eliminado';
            } else {
                var accionesMap = {
                    'login': 'Ingreso',
                    'create': 'Creado',
                    'edit': 'Editado',
                    'venta': 'Venta',
                    'compra': 'Compra',
                    'movimiento_financiero': 'Movimiento Financiero',
                    'flete': 'Flete',
                    'analisis_financiero': 'Análisis Financiero'
                };
                if (accionesMap[registro.accion]) accionLabel = accionesMap[registro.accion];
            }

            content += '<p><strong>Acción:</strong> ' + accionLabel + '</p>';
            content += '<p><strong>Módulo:</strong> ' + (registro.modulo.charAt(0).toUpperCase() + registro.modulo.slice(1)) + '</p>';
            content += '<p><strong>ID Dispositivo:</strong> <span class="badge bg-info text-dark">' + (registro.ID_dispositivo || 'N/A') + '</span></p>';
            content += '<p><strong>Fecha:</strong> ' + new Date(registro.created_at).toLocaleString() + '</p>';
            content += '</div>';

            content += '<div class="col-md-6"><h5>Descripción</h5>';
            content += '<p>' + registro.descripcion + '</p>';
            content += '</div>';

            content += '</div>';

            if (registro.datos_anteriores) {
                content += '<div class="row mt-3"><div class="col-12"><h5>Datos Anteriores</h5>';
                content += formatData(registro.datos_anteriores);
                content += '</div></div>';
            }

            if (registro.datos_nuevos) {
                content += '<div class="row mt-3"><div class="col-12"><h5>Datos Nuevos</h5>';
                content += formatData(registro.datos_nuevos);
                content += '</div></div>';
            }

            modalBody.innerHTML = content;
        });
    });
</script>
@endsection