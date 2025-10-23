<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Editar Producto</h1>

<form action="{{ route('productos.update', $producto->id) }}" method="POST" class="form-inventario">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ $producto->nombre }}" required>
    </div>

    <div class="form-group">
        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id">
            <option value="">--Seleccionar--</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="{{ $producto->cantidad }}" min="0" max="9999" required>
    </div>

    <div class="form-group">
        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" value="{{ $producto->precio_unitario }}" min="0" max="999999.99" required>
    </div>

    <div class="form-group">
        <label for="stock_minimo">Stock mínimo:</label>
        <input type="number" name="stock_minimo" id="stock_minimo" value="{{ $producto->stock_minimo }}" min="1" required>
    </div>

    <button type="submit" class="btn-accion btn-editar">Actualizar</button>
    <a href="{{ route('productos.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>

