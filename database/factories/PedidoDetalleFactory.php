<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PedidoDetalle>
 */
class PedidoDetalleFactory extends Factory
{
    public function definition(): array
    {
        $cantidad = fake()->numberBetween(1, 4);
        $precioUnitario = fake()->randomFloat(2, 20, 120);

        return [
            'pedido_id' => Pedido::factory(),
            'libro_id' => Libro::factory(),
            'isbn' => fake()->isbn13(),
            'titulo' => fake()->sentence(3),
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'subtotal' => $cantidad * $precioUnitario,
        ];
    }
}
