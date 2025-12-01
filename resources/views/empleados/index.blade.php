@extends('layouts.app')

@section('content')
<h1>Empleados</h1>

<a href="{{ route('empleados.create') }}" class="btn btn-primary">Crear Empleado</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table table-striped">
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
    <tbody>
        @foreach($empleados as $empleado)
        <tr>
            <td>{{ $empleado->ID_empleado }}</td>
            <td>{{ $empleado->nombre }}</td>
            <td>{{ $empleado->correo }}</td>
            <td>{{ $empleado->CI }}</td>
            <td>{{ $empleado->telefono }}</td>
            <td>{{ $empleado->puesto }}</td>
            <td>{{ $empleado->rol->cargo ?? $empleado->ID_rol }}</td>
            <td>{{ $empleado->sucursal->nombre ?? $empleado->ID_sucursal }}</td>
            <td>{{ $empleado->seccion->nombre ?? $empleado->ID_seccion }}</td>
            <td>
                <a href="{{ route('empleados.edit', $empleado->ID_empleado) }}" class="btn btn-warning btn-sm">Editar</a>
                <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="if(confirm('¿Estás seguro de que deseas eliminar a {{ $empleado->nombre }}?')) { document.getElementById('delete-form-{{ $empleado->ID_empleado }}').submit(); }">
                    Eliminar
                </button>

                <!-- Form oculto para eliminación -->
                <form id="delete-form-{{ $empleado->ID_empleado }}"
                    action="{{ route('empleados.destroy', $empleado->ID_empleado) }}"
                    method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection