@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-truck"></i> Lista de Proveedores</h1>
            <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Proveedor
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proveedores as $proveedor)
                            <tr>
                                <td>{{ $proveedor->ID_proveedor }}</td>
                                <td>{{ $proveedor->nombre }}</td>
                                <td>{{ $proveedor->direccion ?? 'N/A' }}</td>
                                <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $proveedor->tipo_proveedor }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('proveedores.edit', $proveedor->ID_proveedor) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('proveedores.destroy', $proveedor->ID_proveedor) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <!-- <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-tshirt"></i> Gestionar Productos
            </a> -->
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection