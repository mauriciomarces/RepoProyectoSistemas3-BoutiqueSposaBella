<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
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
        .table {
            background-color: white;
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
            <h1 class="navbar-brand mb-0 h1">Gestión de Clientes</h1>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    Nuevo Cliente
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente['id'] }}</td>
                                    <td>{{ $cliente['nombre'] }}</td>
                                    <td>{{ $cliente['correo'] }}</td>
                                    <td>{{ $cliente['telefono'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detalleModal"
                                                data-cliente="{{ json_encode($cliente) }}">
                                            Ver
                                        </button>
                                        <a href="{{ route('clientes.edit', $cliente['id']) }}" 
                                           class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                        <form action="{{ route('clientes.destroy', $cliente['id']) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalle -->
    <div class="modal fade" id="detalleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información Personal</h6>
                            <p><strong>Nombre:</strong> <span id="modal-nombre"></span></p>
                            <p><strong>Correo:</strong> <span id="modal-correo"></span></p>
                            <p><strong>Teléfono:</strong> <span id="modal-telefono"></span></p>
                            <p><strong>Dirección:</strong> <span id="modal-direccion"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Medidas</h6>
                            <p><strong>Busto:</strong> <span id="modal-busto"></span> cm</p>
                            <p><strong>Cintura:</strong> <span id="modal-cintura"></span> cm</p>
                            <p><strong>Cadera:</strong> <span id="modal-cadera"></span> cm</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Historial de Compras</h6>
                            <div id="modal-historial"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detalleModal = document.getElementById('detalleModal');
            detalleModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const cliente = JSON.parse(button.getAttribute('data-cliente'));
                
                document.getElementById('modal-nombre').textContent = cliente.nombre;
                document.getElementById('modal-correo').textContent = cliente.correo;
                document.getElementById('modal-telefono').textContent = cliente.telefono;
                document.getElementById('modal-direccion').textContent = cliente.direccion;
                document.getElementById('modal-busto').textContent = cliente.medidas.busto;
                document.getElementById('modal-cintura').textContent = cliente.medidas.cintura;
                document.getElementById('modal-cadera').textContent = cliente.medidas.cadera;
                
                const historialDiv = document.getElementById('modal-historial');
                if (cliente.historial_compras && cliente.historial_compras.length > 0) {
                    let historialHTML = '<ul class="list-group">';
                    cliente.historial_compras.forEach(compra => {
                        historialHTML += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>${compra.fecha} - ${compra.producto}</span>
                                    <span>Bs. ${compra.precio}</span>
                                </div>
                            </li>`;
                    });
                    historialHTML += '</ul>';
                    historialDiv.innerHTML = historialHTML;
                } else {
                    historialDiv.innerHTML = '<p class="text-muted">No hay compras registradas</p>';
                }
            });
        });
    </script>
</body>
</html>