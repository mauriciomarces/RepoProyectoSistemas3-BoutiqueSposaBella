<link rel="stylesheet" href="{{ asset('css/inventario.css') }}">

<h1>Categorías</h1>

<a href="{{ route('categorias.create') }}" class="btn-accion btn-agregar">Agregar Categoría</a>

<table class="table-inventario">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorias as $categoria)
        <tr>
            <td>{{ $categoria->id }}</td>
            <td>{{ $categoria->nombre }}</td>
            <td>
                <!-- Botón Editar -->
                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn-accion btn-editar">Editar</a>

                <!-- Botón Eliminar -->
                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
