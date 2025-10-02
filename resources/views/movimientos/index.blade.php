<h1>Movimientos</h1>
<a href="{{ route('movimientos.create') }}">Registrar Movimiento</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Observación</th>
    </tr>
    @foreach($movimientos as $movimiento)
    <tr>
        <td>{{ $movimiento->id }}</td>
        <td>{{ $movimiento->producto->nombre }}</td>
        <td>{{ $movimiento->tipo }}</td>
        <td>{{ $movimiento->cantidad }}</td>
        <td>{{ $movimiento->created_at }}</td>
        <td>{{ $movimiento->observacion }}</td>
    </tr>
    @endforeach
</table>
