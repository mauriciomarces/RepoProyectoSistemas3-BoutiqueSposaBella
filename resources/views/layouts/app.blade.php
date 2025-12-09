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
    <style>
        /* Hide pagination SVG arrows, keep only page numbers */
        .pagination svg {
            display: none;
        }
    </style>
    @stack('styles')
</head>

<body>
    @php
    $empleado = null;
    $isAdmin = false;
    if(session('empleado_id')) {
    $empleado = DB::table('empleado')->where('ID_empleado', session('empleado_id'))->first();
    $isAdmin = $empleado && $empleado->ID_rol == 1;
    }
    @endphp

    @if(session('empleado_id') && $isAdmin)
    <!-- Sidebar para Administradores -->
    <button class="sidebar-toggle" onclick="toggleSidebarMobile()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <!-- Header del Sidebar -->
        <div class="sidebar-header d-flex justify-content-between align-items-center">
            <a class="sidebar-brand" href="{{ route('welcome') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Sposa Bella" class="sidebar-logo" />
                <span>Sposa Bella</span>
            </a>
            <button class="desktop-toggler" onclick="toggleSidebarDesktop()">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Navegación -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('empleados.index') }}">
                    <i class="fas fa-users me-2"></i><span>Empleados</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('registros_interaccion.index') }}">
                    <i class="fas fa-clipboard-list me-2"></i><span>Registros de Interacción</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('clientes.index') }}">
                    <i class="fas fa-user-friends me-2"></i><span>Clientes</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('proveedores.index') }}">
                    <i class="fas fa-truck me-2"></i><span>Proveedores</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('productos.index') }}">
                    <i class="fas fa-box me-2"></i><span>Productos</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('fletes.index') }}">
                    <i class="fas fa-shipping-fast me-2"></i><span>Fletes</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('confecciones.index') }}">
                    <i class="fas fa-cut me-2"></i><span>Confecciones</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('movimientos.index') }}">
                    <i class="fas fa-exchange-alt me-2"></i><span>Movimientos</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('analisis.index') }}">
                    <i class="fas fa-chart-line me-2"></i><span>Análisis Financiero</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a class="sidebar-nav-link" href="{{ route('trash.index') }}">
                    <i class="fas fa-trash me-2"></i><span>Papelera</span>
                </a>
            </li>
        </ul>

        <!-- Footer del Sidebar -->
        <div class="sidebar-footer">
            <div class="sidebar-user-info">
                <p class="sidebar-user-name">
                    <i class="fas fa-user-circle me-1"></i>
                    <span>{{ $empleado->nombre }}</span>
                </p>
                <p class="sidebar-user-role"><span>Administrador</span></p>
            </div>
            <form action="{{ route('empleado.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i><span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Content con margen para sidebar -->
    <div class="content-with-sidebar" id="content-wrapper">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
    @else
    <!-- Navbar horizontal para empleados regulares y usuarios no autenticados -->
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

                    <li class="nav-item d-flex align-items-center">
                        <span class="nav-link text-sposabella fw-bold" style="cursor: default;">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ $empleado->nombre }} (Empleado)
                        </span>
                    </li>
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

    <!-- Content normal -->
    <div class="container mt-4">
        @yield('content')
    </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Toggle sidebar en móviles
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }

        // Toggle sidebar en Desktop
        function toggleSidebarDesktop() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content-wrapper');

            if (sidebar && content) {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');

                // Guardar preferencia
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed ? 'true' : 'false');
            }
        }

        // Restaurar estado del sidebar al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content-wrapper');

            if (isCollapsed && sidebar && content && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
            }
        });

        // Cerrar sidebar al hacer click fuera de él en móviles
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');

            if (sidebar && toggleBtn && window.innerWidth <= 768) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = toggleBtn.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @yield('scripts')
</body>

</html>