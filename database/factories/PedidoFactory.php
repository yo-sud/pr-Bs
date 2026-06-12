<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 30, 400);
        $envio = $subtotal >= 150 ? 0 : 12;

        return [
            'user_id' => User::factory(),
            'direccion' => fake()->address(),
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $subtotal + $envio,
            'estado_pago' => 'pendiente',
            'estado_pedido' => 'pendiente',
        ];
    }

    public function pagado(): static
    {
        return $this->state(fn () => [
            'estado_pago' => 'pagado',
            'estado_pedido' => 'pagado',
            'pagado_at' => now(),
        ]);
    }

    public function enviado(): static
    {
        return $this->state(fn () => [
            'estado_pago' => 'pagado',
            'estado_pedido' => 'enviado',
            'pagado_at' => now()->subDays(2),
            'enviado_at' => now()->subDay(),
        ]);
    }

    public function entregado(): static
    {
        return $this->state(fn () => [
            'estado_pago' => 'pagado',
            'estado_pedido' => 'entregado',
            'pagado_at' => now()->subDays(4),
            'enviado_at' => now()->subDays(2),
            'entregado_at' => now(),
        ]);
    }

    public function cancelado(): static
    {
        return $this->state(fn () => [
            'estado_pago' => 'reembolsado',
            'estado_pedido' => 'cancelado',
            'pagado_at' => now()->subDay(),
            'cancelado_at' => now(),
        ]);
    }
}
