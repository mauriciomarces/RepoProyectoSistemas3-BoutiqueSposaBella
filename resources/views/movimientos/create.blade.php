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
                <select name="tipo" id="tipo" class="form-control" disabled>
                    <option value="ingreso">Ingreso</option>
                    <option value="egreso">Egreso</option>
                </select>
            </div>

            <div class="mb-3" id="empleado-container" style="display: none;">
                <label class="form-label">Empleado</label>
                <select name="ID_empleado" class="form-control">
                    <option value="">Seleccionar empleado</option>
                    @foreach(\App\Models\Empleado::all() as $emp)
                        <option value="{{ $emp->ID_empleado }}" {{ old('ID_empleado') == $emp->ID_empleado ? 'selected' : '' }}>
                            {{ $emp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Monto</label>
                <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto') }}" />
            </div>

            <div class="mb-3">
                <label class="form-label">Concepto</label>
                <input type="text" name="concepto" class="form-control" value="{{ old('concepto') }}" />
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" 
                       value="{{ old('fecha', date('Y-m-d')) }}" 
                       min="{{ date('Y-m-d') }}" 
                       required />
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
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

        document.getElementById('categoria').addEventListener('change', function() {
            const categoria = this.value;
            const tipoSelect = document.getElementById('tipo');
            const empleadoContainer = document.getElementById('empleado-container');

            if (categoria !== 'otros') {
                tipoSelect.value = categoriaTipos[categoria] || 'ingreso';
                tipoSelect.disabled = true;
            } else {
                tipoSelect.disabled = false;
            }

            if (categoria === 'salarios') {
                empleadoContainer.style.display = 'block';
            } else {
                empleadoContainer.style.display = 'none';
            }
        });

        // Trigger on page load if categoria is already selected
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('categoria').dispatchEvent(new Event('change'));
        });
    </script>
@endsection
