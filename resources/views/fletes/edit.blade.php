@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Flete</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fletes.update', $flete->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Destinatario</label>
                <input type="text" name="destinatario" class="form-control" value="{{ old('destinatario', $flete->destinatario) }}" />
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $flete->direccion) }}" />
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $flete->telefono) }}" />
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('fletes.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
