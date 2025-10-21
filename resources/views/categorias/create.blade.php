<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Agregar Categoría</h1>

<form action="{{ route('categorias.store') }}" method="POST" class="form-inventario">
    @csrf

    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        @error('nombre')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn-accion btn-agregar">Guardar</button>
    <a href="{{ route('categorias.index') }}" class="btn-accion btn-eliminar">Volver</a>
</form>
