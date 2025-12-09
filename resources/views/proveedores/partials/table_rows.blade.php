@forelse($proveedores as $proveedor)
<tr>
    <td>{{ $proveedor->ID_proveedor }}</td>
    <td>
        <i class="fas fa-building text-muted me-1"></i>
        <strong>{{ $proveedor->nombre }}</strong>
    </td>
    <td>
        <span title="{{ $proveedor->direccion }}">
            {{ $proveedor->direccion ? Str::limit($proveedor->direccion, 30) : 'N/A' }}
        </span>
    </td>
    <td>
        @if($proveedor->telefono)
        <i class="fas fa-phone me-1"></i>{{ $proveedor->telefono }}
        @else
        <span class="text-muted">N/A</span>
        @endif
    </td>
    <td>
        <span class="badge" style="background-color: #8E805E; color: white;">
            {{ $proveedor->tipo_proveedor }}
        </span>
    </td>
    <td>
        <div class="btn-group" role="group">
            <button type="button"
                class="btn btn-sm btn-outline-info btn-detalle"
                data-proveedor-id="{{ $proveedor->ID_proveedor }}"
                title="Ver detalle">
                <i class="fas fa-eye"></i>
            </button>
            <a href="{{ route('proveedores.edit', $proveedor->ID_proveedor) }}"
                class="btn btn-sm btn-custom-secondary"
                title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('proveedores.destroy', $proveedor->ID_proveedor) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('¿Está seguro de eliminar este proveedor?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="btn btn-sm btn-outline-danger"
                    title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted py-4">
        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
        No se encontraron proveedores
    </td>
</tr>
@endforelse