<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Venta;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Producto;
use App\Observers\VentaObserver;
use App\Observers\EmpleadoObserver;
use App\Observers\ClienteObserver;
use App\Observers\ProductoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Venta::observe(VentaObserver::class);
        Empleado::observe(EmpleadoObserver::class);
        Cliente::observe(ClienteObserver::class);
        Producto::observe(ProductoObserver::class);
    }
}
