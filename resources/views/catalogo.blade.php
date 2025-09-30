<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-product {
            height: 200px;
            display: flex;
            flex-direction: row;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fdfdfd;
        }

        .card-product .col-img {
            flex: 1 1 40%;
            overflow: hidden;
        }

        .card-product .col-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-product .col-info {
            flex: 1 1 60%;
            padding: 10px;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-product .buttons {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: auto;
        }

        .card-product .buttons a,
        .card-product .buttons button {
            flex: 1;
        }

        .estado-vendido {
            color: red;
            text-decoration: line-through;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .card-product {
                flex-direction: column;
                height: auto;
            }
            .card-product .col-img {
                width: 100%;
                height: 200px;
            }
            .card-product .col-img img {
                height: 100%;
            }
            .card-product .col-info {
                width: 100%;
                font-size: 0.85rem;
            }
            .card-product .buttons {
                flex-direction: row;
            }
        }
    </style>
</head>
<body style="background-color:#f2f2f2;">
    <div class="container py-4">
        <h1 class="mb-4 text-center">Catálogo de Productos</h1>

        @foreach($sections as $section => $products)
            <h2 class="mt-4">{{ $section }}</h2>
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 card-product">
                            <div class="row g-0 h-100">
                                <div class="col-4 col-img">
                                    <img src="{{ $product['imagen'] }}" class="img-fluid rounded-start" alt="{{ $product['nombre'] }}">
                                </div>
                                <div class="col-8 col-info">
                                    <div>
                                        <h5 class="card-title">{{ $product['nombre'] }}</h5>
                                        <p class="card-text">{{ $product['descripcion'] }}</p>
                                        <p class="card-text"><strong>Bs. {{ number_format($product['precio'], 2) }}</strong></p>
                                        <p class="card-text">
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
                                    <div class="buttons mt-2">
                                        <a href="{{ url('/catalogo/'.$product['id']) }}" class="btn btn-primary btn-sm me-2 mb-2">Ver información</a>
                                        <button class="btn btn-success btn-sm mb-2" 
                                                @if($product['cantidad'] == 0) disabled @endif>
                                            Comprar/Fletar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</body>
</html>
