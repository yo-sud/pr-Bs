<?php

namespace App\Providers;

use App\Services\CarritoService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        
    }
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $servicio = app(CarritoService::class);
            $carrito = $servicio->contenido();
            $subtotal = 0;
            foreach ($carrito as $item) {
                if (is_array($item) && isset($item['precio'], $item['cantidad'])) {
                    $subtotal += $item['precio'] * $item['cantidad'];
                }
            }
            $view->with('cantidadCarrito', $servicio->cantidadTotal());
            $view->with('carritoItems', $carrito);
            $view->with('carritoSubtotal', $subtotal);
        });
    }
}
