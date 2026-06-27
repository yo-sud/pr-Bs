<?php

namespace App\Services;

use App\Models\EventoPago;
use App\Models\Repartidor;
use App\Models\TransaccionPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PagoService
{
    public const ESTADOS_EXTERNOS = ['aprobado', 'rechazado', 'pendiente'];

    public function procesar(array $evento): TransaccionPago
    {
        return DB::transaction(function () use ($evento) {
            $eventoExistente = EventoPago::query()
                ->where('evento_id', $evento['evento_id'])
                ->first();

            if ($eventoExistente) {
                return $eventoExistente->transaccion;
            }

            $transaccion = TransaccionPago::query()
                ->with('pedido.usuario')
                ->where('referencia', $evento['referencia'])
                ->lockForUpdate()
                ->firstOrFail();

            $eventoExistente = EventoPago::query()
                ->where('evento_id', $evento['evento_id'])
                ->first();

            if ($eventoExistente) {
                return $eventoExistente->transaccion;
            }

            $monto = number_format((float) $evento['monto'], 2, '.', '');

            if ($monto !== $transaccion->monto || $monto !== $transaccion->pedido->total) {
                throw ValidationException::withMessages([
                    'monto' => "El monto del evento (S/ $monto) no coincide con el total registrado en el pedido (S/ {$transaccion->pedido->total}).",
                ]);
            }

            if (($evento['moneda'] ?? 'PEN') !== 'PEN') {
                throw ValidationException::withMessages([
                    'moneda' => 'La moneda del evento no es valida.',
                ]);
            }

            if (! in_array($evento['estado'], self::ESTADOS_EXTERNOS, true)) {
                throw ValidationException::withMessages([
                    'estado' => 'El estado de pago no es valido.',
                ]);
            }

            $estadoTransaccion = $transaccion->pedido->estado_pago === 'pagado'
                ? 'aprobado'
                : $evento['estado'];

            $transaccion->update([
                'estado' => $estadoTransaccion,
                'payload' => $evento,
                'procesado_at' => now(),
            ]);

            $transaccion->eventos()->create([
                'evento_id' => $evento['evento_id'],
                'estado' => $evento['estado'],
                'payload' => $evento,
                'procesado_at' => now(),
            ]);

            $pedido = $transaccion->pedido;

            if ($evento['estado'] === 'aprobado' && $pedido->estado_pago !== 'pagado') {
                if ($pedido->estado_pedido === 'cancelado') {
                    throw ValidationException::withMessages([
                        'pedido' => 'No se puede aprobar el pago de un pedido cancelado.',
                    ]);
                }

                // Asigna el repartidor activo con menor carga de pedidos en curso.
                $repartidor = Repartidor::where('activo', true)
                    ->withCount(['pedidos as pedidos_activos_count' => function ($q) {
                        $q->whereNotIn('estado_pedido', ['entregado', 'cancelado']);
                    }])
                    ->orderBy('pedidos_activos_count')
                    ->first();

                $pedido->update([
                    'estado_pago'    => 'pagado',
                    'repartidor_id'  => $repartidor?->id,
                ]);
                app(PedidoEstadoService::class)->cambiar(
                    $pedido,
                    'pagado',
                    null,
                    'Pago aprobado por la pasarela simulada.',
                );
            } elseif ($evento['estado'] === 'rechazado' && $pedido->estado_pago !== 'pagado') {
                $pedido->update(['estado_pago' => 'fallido']);
            } elseif ($evento['estado'] === 'pendiente' && $pedido->estado_pago !== 'pagado') {
                $pedido->update(['estado_pago' => 'pendiente']);
            }

            return $transaccion;
        }, 3);
    }
}