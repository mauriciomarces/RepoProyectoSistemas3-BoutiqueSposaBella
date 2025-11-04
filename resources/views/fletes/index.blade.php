@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h1>Gestión de Fletes</h1>
            </div>
            <div class="col text-end">
                <a href="{{ route('fletes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Nuevo Flete
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Destinatario</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fletes as $flete)
                                <tr>
                                    <td>{{ $flete->destinatario }}</td>
                                    <td>{{ $flete->direccion }}</td>
                                    <td>{{ $flete->telefono ?? 'No especificado' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($flete->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('fletes.destroy', $flete->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este flete?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
