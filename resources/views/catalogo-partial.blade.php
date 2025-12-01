@if(count($sections) > 0)
    @foreach($sections as $section => $products)
        <h2 class="mt-4">{{ $section }}</h2>
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-6 col-md-12 mb-3 d-flex">
                    <div class="card-product w-100">
                        <div class="col-img">
                            <img src="{{ $product['imagen'] }}" alt="{{ $product['nombre'] }}">
                        </div>
                        <div class="col-info">
                            <div>
                                <div class="card-title">{{ $product['nombre'] }}</div>
                                <div class="card-text">{{ $product['descripcion_corta'] }}</div>
                                <div class="card-text"><strong>Bs. {{ number_format($product['precio'], 2) }}</strong></div>
                                <div class="card-text">
                                    Cantidad: 
                                    @if($product['cantidad'] > 0)
                                        {{ $product['cantidad'] }}
                                    @else
                                        <span class="estado-vendido">Vendido</span>
                                    @endif
                                    | Estado: {{ $product['estado'] }}
                                </div>
                            </div>
                            <div class="buttons">
                <button type="button" class="btn btn-primary btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#productModal"
                    data-product='{{ e(json_encode($product)) }}'>
                                    Ver información
                                </button>
                                <button class="btn btn-success btn-sm direct-buy" data-product='{{ e(json_encode($product)) }}' @if($product['cantidad'] == 0) disabled @endif>
                                    Comprar/Fletar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    @if(isset($paginatedProductos))
    <div class="d-flex justify-content-center mt-4">
        {{ $paginatedProductos->links() }}
    </div>
    @endif

@else
    <div class="alert alert-info">No se encontraron productos con los filtros seleccionados.</div>
@endif
