<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img-container {
            width: 100%;
            max-height: 500px; /* tamaño máximo para la imagen */
            overflow: hidden;
            border-radius: 8px;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* ajusta la imagen sin recortarla */
        }

        .product-info h1 {
            font-size: 1.8rem;
        }

        .price {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .estado-vendido {
            color: red;
            text-decoration: line-through;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .product-img-container {
                max-height: 300px;
            }
            .product-info h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body style="background-color:#f2f2f2;">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="product-img-container">
                    <img src="{{ $product['imagen'] }}" class="product-img" alt="{{ $product['nombre'] }}">
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column justify-content-between product-info">
                <div>
                    <h1>{{ $product['nombre'] }}</h1>
                    <p>{{ $product['descripcion'] }}</p>
                    <p class="price">
                        Bs. {{ number_format($product['precio'], 2) }}
                    </p>
                    <p>
                        Cantidad: 
                        @if($product['cantidad'] > 0)
                            {{ $product['cantidad'] }}
                        @else
                            <span class="estado-vendido">Vendido</span>
                        @endif
                        | Estado: 
                        @if($product['cantidad'] > 0)
                            {{ $product['estado'] }}
                        @else
                            <span class="estado-vendido">{{ $product['estado'] }}</span>
                        @endif
                    </p>
                </div>
                <div class="mt-3">
                    <button class="btn btn-success mb-2" 
                            @if($product['cantidad'] == 0) disabled @endif>
                        Comprar/Fletar
                    </button>
                    <a href="{{ url('/catalogo') }}" class="btn btn-secondary mb-2">Volver al catálogo</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
