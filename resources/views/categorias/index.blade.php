<h1>Categorías</h1>
<a href="{{ route('categorias.create') }}">Agregar Categoría</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>
    @foreach($categorias as $categoria)
    <tr>
        <td>{{ $categoria->id }}</td>
        <td>{{ $categoria->nombre }}</td>
        <td>
            <a href="{{ route('categorias.edit', $categoria) }}">Editar</a>
            <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
