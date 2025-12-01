<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
<link href="{{ asset('css/catalogo.css') }}" rel="stylesheet">
</head>
<body style="background-color:#EDEEE8;">

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Boutique Sposa Bella" class="nav-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/catalogo') }}">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#nosotros">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#contacto">Contacto</a>
                </li>
                @if(session('empleado_nombre'))
                    <li class="nav-item ms-3">
                        <span class="navbar-text">Hola, {{ session('empleado_nombre') }}!</span>
                    </li>
                    <li class="nav-item ms-2">
                        <form action="{{ route('empleado.logout') }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-auth">Cerrar Sesión</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-2">
                        <a class="btn btn-auth" href="{{ route('empleado.login') }}">Iniciar Sesión</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
<div class="row mb-4">
<div class="col-md-12">
    <div class="filter-container p-3 bg-white rounded shadow-sm">
        <form id="filterForm" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Categoría</label>
                <select class="form-select" name="seccion">
                    <option value="">Todas</option>
                    @php $uniqueSections = array_keys($sections); sort($uniqueSections); @endphp
                    @foreach($uniqueSections as $seccion)
                        <option value="{{ $seccion }}">{{ $seccion }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="">Todos</option>
                    <option value="disponible">Disponible</option>
                    <option value="vendido">Vendido</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filtrar</button>
                <button type="button" class="btn btn-outline-secondary" id="clearFilters">Limpiar</button>
            </div>
        </form>
    </div>
</div>
</div>

<div id="productsContainer">
    @include('catalogo-partial', ['sections' => $sections, 'paginatedProductos' => $paginatedProductos])
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header border-0">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="modal-img-container">
                    <img src="" class="modal-img img-fluid" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="product-title"></h3>
                <p class="product-description"></p>
                <p class="product-price"></p>
                <p class="product-stock"></p>
            </div>
        </div>
    </div>
    <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success buy-button">Comprar/Fletar</button>
    </div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productModal = document.getElementById('productModal');
    const filterForm = document.getElementById('filterForm');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const productsContainer = document.getElementById('productsContainer');

    // Helper para decodificar entidades HTML (por si el JSON vino escapado en el atributo)
    function decodeHtmlEntities(str) {
        if (!str) return str;
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    }

    // Modal
    productModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        let product = null;
        try {
            const raw = button.getAttribute('data-product');
            const decoded = decodeHtmlEntities(raw);
            product = JSON.parse(decoded || raw);
        } catch (err) {
            console.error('No se pudo parsear data-product del botón del modal:', err, button && button.getAttribute('data-product'));
            return; // evitar continuar si no hay datos
        }

        const modal = this;
        modal.querySelector('.modal-title').textContent = product.nombre || '';
        modal.querySelector('.product-title').textContent = product.nombre || '';
        modal.querySelector('.product-description').textContent = product.descripcion || '';
        modal.querySelector('.product-price').textContent = product.precio ? `Bs. ${parseFloat(product.precio).toFixed(2)}` : '';
        modal.querySelector('.modal-img').src = product.imagen || '';
        modal.querySelector('.modal-img').alt = product.nombre || '';

        const stockText = product.cantidad > 0 
            ? `Cantidad: ${product.cantidad} | Estado: ${product.estado || ''}`
            : `<span class="estado-vendido">Vendido</span>`;
        modal.querySelector('.product-stock').innerHTML = stockText;

        const buyButton = modal.querySelector('.buy-button');
        buyButton.disabled = product.cantidad === 0;
        buyButton.classList.toggle('disabled', product.cantidad === 0);

        // Preparar acción de Comprar/Fletar: abrir WhatsApp con mensaje predefinido
        const phone = '59162498914'; // número destino sin +
        const message = `Hola SposaBella, estoy interesada(o) en el producto: ${product.nombre}`;
        const waUrl = `https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(message)}`;
        // Evitar múltiples listeners: reemplazar onclick
        buyButton.onclick = function(e) {
            if (product.cantidad === 0) return;
            window.open(waUrl, '_blank');
        };
    });

    // Manejo de botones 'Comprar/Fletar' directos en la lista (sin abrir modal)
    productsContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.direct-buy');
        if (!btn) return;
        e.preventDefault();
        if (btn.disabled) return;
        try {
            const raw = btn.getAttribute('data-product');
            const decoded = decodeHtmlEntities(raw);
            const product = JSON.parse(decoded || raw);
            const phone = '59162498914';
            const message = `Hola SposaBella, estoy interesada(o) en el producto: ${product.nombre}`;
            const waUrl = `https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
        } catch (err) {
            console.error('Error al abrir WhatsApp:', err);
        }
    });

    // Función para aplicar filtros
    function applyFilters() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        fetch(`/catalogo?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            productsContainer.innerHTML = html;
        })
        .catch(err => console.error('Error al filtrar productos:', err));
    }

    // Evento al enviar el formulario
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Evento para limpiar filtros
    clearFiltersBtn.addEventListener('click', function() {
        filterForm.reset();
        applyFilters();
    });
});
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('productsContainer');
    const filterForm = document.getElementById('filterForm');

    // Helper para decodificar entidades HTML (por si el JSON vino escapado en el atributo)
    function decodeHtmlEntities(str) {
        if (!str) return str;
        const txt = document.createElement('textarea');
        txt.innerHTML = str;
        return txt.value;
    }

    // Esto aplica filtros vía AJAX
    function applyFilters(page = 1) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        params.set('page', page);

        fetch(`/catalogo?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            productsContainer.innerHTML = html;
            attachPaginationHandlers(); // Re-attaching after update
        })
        .catch(err => console.error('Error al filtrar productos:', err));
    }

    // Asociación de manejadores a los links de paginación para AJAX
    function attachPaginationHandlers() {
        productsContainer.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                applyFilters(page);
            });
        });
    }

    // Al enviar el formulario de filtros
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Limpiar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        filterForm.reset();
        applyFilters();
    });

    // Inicial attach pagination handlers
    attachPaginationHandlers();
});
</script>

</body>
</html>
