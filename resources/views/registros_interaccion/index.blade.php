@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registros de Interacciones</h1>

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
                    @foreach ($acciones as $accion)
                        <option value="{{ $accion }}" {{ request('accion') == $accion ? 'selected' : '' }}>{{ ucfirst($accion) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="modulo" class="col-form-label">Módulo</label>
                <select name="modulo" id="modulo" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($modulos as $modulo)
                        <option value="{{ $modulo }}" {{ request('modulo') == $modulo ? 'selected' : '' }}>{{ ucfirst($modulo) }}</option>
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

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Acción</th>
                <th>Módulo</th>
                <th>Descripción</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registros as $registro)
            <tr>
                <td>{{ $registro->empleado->nombre ?? 'N/A' }}</td>
                <td>{{ ucfirst($registro->accion) }}</td>
                <td>{{ ucfirst($registro->modulo) }}</td>
                <td>{{ $registro->descripcion }}</td>
                <td>{{ $registro->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No se encontraron registros.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $registros->withQueryString()->links() }}
</div>
@endsection
