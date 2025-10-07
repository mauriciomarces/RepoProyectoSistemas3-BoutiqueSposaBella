
<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Registrar Movimiento</h1>

<form action="{{ route('movimientos.store') }}" method="POST" class="form-inventario">
    @csrf

    <div class="form-group">
        <label for="producto_id">Producto:</label>
        <select name="producto_id" id="producto_id" required>
            @foreach($productos as $producto)
                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required>
            <option value="entrada">Entrada</option>
            <option value="salida">Salida</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" min="1" required>
    </div>

    <div class="form-group">
        <label for="observacion">Observación:</label>
        <input type="text" name="observacion" id="observacion">
    </div>

    <button type="submit" class="btn-accion btn-agregar">Registrar</button>
    <a href="{{ route('movimientos.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
