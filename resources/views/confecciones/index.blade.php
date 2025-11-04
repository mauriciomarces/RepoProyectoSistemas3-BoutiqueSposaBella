@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Confecciones</h1>
        <a href="{{ route('confecciones.create') }}" class="btn btn-primary">Nueva Confección</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Cliente</th>
                            <th>Fecha inicio</th>
                            <th>Entrega</th>
                            <th>Costo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($confecciones as $c)
                        <tr>
                            <td>{{ $c->ID_confeccion ?? $c->id ?? 'N/A' }}</td>
                            <td>{{ $c->tipo_confeccion }}</td>
                            <td>{{ optional(DB::table('cliente')->where('ID_cliente', $c->ID_cliente)->first())->nombre ?? 'N/A' }}</td>
                            <td>{{ $c->fecha_inicio }}</td>
                            <td>{{ $c->fecha_entrega }}</td>
                            <td>Bs. {{ number_format($c->costo ?? 0,2) }}</td>
                            <td>{{ $c->estado ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
