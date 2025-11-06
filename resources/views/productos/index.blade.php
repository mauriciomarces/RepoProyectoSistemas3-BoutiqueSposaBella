@extends('layouts.app')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tshirt"></i> Lista de Productos</h1>
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>

        {{-- =========================================== --}}
        {{-- ALERTAS DE INVENTARIO --}}
        {{-- =========================================== --}}
        @if($productosAgotados > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="fas fa-exclamation-triangle"></i> ¡ALERTA DE INVENTARIO!</h5>
            <strong>{{ $productosAgotados }} producto(s) AGOTADO(S)</strong> - Necesita reposición inmediata.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($productosBajos > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5><i class="fas fa-exclamation-circle"></i> Inventario Bajo</h5>
            <strong>{{ $productosBajos }} producto(s) con stock bajo</strong> - Considerar reposición.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($productosAgotados == 0 && $productosBajos == 0)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5><i class="fas fa-check-circle"></i> Inventario en Orden</h5>
            Todos los productos tienen stock suficiente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

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
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripción Corta</th>
                                <th>Categoría</th>
                                <th>Precio (Bs)</th>
                                <th>Stock / Mínimo</th>
                                <th>Estado</th>
                                <th>Proveedor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                            <tr class="@if($producto->estaAgotado()) table-danger @elseif($producto->necesitaReposicion()) table-warning @endif">
                                <td>{{ $producto->ID_producto }}</td>
                                <td>
                                    @if(!empty($producto->imagen_blob) && !empty($producto->imagen_mime))
                                        <img src="{{ $producto->imagen_data }}" 
                                            alt="{{ $producto->nombre }}" 
                                            style="width: 50px; height: 50px; object-fit: cover;" 
                                            class="rounded">
                                    @elseif(!empty($producto->imagen) && file_exists(public_path('images/productos/' . $producto->imagen)))
                                        <img src="{{ asset('images/productos/' . $producto->imagen) }}" 
                                            alt="{{ $producto->nombre }}" 
                                            style="width: 50px; height: 50px; object-fit: cover;" 
                                            class="rounded">
                                    @else
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    @endif
                                </td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->descripcion_corta }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $producto->categoria }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">Bs {{ number_format($producto->precio, 2) }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold {{ $producto->estaAgotado() ? 'text-danger' : ($producto->necesitaReposicion() ? 'text-warning' : 'text-success') }}">
                                            {{ $producto->stock }} unidades
                                        </span>
                                        <small class="text-muted">
                                            Mín: {{ $producto->stock_minimo }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($producto->estaAgotado())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Agotado
                                        </span>
                                    @elseif($producto->necesitaReposicion())
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle"></i> Bajo
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Normal
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $producto->proveedor->nombre ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('productos.edit', $producto->ID_producto) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('productos.destroy', $producto->ID_producto) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">
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

{{-- RESUMEN DE ALERTAS - CORREGIDO --}}
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body text-center">
                @php
                    $productosNormales = $productos->filter(function($producto) {
                        return $producto->stock > $producto->stock_minimo;
                    })->count();
                @endphp
                <h3 class="text-success">{{ $productosNormales }}</h3>
                <p class="mb-0">Productos con stock normal</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body text-center">
                <h3 class="text-warning">{{ $productosBajos }}</h3>
                <p class="mb-0">Productos con stock bajo</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body text-center">
                <h3 class="text-danger">{{ $productosAgotados }}</h3>
                <p class="mb-0">Productos agotados</p>
            </div>
        </div>
    </div>
</div>

        <div class="mt-3">
            <!-- <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-truck"></i> Gestionar Proveedores
            </a> -->
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Auto-submit de filtros al cambiar
    document.querySelectorAll('.filter-control').forEach(control => {
        control.addEventListener('change', () => control.form.submit());
    });
</script>
@endsection