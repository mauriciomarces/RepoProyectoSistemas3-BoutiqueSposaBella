
<h1>Registrar Movimiento</h1>
<form action="{{ route('movimientos.store') }}" method="POST">
    @csrf
    <label>Producto:</label>
    <select name="producto_id" required>
        @foreach($productos as $producto)
            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
        @endforeach
    </select><br>
    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="entrada">Entrada</option>
        <option value="salida">Salida</option>
    </select><br>
    <label>Cantidad:</label>
    <input type="number" name="cantidad" min="1" required><br>
    <label>Observación:</label>
    <input type="text" name="observacion"><br>
    <button type="submit">Registrar</button>
</form>
<a href="{{ route('movimientos.index') }}">Volver</a>
