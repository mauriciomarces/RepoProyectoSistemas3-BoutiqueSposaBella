<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }
        .btn-primary {
            background-color: #8E805E;
            border-color: #8E805E;
        }
        .btn-primary:hover {
            background-color: #A19E94;
            border-color: #A19E94;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="60">
            </a>
            <h1 class="navbar-brand mb-0 h1">Registro de Cliente</h1>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea class="form-control" name="direccion" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Medidas</h5>
                            <div class="mb-3">
                                <label class="form-label">Busto (cm)</label>
                                <input type="number" class="form-control" name="busto" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cintura (cm)</label>
                                <input type="number" class="form-control" name="cintura" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cadera (cm)</label>
                                <input type="number" class="form-control" name="cadera" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>