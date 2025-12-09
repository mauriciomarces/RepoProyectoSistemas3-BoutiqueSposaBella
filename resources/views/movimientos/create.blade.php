@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Movimiento</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('movimientos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria" id="categoria" class="form-control" required>
                @foreach($categorias as $value => $label)
                <option value="{{ $value }}" {{ old('categoria') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <!-- Hidden input to submit the value -->
            <input type="hidden" name="tipo" id="tipo-hidden" value="ingreso">
            <!-- Disabled select for display only -->
            <select id="tipo" class="form-control" disabled>
                <option value="ingreso">Ingreso</option>
                <option value="egreso">Egreso</option>
            </select>
        </div>

        <div class="mb-3" id="empleado-container" style="display: none;">
            <label class="form-label">Empleado</label>
            <select name="ID_empleado" id="empleado-select" class="form-control">
                <option value="">Seleccionar empleado</option>
                @foreach(\App\Models\Empleado::all() as $emp)
                <option value="{{ $emp->ID_empleado }}" data-nombre="{{ $emp->nombre }}" {{ old('ID_empleado') == $emp->ID_empleado ? 'selected' : '' }}>
                    {{ $emp->nombre }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" id="monto" class="form-control" value="{{ old('monto') }}" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Concepto <small class="text-muted">(generado automáticamente)</small></label>
            <!-- Hidden input to submit the value -->
            <input type="hidden" name="concepto" id="concepto-hidden" value="{{ old('concepto') }}">
            <!-- Readonly input for display -->
            <input type="text" id="concepto" class="form-control" readonly style="background-color: #e9ecef;" />
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control"
                value="{{ old('fecha', date('Y-m-d')) }}"
                min="{{ date('Y-m-d') }}"
                required />
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción <small class="text-muted">(detalles adicionales)</small></label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Agregue detalles adicionales sobre esta transacción (opcional)...">{{ old('descripcion') }}</textarea>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    const categoriaTipos = {
        'ventas': 'ingreso',
        'confecciones': 'egreso',
        'gastos_operativos': 'egreso',
        'salarios': 'egreso',
        'insumos': 'egreso',
        'otros': 'ingreso' // default for "otros"
    };

    const categoriaNombres = {
        'ventas': 'Ventas',
        'confecciones': 'Confecciones',
        'gastos_operativos': 'Gastos Operativos',
        'salarios': 'Salarios',
        'insumos': 'Insumos',
        'otros': 'Otros'
    };

    // Función para generar el concepto automáticamente
    function generarConcepto() {
        const categoria = document.getElementById('categoria').value;
        const tipo = document.getElementById('tipo').value;
        const monto = document.getElementById('monto').value;

        if (!categoria || !tipo) {
            return;
        }

        const categoriaNombre = categoriaNombres[categoria] || categoria;
        const tipoTexto = tipo === 'ingreso' ? 'Ingreso' : 'Egreso';
        const montoTexto = monto ? `Bs. ${parseFloat(monto).toFixed(2)}` : 'Bs. 0.00';

        let concepto = `${tipoTexto} por ${categoriaNombre}`;

        // Si es salario, agregar el nombre del empleado
        if (categoria === 'salarios') {
            const empleadoSelect = document.getElementById('empleado-select');
            if (empleadoSelect && empleadoSelect.value) {
                const empleadoNombre = empleadoSelect.options[empleadoSelect.selectedIndex].getAttribute('data-nombre');
                if (empleadoNombre) {
                    concepto += ` - ${empleadoNombre}`;
                }
            }
        }

        concepto += ` - ${montoTexto}`;

        document.getElementById('concepto').value = concepto;
        document.getElementById('concepto-hidden').value = concepto;
    }

    document.getElementById('categoria').addEventListener('change', function() {
        const categoria = this.value;
        const tipoSelect = document.getElementById('tipo');
        const tipoHidden = document.getElementById('tipo-hidden');
        const empleadoContainer = document.getElementById('empleado-container');

        if (categoria !== 'otros') {
            const tipoValue = categoriaTipos[categoria] || 'ingreso';
            tipoSelect.value = tipoValue;
            tipoHidden.value = tipoValue;
            tipoSelect.disabled = true;
        } else {
            tipoSelect.disabled = false;
        }

        if (categoria === 'salarios') {
            empleadoContainer.style.display = 'block';
        } else {
            empleadoContainer.style.display = 'none';
        }

        // Generar concepto cuando cambia la categoría
        generarConcepto();
    });

    // Sync hidden input when tipo select changes manually (for "otros" category)
    document.getElementById('tipo').addEventListener('change', function() {
        const tipoHidden = document.getElementById('tipo-hidden');
        tipoHidden.value = this.value;
        // Generar concepto cuando cambia el tipo
        generarConcepto();
    });

    // Generar concepto cuando cambia el monto
    document.getElementById('monto').addEventListener('input', function() {
        generarConcepto();
    });

    // Generar concepto cuando cambia el empleado (para salarios)
    document.getElementById('empleado-select').addEventListener('change', function() {
        generarConcepto();
    });

    // Trigger on page load if categoria is already selected
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('categoria').dispatchEvent(new Event('change'));
        generarConcepto();
    });
</script>
@endsection