<?php

namespace Database\Seeders;

use App\Models\Libro;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Seeder;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        if (Pedido::query()->exists()) {
            return;
        }

        $usuario = User::query()
            ->where('email', env('USER_EMAIL', 'user@bookshop.test'))
            ->firstOrFail();
        $libros = Libro::query()->orderBy('id')->limit(8)->get();

        if ($libros->count() < 8) {
            return;
        }

        $escenarios = [
            ['pendiente', 'pendiente', null, null, null, null],
            ['pagado', 'pagado', now()->subDays(2), null, null, null],
            ['pagado', 'enviado', now()->subDays(4), now()->subDay(), null, null],
            ['pagado', 'entregado', now()->subDays(7), now()->subDays(5), now()->subDays(2), null],
        ];

        foreach ($escenarios as $indice => [$estadoPago, $estadoPedido, $pagadoAt, $enviadoAt, $entregadoAt, $canceladoAt]) {
            $seleccion = $libros->slice($indice * 2, 2);
            $detalles = $seleccion->values()->map(function (Libro $libro, int $detalleIndice) {
                $cantidad = $detalleIndice + 1;
                $precioUnitario = (float) $libro->precio;

                return [
                    'libro_id' => $libro->id,
                    'isbn' => $libro->isbn,
                    'titulo' => $libro->titulo,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => round($cantidad * $precioUnitario, 2),
                ];
            });

            $subtotal = round($detalles->sum('subtotal'), 2);
            $envio = $subtotal >= 150 ? 0 : 12;

            $pedido = Pedido::query()->create([
                'user_id' => $usuario->id,
                'direccion' => 'Av. Arequipa 1234, Lima, Peru',
                'subtotal' => $subtotal,
                'envio' => $envio,
                'total' => $subtotal + $envio,
                'estado_pago' => $estadoPago,
                'estado_pedido' => $estadoPedido,
                'pagado_at' => $pagadoAt,
                'enviado_at' => $enviadoAt,
                'entregado_at' => $entregadoAt,
                'cancelado_at' => $canceladoAt,
            ]);

            $pedido->detalles()->createMany($detalles->all());
        }
    }
}
