<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Confecciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Paleta de colores de SposaBella */
        :root {
            --color-primary: #8E805E;
            --color-secondary: #D4C4A0;
            --color-dark: #2c2c2c;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .card-header {
            background-color: var(--color-primary);
            color: white;
        }
        
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .btn-primary:hover {
            background-color: #7a6f51;
            border-color: #7a6f51;
        }
        
        .form-label {
            color: var(--color-dark);
            font-weight: 500;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit"></i> Editar Producto
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('productos.update', $producto->ID_producto) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre del Producto *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="{{ old('nombre', $producto->nombre) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="categoria" class="form-label">Categoría *</label>
                                        <select class="form-select" id="categoria" name="categoria" required>
                                            <option value="">Seleccionar categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria }}" 
                                                    {{ old('categoria', $producto->categoria) == $categoria ? 'selected' : '' }}>
                                                    {{ $categoria }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_corta" class="form-label">Descripción Corta *</label>
                                <input type="text" class="form-control" id="descripcion_corta" name="descripcion_corta"
                                       value="{{ old('descripcion_corta', $producto->descripcion_corta) }}" required maxlength="255">
                                <div class="form-text">Máximo 255 caracteres</div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción Completa</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="precio" class="form-label">Precio (Bs) *</label>
                                        <input type="number" step="0.01" class="form-control" id="precio" name="precio"
                                               value="{{ old('precio', $producto->precio) }}" required min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock *</label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                               value="{{ old('stock', $producto->stock) }}" required min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_minimo" class="form-label">Stock Mínimo *</label>
                                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo"
                                               value="{{ old('stock_minimo', $producto->stock_minimo) }}" required min="0">
                                        <div class="form-text">Alerta cuando el stock llegue a este nivel</div>
                                        @error('stock_minimo')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ID_proveedor" class="form-label">Proveedor *</label>
                                        <select class="form-select" id="ID_proveedor" name="ID_proveedor" required>
                                            <option value="">Seleccionar proveedor</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->ID_proveedor }}"
                                                    {{ old('ID_proveedor', $producto->ID_proveedor) == $proveedor->ID_proveedor ? 'selected' : '' }}>
                                                    {{ $proveedor->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="imagen" class="form-label">URL de Imagen</label>
                                <input type="text" class="form-control" id="imagen" name="imagen"
                                       value="{{ old('imagen', $producto->imagen) }}" placeholder="ejemplo: producto.jpg">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('productos.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Producto
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