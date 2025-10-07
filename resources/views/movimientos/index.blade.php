<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Movimientos</h1>

<a href="{{ route('movimientos.create') }}" class="btn-accion btn-agregar">Registrar Movimiento</a>

<table class="table-inventario">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimientos as $movimiento)
        <tr>
            <td>{{ $movimiento->id }}</td>
            <td>{{ $movimiento->producto?->nombre ?? '-' }}</td>
            <td>
                @if($movimiento->tipo == 'entrada')
                    <span class="px-2 py-1 rounded bg-green-500 text-white">Entrada</span>
                @else
                    <span class="px-2 py-1 rounded bg-red-500 text-white">Salida</span>
                @endif
            </td>
            <td>{{ $movimiento->cantidad }}</td>
            <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $movimiento->observacion ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
