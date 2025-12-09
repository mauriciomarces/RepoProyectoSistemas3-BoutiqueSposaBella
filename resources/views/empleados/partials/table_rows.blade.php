@foreach($empleados as $empleado)
<tr>
    <td>{{ $empleado->ID_empleado }}</td>
    <td>{{ $empleado->nombre }}</td>
    <td>{{ $empleado->correo }}</td>
    <td>{{ $empleado->CI }}</td>
    <td>{{ $empleado->telefono }}</td>
    <td>{{ $empleado->puesto }}</td>
    <td>{{ $empleado->rol->cargo ?? $empleado->ID_rol }}</td>
    <td>{{ $empleado->sucursal->nombre ?? $empleado->ID_sucursal }}</td>
    <td>{{ $empleado->seccion->nombre ?? $empleado->ID_seccion }}</td>
    <td>
        <!-- Botón Ver Detalles (Modal) -->
        <button type="button" class="btn btn-sm btn-primary text-white me-1" onclick="verDetalles({{ $empleado->ID_empleado }})" title="Ver detalles">
            <i class="fas fa-eye"></i>
        </button>

        <a href="{{ route('empleados.edit', $empleado->ID_empleado) }}" class="btn btn-secondary btn-sm me-1" title="Editar">
            <i class="fas fa-edit"></i>
        </a>

        <button type="button"
            class="btn btn-danger btn-sm"
            title="Eliminar"
            onclick="confirmarEliminacion({{ $empleado->ID_empleado }}, '{{ $empleado->nombre }}')">
            <i class="fas fa-trash"></i>
        </button>

        <!-- Form oculto para eliminación -->
        <form id="delete-form-{{ $empleado->ID_empleado }}"
            action="{{ route('empleados.destroy', $empleado->ID_empleado) }}"
            method="POST"
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>
@endforeach