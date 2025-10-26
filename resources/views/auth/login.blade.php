<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SposaBella</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <style>
        .card {
            border-radius: 1rem;
            padding: 2rem;
            max-width: 400px;
            margin: 3rem auto;
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .card h3 {
            color: #8E805E;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-control:focus {
            border-color: #8E805E;
            box-shadow: 0 0 0 0.25rem rgba(142,128,94,0.25);
        }
        .btn-login {
            background-color: #8E805E;
            color: #EDEEE8;
            width: 100%;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #A19E94;
            color: #EDEEE8;
        }
        .btn-back {
            background-color: #8E805E;
            color: #EDEEE8;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 0.5rem;
        }
        .btn-back:hover {
            background-color: #A19E94;
            color: #EDEEE8;
            transform: translateY(-2px);
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
    </style>
</head>
<body>

<div class="card">
    <h3>Iniciar sesión</h3>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
            <span class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
            </span>
        </div>

        <button type="submit" class="btn btn-login mt-3">Entrar</button>

        <div class="text-center mt-3">
            <small>¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a></small>
            <br>
            <a href="{{ url('/') }}" class="btn btn-back">Volver al inicio</a>
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
