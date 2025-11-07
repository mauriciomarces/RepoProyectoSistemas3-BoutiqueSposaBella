@extends('layouts.app')

@section('content')
    <h1>Crear Empleado</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="puesto" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" value="{{ old('puesto') }}" required>
        </div>
        <div class="mb-3">
            <label for="ID_rol" class="form-label">ID Rol</label>
            <input type="number" class="form-control" id="ID_rol" name="ID_rol" value="{{ old('ID_rol') }}" required>
        </div>
        <div class="mb-3">
            <label for="ID_sucursal" class="form-label">ID Sucursal</label>
            <input type="number" class="form-control" id="ID_sucursal" name="ID_sucursal" value="{{ old('ID_sucursal') }}" required>
        </div>
        <div class="mb-3">
            <label for="ID_seccion" class="form-label">ID Sección</label>
            <input type="number" class="form-control" id="ID_seccion" name="ID_seccion" value="{{ old('ID_seccion') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
    </form>
@endsection
