<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Empleados - SposaBella</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #8E805E 0%, #C1BAA2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
        }
        .card {
            border-radius: 1.5rem;
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            background-color: #fff;
        }
        .card-header {
            background: none;
            border: none;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .card-header h3 {
            color: #8E805E;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .card-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: #8E805E;
            box-shadow: 0 0 0 0.25rem rgba(142,128,94,0.25);
        }
        .btn-login {
            background-color: #8E805E;
            color: #fff;
            width: 100%;
            font-weight: 600;
            padding: 0.75rem;
            border: none;
        }
        .btn-login:hover {
            background-color: #A19E94;
            color: #fff;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 38px;
            color: #888;
        }
        .toggle-password:hover {
            color: #8E805E;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
        }
        .logo-container i {
            font-size: 4rem;
            color: #8E805E;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo-container">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    
    <div class="card-header">
        <h3>Sistema Administrativo</h3>
        <p>Acceso exclusivo para empleados</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('empleado.login.post') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="correo" class="form-label">
                <i class="bi bi-envelope-fill me-1"></i>Correo Corporativo
            </label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="nombre@spozabella.com" required autofocus>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">
                <i class="bi bi-lock-fill me-1"></i>Contraseña
            </label>
            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
            <span class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
            </span>
        </div>

        <button type="submit" class="btn btn-login mt-3">
            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-house-door me-1"></i>Volver al Inicio
            </a>
        </div>

        <div class="text-center mt-2">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Acceso restringido a personal autorizado
            </small>
        </div>
    </form>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");
        if(input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye-slash-fill","bi-eye-fill");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-fill","bi-eye-slash-fill");
        }
    }
</script>

</body>
</html>