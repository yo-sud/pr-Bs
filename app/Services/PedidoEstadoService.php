<?php

namespace App\Services;

use App\Mail\PedidoActualizadoMail;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PedidoEstadoService
{
    private const TRANSICIONES = [
        'pendiente' => ['pagado', 'cancelado'],
        'pagado' => ['preparando', 'cancelado'],
        'preparando' => ['enviado', 'cancelado'],
        'enviado' => ['entregado'],
        'entregado' => [],
        'cancelado' => [],
    ];

    public function cambiar(
        Pedido $pedido,
        string $nuevoEstado,
        ?User $usuario,
        ?string $observacion = null,
        bool $notificar = true,
    ): Pedido {
        $estadoAnterior = $pedido->estado_pedido;

        if ($estadoAnterior === $nuevoEstado) {
            return $pedido;
        }

        if (! in_array($nuevoEstado, self::TRANSICIONES[$estadoAnterior] ?? [], true)) {
            throw ValidationException::withMessages([
                'estado' => "No se puede cambiar el pedido de {$estadoAnterior} a {$nuevoEstado}.",
            ]);
        }

        $fechas = match ($nuevoEstado) {
            'pagado' => ['pagado_at' => now()],
            'enviado' => ['enviado_at' => now()],
            'entregado' => ['entregado_at' => now()],
            'cancelado' => ['cancelado_at' => now()],
            default => [],
        };

        $pedido->update([
            'estado_pedido' => $nuevoEstado,
            ...$fechas,
        ]);

        $pedido->historialEstados()->create([
            'user_id' => $usuario?->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $nuevoEstado,
            'observacion' => $observacion,
        ]);

        if ($notificar && $pedido->usuario) {
            Mail::to($pedido->usuario)->queue(new PedidoActualizadoMail(
                $pedido->fresh('usuario'),
                "Pedido {$nuevoEstado}",
                $observacion,
            ));
        }

        return $pedido;
    }

    public function registrarCreacion(Pedido $pedido): void
    {
        $pedido->historialEstados()->create([
            'user_id' => $pedido->user_id,
            'estado_anterior' => null,
            'estado_nuevo' => 'pendiente',
            'observacion' => 'Pedido confirmado por el cliente.',
        ]);

        if ($pedido->usuario) {
            Mail::to($pedido->usuario)->queue(new PedidoActualizadoMail(
                $pedido->fresh('usuario'),
                'Pedido recibido',
                'Recibimos tu pedido y estamos esperando la confirmacion del pago.',
            ));
        }
    }
}
