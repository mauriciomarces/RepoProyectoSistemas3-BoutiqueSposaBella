<table class="table table-hover">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Busto</th>
            <th>Cintura</th>
            <th>Cadera</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clientes as $cliente)
        <tr>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->correo }}</td>
            <td>{{ $cliente->telefono }}</td>
            <td>{{ $cliente->busto }} cm</td>
            <td>{{ $cliente->cintura }} cm</td>
            <td>{{ $cliente->cadera }} cm</td>
            <td>
                <div class="d-flex gap-1">
                    <!-- Botón Detalle -->
                    <button type="button" 
                            class="btn btn-outline-secondary btn-sm btn-detalle" 
                            data-cliente-id="{{ $cliente->ID_cliente }}"
                            data-cliente-nombre="{{ $cliente->nombre }}"
                            data-cliente-apellido=""
                            data-cliente-email="{{ $cliente->correo }}"
                            data-cliente-telefono="{{ $cliente->telefono }}"
                            data-cliente-busto="{{ $cliente->busto }}"
                            data-cliente-cintura="{{ $cliente->cintura }}"
                            data-cliente-cadera="{{ $cliente->cadera }}">
                        Detalle
                    </button>

                    <!-- Botón Editar -->
                    <a href="{{ route('clientes.edit', $cliente->ID_cliente) }}"
                       class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <!-- Botón Eliminar -->
                    <button type="button"
                            class="btn btn-danger btn-sm btn-eliminar"
                            data-cliente-id="{{ $cliente->ID_cliente }}"
                            data-cliente-nombre="{{ $cliente->nombre }}">
                        Eliminar
                    </button>
                    
                    <!-- Form oculto para eliminación -->
                    <form id="delete-form-{{ $cliente->ID_cliente }}"
                          action="{{ route('clientes.destroy', $cliente->ID_cliente) }}"
                          method="POST"
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No hay clientes que coincidan con los filtros</td>
        </tr>
        @endforelse
    </tbody>
</table>