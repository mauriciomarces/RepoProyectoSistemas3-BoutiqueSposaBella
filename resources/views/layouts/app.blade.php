<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique Sposa Bella</title>
    <!-- Google Fonts - Playfair Display -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Sistema CSS - Paleta Sposa Bella -->
    <link href="{{ asset('css/sistema.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navbar (paleta personalizada) -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Sposa Bella" class="nav-logo me-2" />
                <span class="text-sposabella">Sposa Bella</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(session('empleado_id'))
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('clientes.index') }}">Clientes</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('proveedores.index') }}">Proveedores</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('productos.index') }}">Productos</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('fletes.index') }}">Fletes</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('confecciones.index') }}">Confecciones</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('movimientos.index') }}">Movimientos</a></li>
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('analisis.index') }}">Análisis Financiero</a></li>
                        <li class="nav-item">
                            <form action="{{ route('empleado.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-auth ms-2" style="background-color: #8E805E; border-color: #8E805E; color: #EDEEE8;">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link text-sposabella" href="{{ route('empleado.login') }}">Iniciar Sesión</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>