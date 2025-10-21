<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Movimientos</h1>

<a href="{{ route('movimientos.create') }}" class="btn-accion btn-agregar">Registrar Movimiento</a>

{{-- 🔔 Búsqueda con estilo igual a Productos --}}
<form method="GET" action="{{ route('movimientos.index') }}" class="form-busqueda mb-3">
    <input type="text" name="search" class="form-control" placeholder="Buscar por producto, tipo u observación..." value="{{ $query ?? '' }}">
    <button type="submit" class="btn btn-primary">Buscar</button>
    @if(request('search'))
        <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Limpiar</a>
    @endif
</form>

<table class="table-inventario">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Observación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimientos as $movimiento)
        <tr>
            <td>{{ $movimiento->id }}</td>
            <td>{{ $movimiento->producto?->nombre }}</td>
            <td>{{ $movimiento->tipo }}</td>
            <td>{{ $movimiento->cantidad }}</td>
            <td>{{ $movimiento->observacion }}</td>
            <td>
                <a href="{{ route('movimientos.edit', $movimiento->id) }}" class="btn-accion btn-editar">Editar</a>
                <form action="{{ route('movimientos.destroy', $movimiento->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Paginación --}}
{{ $movimientos->links() }}
