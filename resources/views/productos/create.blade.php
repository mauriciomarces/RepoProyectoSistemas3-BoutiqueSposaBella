<h1>Agregar Producto</h1>
<form action="{{ route('productos.store') }}" method="POST">
    @csrf
    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>
    <label>Categoría:</label>
    <select name="categoria_id">
        <option value="">--Seleccionar--</option>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
        @endforeach
    </select><br>
    <label>Cantidad:</label>
    <input type="number" name="cantidad" value="0" min="0"><br>
    <label>Precio Unitario:</label>
    <input type="number" step="0.01" name="precio_unitario" required><br>
    <button type="submit">Guardar</button>
</form>
<a href="{{ route('productos.index') }}">Volver</a>
