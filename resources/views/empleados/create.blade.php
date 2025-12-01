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
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="CI" class="form-label">CI</label>
            <input type="text" class="form-control" id="CI" name="CI" value="{{ old('CI') }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
            <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" value="{{ old('fecha_contratacion', date('Y-m-d')) }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="puesto" class="form-label">Puesto</label>
            <input type="text" class="form-control" id="puesto" name="puesto" value="{{ old('puesto') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="salario" class="form-label">Salario</label>
            <input type="number" step="0.01" class="form-control" id="salario" name="salario" value="{{ old('salario') }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="experiencia" class="form-label">Experiencia</label>
        <textarea class="form-control" id="experiencia" name="experiencia" rows="2">{{ old('experiencia') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="ID_rol" class="form-label">Rol</label>
            <select class="form-select" id="ID_rol" name="ID_rol" required>
                <option value="">Seleccione un rol</option>
                @foreach($roles as $rol)
                <option value="{{ $rol->ID_rol }}" {{ old('ID_rol') == $rol->ID_rol ? 'selected' : '' }}>{{ $rol->cargo }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="ID_sucursal" class="form-label">Sucursal</label>
            <select class="form-select" id="ID_sucursal" name="ID_sucursal" required>
                <option value="">Seleccione una sucursal</option>
                @foreach($sucursales as $sucursal)
                <option value="{{ $sucursal->ID_sucursal }}" {{ old('ID_sucursal') == $sucursal->ID_sucursal ? 'selected' : '' }}>{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="ID_seccion" class="form-label">Sección</label>
            <select class="form-select" id="ID_seccion" name="ID_seccion" required>
                <option value="">Seleccione una sección</option>
                @foreach($secciones as $seccion)
                <option value="{{ $seccion->ID_seccion }}" {{ old('ID_seccion') == $seccion->ID_seccion ? 'selected' : '' }}>{{ $seccion->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
</form>
@endsection