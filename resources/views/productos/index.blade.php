<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Productos</h1>

<a href="{{ route('productos.create') }}" class="btn-accion btn-agregar">Agregar Producto</a>

{{-- 🔔 ALERTA GENERAL: muestra un aviso si hay productos con stock bajo --}}
@if($productos->where('cantidad', '<=', 'stock_minimo')->count() > 0)
    <div class="alerta-stock-bajo">
        ⚠️ Hay productos con stock bajo. Revisa la lista.
    </div>
@endif

<table class="table-inventario">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Stock Mínimo</th>
            <th>Precio Unitario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $producto)
        <tr @if($producto->cantidad <= $producto->stock_minimo) class="fila-alerta" @endif>
            <td>{{ $producto->id }}</td>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->categoria?->nombre }}</td>
            <td>
                {{ $producto->cantidad }}
                {{-- 🔴 Alerta visual por producto --}}
                @if($producto->cantidad <= $producto->stock_minimo)
                    <span class="badge-alerta">Stock bajo</span>
                @endif
            </td>
            <td>{{ $producto->stock_minimo }}</td>
            <td>{{ $producto->precio_unitario }}</td>
            <td>
                <!-- Botón Editar -->
                <a href="{{ route('productos.edit', $producto->id) }}" class="btn-accion btn-editar">Editar</a>

                <!-- Botón Eliminar -->
                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
