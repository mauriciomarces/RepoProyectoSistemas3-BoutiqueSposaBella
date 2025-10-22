<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Editar Movimiento</h1>

@if($errors->any())
    <div class="alerta-error">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('movimientos.update', $movimiento->id) }}" method="POST" class="form-inventario">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="producto_id">Producto:</label>
        <select name="producto_id" id="producto_id" required>
            <option value="">--Seleccionar--</option>
            @foreach($productos as $producto)
                <option value="{{ $producto->id }}" {{ $movimiento->producto_id == $producto->id ? 'selected' : '' }}>
                    {{ $producto->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" required>
            <option value="entrada" {{ $movimiento->tipo == 'entrada' ? 'selected' : '' }}>Entrada</option>
            <option value="salida" {{ $movimiento->tipo == 'salida' ? 'selected' : '' }}>Salida</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="{{ $movimiento->cantidad }}" min="1" max="9999" required>
    </div>

    <div class="form-group">
        <label for="observacion">Observación:</label>
        <input type="text" name="observacion" id="observacion" value="{{ $movimiento->observacion }}">
    </div>

    <button type="submit" class="btn-accion btn-editar">Actualizar</button>
    <a href="{{ route('movimientos.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
