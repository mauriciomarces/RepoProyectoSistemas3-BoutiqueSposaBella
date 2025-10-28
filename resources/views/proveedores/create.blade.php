<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Proveedor - Confecciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle"></i> Crear Nuevo Proveedor
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('proveedores.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Proveedor *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="{{ old('nombre') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_proveedor" class="form-label">Tipo de Proveedor *</label>
                                <input type="text" class="form-control" id="tipo_proveedor" name="tipo_proveedor"
                                       value="{{ old('tipo_proveedor') }}" required 
                                       placeholder="Ej: Tela, Accesorios, Hilos, etc.">
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2">{{ old('direccion') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono"
                                       value="{{ old('telefono') }}">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Proveedor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>