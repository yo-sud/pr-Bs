<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\TransaccionPago;
use App\Services\PagoService; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; 
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

    public function store(Request $request, Pedido $pedido): RedirectResponse
    {
        $this->autorizar($request, $pedido);

        if ($pedido->estado_pago === 'pagado') {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'Este pedido ya se encuentra pagado.');
        }

        abort_if($pedido->estado_pedido === 'cancelado', 404);

        $referenciaUuid = (string) Str::uuid(); 

        $transaccion = TransaccionPago::query()->create([
            'pedido_id' => $pedido->id,
            'referencia' => $referenciaUuid,
            'monto' => $pedido->total,
            'moneda' => 'PEN',
            'estado' => 'pendiente',
        ]);

        $datosPago = [
            'items' => [
                [
                    'title' => 'Pedido #' . $pedido->id, 
                    'quantity' => 1,
                    'unit_price' => (float) $pedido->total,
                    'currency_id' => 'PEN'
                ]
            ],
            'external_reference' => $referenciaUuid, 
            
            // === EL TRUCO: URLs seguras temporales para que Mercado Pago no nos bloquee ===
            'back_urls' => [
                'success' => 'https://www.google.com', 
                'failure' => 'https://www.google.com',
                'pending' => 'https://www.google.com'
            ],
            'auto_return' => 'approved',
        ];

        $respuesta = Http::withoutVerifying()
            ->withToken(env('MERCADOPAGO_ACCESS_TOKEN'))
            ->post('https://api.mercadopago.com/checkout/preferences', $datosPago);

        if ($respuesta->successful()) {
            $preferencia = $respuesta->json();
            return redirect()->away($preferencia['init_point']); 
        }

        dd([
            'Mensaje' => 'Falló la conexión con Mercado Pago',
            'Codigo_Estado_HTTP' => $respuesta->status(),
            'Respuesta_API' => $respuesta->json(),
            'Token_Usado' => env('MERCADOPAGO_ACCESS_TOKEN') 
        ]);
    } 

    public function retorno(Request $request, PagoService $pagos): RedirectResponse
    {
        $estadoMercadoPago = $request->query('status'); 
        $referenciaUuid = $request->query('external_reference');

        $transaccion = TransaccionPago::where('referencia', $referenciaUuid)->firstOrFail();
        $pedido = clone $transaccion->pedido; 

        $estadoFinal = match ($estadoMercadoPago) {
            'approved' => 'aprobado',
            'rejected' => 'rechazado',
            default => 'pendiente',
        };

        $pagos->procesar([
            'evento_id' => (string) Str::uuid(),
            'referencia' => $referenciaUuid,
            'monto' => $transaccion->monto,
            'moneda' => 'PEN',
            'estado' => $estadoFinal,
        ]);

        $mensaje = match ($estadoFinal) {
            'aprobado' => '¡Pago aprobado correctamente!',
            'rechazado' => 'El pago fue rechazado. Puedes intentarlo nuevamente.',
            default => 'El pago quedó pendiente de confirmación.',
        };

        return redirect()->route('pedidos.show', $pedido)->with('status', $mensaje);
    }

    private function autorizar(Request $request, Pedido $pedido): void
    {
        abort_unless($pedido->user_id === $request->user()->id, 403);
    }
}