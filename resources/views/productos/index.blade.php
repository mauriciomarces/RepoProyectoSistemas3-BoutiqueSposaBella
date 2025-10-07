<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Productos</h1>

<a href="{{ route('productos.create') }}" class="btn-accion btn-agregar">Agregar Producto</a>

<table class="table-inventario">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $producto)
        <tr>
            <td>{{ $producto->id }}</td>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->categoria?->nombre }}</td>
            <td>{{ $producto->cantidad }}</td>
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
