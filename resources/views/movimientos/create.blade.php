@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Registrar Movimiento</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('movimientos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-control">
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-control" required>
                    @foreach($categorias as $value => $label)
                        <option value="{{ $value }}" {{ old('categoria') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Monto</label>
                <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto') }}" />
            </div>

            <div class="mb-3">
                <label class="form-label">Concepto</label>
                <input type="text" name="concepto" class="form-control" value="{{ old('concepto') }}" />
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" 
                       value="{{ old('fecha', date('Y-m-d')) }}" 
                       min="{{ date('Y-m-d') }}" 
                       required />
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
