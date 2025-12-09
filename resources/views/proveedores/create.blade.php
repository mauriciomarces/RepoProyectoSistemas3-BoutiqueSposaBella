@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header" style="background-color: #8E805E; color: white;">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Proveedor
                    </h3>
                </div>
                <div class="card-body" style="background-color: #EDEEE8;">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Por favor, corrija los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('proveedores.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre del Proveedor <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                required
                                placeholder="Nombre completo del proveedor">
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo_proveedor" class="form-label">
                                Tipo de Proveedor <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tipo_proveedor') is-invalid @enderror"
                                id="tipo_proveedor"
                                name="tipo_proveedor"
                                required>
                                <option value="" disabled {{ old('tipo_proveedor') ? '' : 'selected' }}>Seleccione un tipo...</option>
                                <option value="Telas" {{ old('tipo_proveedor') == 'Telas' ? 'selected' : '' }}>Telas</option>
                                <option value="Accesorios" {{ old('tipo_proveedor') == 'Accesorios' ? 'selected' : '' }}>Accesorios</option>
                                <option value="Mercería" {{ old('tipo_proveedor') == 'Mercería' ? 'selected' : '' }}>Mercería</option>
                                <option value="Maquinaria" {{ old('tipo_proveedor') == 'Maquinaria' ? 'selected' : '' }}>Maquinaria</option>
                                <option value="Servicios" {{ old('tipo_proveedor') == 'Servicios' ? 'selected' : '' }}>Servicios</option>
                                <option value="Otros" {{ old('tipo_proveedor') == 'Otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                            @error('tipo_proveedor')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Seleccione la categoría principal del proveedor
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror"
                                id="direccion"
                                name="direccion"
                                rows="2"
                                placeholder="Dirección completa del proveedor">{{ old('direccion') }}</textarea>
                            @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text"
                                class="form-control @error('telefono') is-invalid @enderror"
                                id="telefono"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                maxlength="8"
                                placeholder="Ej: 71234567">
                            @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="telefonoHelp">
                                <i class="fas fa-info-circle me-1"></i>
                                Debe tener 8 dígitos y comenzar con 6 o 7
                            </small>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-custom-primary">
                                <i class="fas fa-save me-2"></i>Guardar Proveedor
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-custom-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-custom-primary {
        background-color: #8E805E;
        color: white;
        border: none;
    }

    .btn-custom-primary:hover {
        background-color: #7a6f52;
        color: white;
    }

    .btn-custom-secondary {
        background-color: #C1BAA2;
        color: #333;
        border: none;
    }

    .btn-custom-secondary:hover {
        background-color: #afa593;
        color: #333;
    }
</style>
@endsection

@section('scripts')
<script>
    document.getElementById('telefono').addEventListener('input', function(e) {
        let value = e.target.value;

        // Solo permitir números
        value = value.replace(/[^0-9]/g, '');

        // Si hay caracteres, validar que empiece con 6 o 7
        if (value.length > 0) {
            if (!['6', '7'].includes(value[0])) {
                // Si el primer dígito no es 6 o 7, lo eliminamos
                // o si estamos escribiendo, simplemente prevenimos ese input?
                // Mejor estrategia: Si el primer char no es válido, limpiar todo o quitar ese char.
                // Aquí quitamos el primer char si no es válido.
                value = value.substring(1);
            }
        }

        // Limitar a 8 dígitos
        if (value.length > 8) {
            value = value.slice(0, 8);
        }

        e.target.value = value;

        // Feedback visual
        const helpText = document.getElementById('telefonoHelp');
        if (value.length > 0 && value.length < 8) {
            helpText.classList.add('text-warning');
            helpText.classList.remove('text-muted', 'text-success');
        } else if (value.length === 8) {
            helpText.classList.remove('text-warning', 'text-muted');
            helpText.classList.add('text-success');
        } else {
            helpText.classList.remove('text-warning', 'text-success');
            helpText.classList.add('text-muted');
        }
    });
</script>
@endsection