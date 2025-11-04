<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SposaBella</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <style>
        .card {
            border-radius: 1rem;
            padding: 2rem;
            max-width: 450px;
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
        .btn-register {
            background-color: #8E805E;
            color: #EDEEE8;
            width: 100%;
            font-weight: 600;
        }
        .btn-register:hover {
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
        .password-rules {
            list-style: none;
            padding-left: 0;
            margin-top: 0.5rem;
        }
        .password-rules li {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: gray;
            margin-bottom: 0.2rem;
        }
        .password-rules li.valid {
            color: green;
        }
        .password-rules li i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>

<div class="card">
    <h3>Crear cuenta</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('registro.guardar') }}" method="POST" novalidate>
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="María López" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="correo@ejemplo.com" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" id="telefono" name="telefono" class="form-control" placeholder="76451239" required>
            <small class="text-muted">Debe iniciar con 6 o 7</small>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Av. Velasco 125" required>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
            <span class="toggle-password" onclick="togglePassword()">
                <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
            </span>
        </div>

        <ul class="password-rules" id="passwordRules">
            <li id="rule-length"><i class="bi bi-x-circle-fill"></i>Mínimo 8 caracteres</li>
            <li id="rule-uppercase"><i class="bi bi-x-circle-fill"></i>Al menos una letra mayúscula</li>
            <li id="rule-lowercase"><i class="bi bi-x-circle-fill"></i>Al menos una letra minúscula</li>
            <li id="rule-number"><i class="bi bi-x-circle-fill"></i>Al menos un número</li>
            <li id="rule-special"><i class="bi bi-x-circle-fill"></i>Al menos un carácter especial (!@#$...)</li>
        </ul>

        <button type="submit" class="btn btn-register mt-3">Registrarse</button>

        <div class="text-center mt-3">
            <small>¿Ya tienes una cuenta? <a href="{{ route('empleado.login') }}">Inicia sesión</a></small>
            <br>
            <a href="{{ url('/') }}" class="btn btn-back">Volver al inicio</a>
        </div>
    </form>
</div>

<script>
    document.getElementById("nombre").addEventListener("input", function() {
        this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g,'');
    });

    document.getElementById("telefono").addEventListener("input", function() {
        this.value = this.value.replace(/[^0-9]/g,'');
        if(this.value.length > 0 && !['6','7'].includes(this.value[0])) {
            this.value = '';
        }
    });

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

    const passwordInput = document.getElementById("password");
    const rules = {
        length: /.{8,}/,
        uppercase: /[A-Z]/,
        lowercase: /[a-z]/,
        number: /[0-9]/,
        special: /[^A-Za-z0-9]/
    };

    passwordInput.addEventListener("input", function() {
        for (let key in rules) {
            const ruleElement = document.getElementById(`rule-${key}`);
            const icon = ruleElement.querySelector('i');
            if(rules[key].test(this.value)) {
                ruleElement.classList.add("valid");
                icon.classList.replace("bi-x-circle-fill","bi-check-circle-fill");
            } else {
                ruleElement.classList.remove("valid");
                icon.classList.replace("bi-check-circle-fill","bi-x-circle-fill");
            }
        }
    });
</script>

</body>
</html>
