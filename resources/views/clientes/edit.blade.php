<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EDEEE8;
            font-family: 'Playfair Display', serif;
        }

        .navbar {
            background-color: #EDEEE8;
            border-bottom: 2px solid #C1BAA2;
            margin-bottom: 2rem;
        }

        .card {
            border-color: #C1BAA2;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #8E805E;
            color: white;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #8E805E;
            border-color: #8E805E;
        }

        .btn-primary:hover {
            background-color: #A19E94;
            border-color: #A19E94;
        }

        .btn-secondary {
            background-color: #C1BAA2;
            border-color: #C1BAA2;
        }

        .btn-secondary:hover {
            background-color: #A19E94;
            border-color: #A19E94;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container position-relative">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60">
            </a>
            <h1 class="navbar-title position-absolute start-50 translate-middle-x mb-0">Editar Cliente</h1>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

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

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Datos del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('clientes.update', $cliente->ID_cliente) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" class="form-control @error('nombre') is-invalid @enderror" required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" name="correo" value="{{ old('correo', $cliente->correo) }}" class="form-control @error('correo') is-invalid @enderror" required>
                                    @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="form-control @error('telefono') is-invalid @enderror" required>
                                    @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" class="form-control @error('direccion') is-invalid @enderror">
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3" style="color: #8E805E;">Medidas (cm)</h6>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Busto</label>
                                    <input type="number" step="0.01" name="busto" value="{{ old('busto', $cliente->busto) }}" class="form-control @error('busto') is-invalid @enderror" required>
                                    @error('busto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cintura</label>
                                    <input type="number" step="0.01" name="cintura" value="{{ old('cintura', $cliente->cintura) }}" class="form-control @error('cintura') is-invalid @enderror" required>
                                    @error('cintura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cadera</label>
                                    <input type="number" step="0.01" name="cadera" value="{{ old('cadera', $cliente->cadera) }}" class="form-control @error('cadera') is-invalid @enderror" required>
                                    @error('cadera')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Actualizar Cliente
                                </button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>