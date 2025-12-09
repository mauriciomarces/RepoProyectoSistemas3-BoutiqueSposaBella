<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente</title>
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

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            @if(session('restore_id'))
            <br>
            <a href="{{ route('trash.restore', ['type' => 'cliente', 'id' => session('restore_id')]) }}" class="btn btn-sm btn-outline-primary mt-2">
                <i class="fas fa-undo"></i> Restaurar Cliente
            </a>
            @endif
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
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required pattern="^[a-zA-ZÀ-ÿ\s]+$" title="Solo se permiten letras y espacios">
                                <div class="invalid-feedback">
                                    Solo se permiten letras y espacios.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" id="telefono" required pattern="^[67]\d{7}$" maxlength="8" title="El teléfono debe comenzar con 6 o 7 y tener exactamente 8 dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 8); if (this.value && !/^[67]/.test(this.value)) { this.value = ''; }">
                                <div class="invalid-feedback">
                                    El teléfono debe comenzar con 6 o 7 y tener exactamente 8 dígitos.
                                </div>
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
                                <input type="number" class="form-control" name="busto" required min="30" max="200" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cintura (cm)</label>
                                <input type="number" class="form-control" name="cintura" required min="20" max="200" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cadera (cm)</label>
                                <input type="number" class="form-control" name="cadera" required min="30" max="200" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const telefonoInput = document.getElementById('telefono');

            // Validación en tiempo real para nombre
            nombreInput.addEventListener('input', function() {
                const value = this.value;
                if (value && !/^[a-zA-ZÀ-ÿ\s]+$/.test(value)) {
                    this.setCustomValidity('Solo se permiten letras y espacios');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            // Validación en tiempo real para teléfono
            telefonoInput.addEventListener('input', function() {
                const value = this.value;
                if (value && !/^[67]\d{0,7}$/.test(value)) {
                    this.setCustomValidity('El teléfono debe comenzar con 6 o 7 y contener solo números');
                    this.classList.add('is-invalid');
                } else if (value.length > 8) {
                    this.setCustomValidity('El teléfono no puede tener más de 8 dígitos');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            // Validación final antes del envío
            document.querySelector('form').addEventListener('submit', function(e) {
                const nombre = nombreInput.value.trim();
                const telefono = telefonoInput.value.trim();

                if (nombre && !/^[a-zA-ZÀ-ÿ\s]+$/.test(nombre)) {
                    e.preventDefault();
                    nombreInput.focus();
                    return false;
                }

                if (telefono && !/^[67]\d{7}$/.test(telefono)) {
                    e.preventDefault();
                    telefonoInput.focus();
                    return false;
                }
            });
        });
    </script>
</body>

</html>