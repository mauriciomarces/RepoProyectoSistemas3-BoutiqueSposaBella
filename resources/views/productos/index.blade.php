<h1>Productos</h1>
<a href="{{ route('productos.create') }}">Agregar Producto</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Acciones</th>
    </tr>
    @foreach($productos as $producto)
    <tr>
        <td>{{ $producto->id }}</td>
        <td>{{ $producto->nombre }}</td>
        <td>{{ $producto->categoria?->nombre }}</td>
        <td>{{ $producto->cantidad }}</td>
        <td>{{ $producto->precio_unitario }}</td>
        <td>
            <a href="{{ route('productos.edit', $producto) }}">Editar</a>
            <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
