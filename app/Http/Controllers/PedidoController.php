<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use App\Services\PedidoEstadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        return view('pedidos.index', [
            'pedidos' => $request->user()->pedidos()
                ->withCount('detalles')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Request $request, Pedido $pedido): View
    {
        $this->autorizar($request, $pedido);

        return view('pedidos.show', [
            'pedido' => $pedido->load([
                'usuario',
                'repartidor',
                'detalles.libro',
                'transaccionesPago',
                'historialEstados.usuario',
            ]),
        ]);
    }

    public function cancel(
        Request $request,
        Pedido $pedido,
        PedidoEstadoService $estados,
    ): RedirectResponse {
        $this->autorizar($request, $pedido);

        DB::transaction(function () use ($request, $pedido, $estados) {
            $pedido = Pedido::query()
                ->with('detalles')
                ->lockForUpdate()
                ->findOrFail($pedido->id);

            if ($pedido->estado_pedido === 'cancelado') {
                return;
            }

            if (! in_array($pedido->estado_pedido, ['pendiente', 'pagado', 'preparando'], true)) {
                throw ValidationException::withMessages([
                    'pedido' => 'El pedido ya no puede cancelarse porque fue enviado.',
                ]);
            }

            $libroIds = $pedido->detalles->pluck('libro_id')->filter()->sort()->values();
            $libros = Libro::query()
                ->whereIn('id', $libroIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($pedido->detalles as $detalle) {
                $libro = $libros->get($detalle->libro_id);

                if (! $libro) {
                    continue;
                }

                $stockAnterior = $libro->stock;
                $stockNuevo = $stockAnterior + $detalle->cantidad;

                $libro->update([
                    'stock' => $stockNuevo,
                    'ventas' => max(0, $libro->ventas - $detalle->cantidad),
                ]);

                MovimientoInventario::query()->create([
                    'libro_id' => $libro->id,
                    'user_id' => $request->user()->id,
                    'tipo' => 'devolucion',
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'motivo' => "Cancelacion del pedido #{$pedido->id}",
                ]);
            }

            $pedido->update([
                'estado_pago' => $pedido->estado_pago === 'pagado' ? 'reembolsado' : $pedido->estado_pago,
            ]);

            $estados->cambiar(
                $pedido,
                'cancelado',
                $request->user(),
                'Pedido cancelado por el cliente. Stock restaurado.',
            );
        }, 3);

        return back()->with('status', 'Pedido cancelado y stock restaurado.');
    }

    private function autorizar(Request $request, Pedido $pedido): void
    {
        abort_unless($pedido->user_id === $request->user()->id, 403);
    }
}
