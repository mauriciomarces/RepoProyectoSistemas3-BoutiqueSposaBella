<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Editar Categoría</h1>

<form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="form-inventario">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="{{ $categoria->nombre }}" required>
    </div>

    <button type="submit" class="btn-accion btn-editar">Actualizar</button>
    <a href="{{ route('categorias.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
