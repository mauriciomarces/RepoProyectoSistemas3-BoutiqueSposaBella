@forelse($confecciones as $c)
<tr>
    <td class="align-middle font-weight-bold">
        #{{ $c->ID_confeccion }}
    </td>
    <td class="align-middle">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2"
                style="width: 35px; height: 35px; color: #8E805E;">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <div class="font-weight-bold">{{ $c->cliente->nombre ?? 'Cliente eliminado' }}</div>
                <div class="small text-muted">{{ $c->cliente->telefono ?? '' }}</div>
            </div>
        </div>
    </td>
    <td class="align-middle">{{ $c->tipo_confeccion }}</td>
    <td class="align-middle">
        <div class="small">
            <span class="text-muted">Inicio:</span> {{ $c->fecha_inicio ? $c->fecha_inicio->format('d/m/Y') : 'N/A' }}
        </div>
        @if($c->fecha_entrega)
        <div class="small">
            <span class="text-{{ \Carbon\Carbon::parse($c->fecha_entrega)->isPast() && $c->estado != 'completado' ? 'danger' : 'success' }}">
                Entrega: {{ $c->fecha_entrega->format('d/m/Y') }}
            </span>
        </div>
        @endif
    </td>
    <td class="align-middle font-weight-bold">
        Bs. {{ number_format($c->costo, 2) }}
    </td>
    <td class="align-middle">
        @php
        $badgeClass = match($c->estado) {
        'completado' => 'success',
        'pendiente' => 'warning',
        'en_proceso' => 'info',
        'cancelado' => 'danger',
        default => 'secondary'
        };
        $icon = match($c->estado) {
        'completado' => 'check-circle',
        'pendiente' => 'clock',
        'en_proceso' => 'spinner',
        'cancelado' => 'times-circle',
        default => 'question-circle'
        };
        @endphp
        <span class="badge bg-{{ $badgeClass }}">
            <i class="fas fa-{{ $icon }} me-1"></i>
            {{ ucfirst(str_replace('_', ' ', $c->estado)) }}
        </span>
    </td>
    <td class="align-middle">
        <div class="btn-group" role="group">
            <a href="{{ route('confecciones.edit', $c->ID_confeccion) }}"
                class="btn btn-sm btn-outline-primary" title="Editar / Actualizar Estado">
                <i class="fas fa-edit"></i>
            </a>
            <a href="{{ route('confecciones.show', $c->ID_confeccion) }}"
                class="btn btn-sm btn-outline-secondary" title="Ver Detalles">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-4 text-muted">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <p>No se encontraron confecciones.</p>
    </td>
</tr>
@endforelse