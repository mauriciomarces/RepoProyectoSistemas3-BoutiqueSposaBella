<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo</title>
<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/catalogo.css') }}" rel="stylesheet">
</head>
<body style="background-color:#EDEEE8;">

<nav class="navbar navbar-expand-lg">
<div class="container position-relative">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Boutique Sposa Bella" class="nav-logo">
    </a>
    <h1 class="navbar-title position-absolute start-50 translate-middle-x mb-0">Catálogo de Productos</h1>
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
    @include('catalogo-partial', ['sections' => $sections])
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

    // Modal
    productModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const product = JSON.parse(button.getAttribute('data-product'));

        const modal = this;
        modal.querySelector('.modal-title').textContent = product.nombre;
        modal.querySelector('.product-title').textContent = product.nombre;
        modal.querySelector('.product-description').textContent = product.descripcion;
        modal.querySelector('.product-price').textContent = `Bs. ${parseFloat(product.precio).toFixed(2)}`;
        modal.querySelector('.modal-img').src = product.imagen;
        modal.querySelector('.modal-img').alt = product.nombre;

        const stockText = product.cantidad > 0 
            ? `Cantidad: ${product.cantidad} | Estado: ${product.estado}`
            : `<span class="estado-vendido">Vendido</span>`;
        modal.querySelector('.product-stock').innerHTML = stockText;

        const buyButton = modal.querySelector('.buy-button');
        buyButton.disabled = product.cantidad === 0;
        buyButton.classList.toggle('disabled', product.cantidad === 0);
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

</body>
</html>
