<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">
<h1>Reporte de productos con stock bajo</h1>

@if($productos->isEmpty())
    <p>No hay productos con stock bajo 🎉</p>
@else
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Cantidad actual</th>
        <th>Stock mínimo</th>
    </tr>
    @foreach($productos as $producto)
    <tr>
        <td>{{ $producto->id }}</td>
        <td>{{ $producto->nombre }}</td>
        <td>{{ $producto->categoria?->nombre }}</td>
        <td>{{ $producto->cantidad }}</td>
        <td>{{ $producto->stock_minimo }}</td>
    </tr>
    @endforeach
</table>
@endif

<a href="{{ route('productos.index') }}">Volver</a>
