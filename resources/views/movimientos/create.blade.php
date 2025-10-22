<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Registrar Movimiento</h1>

@if($errors->any())
    <div class="alerta-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('movimientos.store') }}" method="POST" class="form-inventario">
    @csrf

    <div class="form-group">
        <label for="producto_id">Producto:</label>
        <select name="producto_id" id="producto_id" required>
            <option value="">--Seleccionar--</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                    {{ $producto->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required>
            <option value="entrada" {{ old('tipo') == 'entrada' ? 'selected' : '' }}>Entrada</option>
            <option value="salida" {{ old('tipo') == 'salida' ? 'selected' : '' }}>Salida</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad', 1) }}" min="1" max="9999" required>
    </div>

    <div class="form-group">
        <label for="observacion">Observación:</label>
        <input type="text" name="observacion" id="observacion" value="{{ old('observacion') }}">
    </div>

    <button type="submit" class="btn-accion btn-agregar">Registrar</button>
    <a href="{{ route('movimientos.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
