@extends('layouts.app')

@section('styles')
<style>
    .btn-custom-primary {
        background-color: #8E805E;
        color: white;
        border: none;
    }

    .btn-custom-primary:hover {
        background-color: #7a6f52;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tshirt me-2" style="color: #8E805E;"></i>Gestión de Confecciones
        </h1>
        <a href="{{ route('confecciones.create') }}" class="btn btn-custom-primary">
            <i class="fas fa-plus me-2"></i>Nueva Confección
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #EDEEE8;">
            <h6 class="m-0 font-weight-bold" style="color: #8E805E;">Listado de Pedidos</h6>

            <form id="searchForm" action="{{ route('confecciones.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Buscar por cliente o tipo..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="clearSearch" style="display: {{ request('search') ? 'block' : 'none' }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Tipo de Confección</th>
                            <th>Fechas</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="confeccionesTableBody">
                        @include('confecciones.partials.table_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        const clearBtn = document.getElementById('clearSearch');
        const tableBody = document.getElementById('confeccionesTableBody');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;

            // Mostrar/ocultar botón limpiar
            clearBtn.style.display = query.length > 0 ? 'block' : 'none';

            timeout = setTimeout(() => {
                fetchConfecciones(query);
            }, 300);
        });

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetchConfecciones(searchInput.value);
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            fetchConfecciones('');
        });

        function fetchConfecciones(query) {
            tableBody.style.opacity = '0.5';

            fetch(`{{ route('confecciones.index') }}?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    tableBody.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.style.opacity = '1';
                });
        }
    });
</script>
@endsection