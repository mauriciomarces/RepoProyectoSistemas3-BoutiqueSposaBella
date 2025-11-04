@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Confección</h1>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('confecciones.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="ID_cliente" class="form-control" required>
                @foreach($clientes as $c)
                    <option value="{{ $c->ID_cliente }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo de confección</label>
            <input name="tipo_confeccion" class="form-control" required />
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ date('Y-m-d') }}" required />
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha entrega</label>
                <input type="date" name="fecha_entrega" class="form-control" />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Costo estimado</label>
            <input type="number" step="0.01" name="costo" class="form-control" />
        </div>

        <h5>Medidas</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Busto (cm)</label>
                <input type="number" step="0.1" name="medidas[busto]" class="form-control" />
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Cintura (cm)</label>
                <input type="number" step="0.1" name="medidas[cintura]" class="form-control" />
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Cadera (cm)</label>
                <input type="number" step="0.1" name="medidas[cadera]" class="form-control" />
            </div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary">Crear confección</button>
            <a href="{{ route('confecciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
