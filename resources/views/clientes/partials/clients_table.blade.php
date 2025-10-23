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
            <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->telefono }}</td>
            <td>{{ $cliente->busto }} cm</td>
            <td>{{ $cliente->cintura }} cm</td>
            <td>{{ $cliente->cadera }} cm</td>
            <td>
                <div class="d-flex gap-1">
                    <!-- Botón Detalle -->
                    <button type="button" 
                            class="btn btn-outline-secondary btn-sm btn-detalle" 
                            data-cliente-id="{{ $cliente->id }}">
                        Detalle
                    </button>

                    <!-- Botón Editar -->
                    <a href="{{ route('clientes.edit', $cliente->id) }}" 
                       class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <!-- Botón Eliminar -->
                    <button type="button" 
                            class="btn btn-danger btn-sm btn-eliminar" 
                            data-cliente-id="{{ $cliente->id }}"
                            data-cliente-nombre="{{ $cliente->nombre }} {{ $cliente->apellido }}">
                        Eliminar
                    </button>
                    
                    <!-- Form oculto para eliminación -->
                    <form id="delete-form-{{ $cliente->id }}" 
                          action="{{ route('clientes.destroy', $cliente->id) }}" 
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
            <td colspan="7" class="text-center text-muted py-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-inbox mb-2" viewBox="0 0 16 16">
                    <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374z"/>
                </svg>
                <p class="mb-0">No hay clientes que coincidan con los filtros aplicados</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>