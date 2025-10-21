<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Agregar Producto</h1>

<form action="{{ route('productos.store') }}" method="POST" class="form-inventario">
    @csrf

    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
    </div>

    <div class="form-group">
        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id">
            <option value="">--Seleccionar--</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" min="0" max="9999" value="0" required>
    </div>

    <div class="form-group">
        <label for="precio_unitario">Precio Unitario:</label>
        <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" min="0" max="999999.99" required>
    </div>

    <div class="form-group">
        <label for="stock_minimo">Stock mínimo:</label>
        <input type="number" name="stock_minimo" id="stock_minimo" value="5" min="1" required>
    </div>

    <button type="submit" class="btn-accion btn-agregar">Guardar</button>
    <a href="{{ route('productos.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
