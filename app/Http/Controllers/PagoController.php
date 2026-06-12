<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\TransaccionPago;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function create(Request $request, Pedido $pedido): View|RedirectResponse
    {
        $this->autorizar($request, $pedido);

        if ($pedido->estado_pago === 'pagado') {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'Este pedido ya se encuentra pagado.');
        }

        abort_if($pedido->estado_pedido === 'cancelado', 404);

        return view('pagos.create', [
            'pedido' => $pedido,
        ]);
    }

    /**
     * Reemplaza e integra por completo la lógica de ProcesarPagoRequest.php
     */
    public function store(Request $request, Pedido $pedido, PagoService $pagos): RedirectResponse
    {
        $this->autorizar($request, $pedido);

        if ($pedido->estado_pago === 'pagado') {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'Este pedido ya se encuentra pagado.');
        }

        abort_if($pedido->estado_pedido === 'cancelado', 404);

        // Validación integrada del estado externo del pago
        $datosValidados = $request->validate([
            'resultado' => ['required', Rule::in(PagoService::ESTADOS_EXTERNOS)],
        ]);

        $transaccion = TransaccionPago::query()->create([
            'pedido_id' => $pedido->id,
            'referencia' => (string) Str::uuid(),
            'monto' => $pedido->total,
            'moneda' => 'PEN',
            'estado' => 'pendiente',
        ]);

        $pagos->procesar([
            'evento_id' => (string) Str::uuid(),
            'referencia' => $transaccion->referencia,
            'monto' => $pedido->total,
            'moneda' => 'PEN',
            'estado' => $datosValidados['resultado'],
        ]);

        $mensaje = match ($datosValidados['resultado']) {
            'aprobado' => 'Pago aprobado correctamente.',
            'rechazado' => 'El pago fue rechazado. Puedes intentarlo nuevamente.',
            default => 'El pago quedo pendiente de confirmacion.',
        };

        return redirect()->route('pedidos.show', $pedido)->with('status', $mensaje);
    }

    private function autorizar(Request $request, Pedido $pedido): void
    {
        abort_unless($pedido->user_id === $request->user()->id, 403);
    }
}