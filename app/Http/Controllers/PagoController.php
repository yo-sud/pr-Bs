<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\TransaccionPago;
use App\Services\PagoService; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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

    public function store(Request $request, Pedido $pedido): RedirectResponse|View
    {
        $this->autorizar($request, $pedido);

        if ($pedido->estado_pago === 'pagado') {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'Este pedido ya se encuentra pagado.');
        }

        abort_if($pedido->estado_pedido === 'cancelado', 404);

        // Cancela transacciones previas pendientes para evitar ambigüedad al verificar el pago.
        TransaccionPago::where('pedido_id', $pedido->id)
            ->where('estado', 'pendiente')
            ->update(['estado' => 'cancelado']);

        $referenciaUuid = (string) Str::uuid();

        $transaccion = TransaccionPago::query()->create([
            'pedido_id' => $pedido->id,
            'referencia' => $referenciaUuid,
            'monto' => $pedido->total,
            'moneda' => 'PEN',
            'estado' => 'pendiente',
        ]);

        session(['pago_ref_' . $pedido->id => $referenciaUuid]);

        $retornoUrl = app()->environment('local')
            ? str_replace('127.0.0.1', 'localhost', route('pago.retorno'))
            : route('pago.retorno');

        $datosPago = [
            'items' => [
                [
                    'title'      => 'Pedido #' . $pedido->id,
                    'quantity'   => 1,
                    'unit_price' => (float) $pedido->total,
                    'currency_id'=> 'PEN',
                ]
            ],
            'external_reference' => $referenciaUuid,
            'back_urls' => [
                'success' => $retornoUrl,
                'failure' => $retornoUrl,
                'pending' => $retornoUrl,
            ],
        ];

        $http = Http::withToken(config('services.mercadopago.access_token'));

        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $respuesta = $http->post('https://api.mercadopago.com/checkout/preferences', $datosPago);

        if ($respuesta->successful()) {
            $preferencia = $respuesta->json();
            return view('pagos.redirect', [
                'pedido'     => $pedido,
                'mpUrl'      => $preferencia['init_point'],
                'referencia' => $referenciaUuid,
            ]);
        }

        return redirect()->route('pedidos.show', $pedido)
            ->with('status', 'Hubo un problema al conectar con MercadoPago. Por favor intenta nuevamente.');
    } 

    public function retorno(Request $request, PagoService $pagos): RedirectResponse
    {
        $estadoMercadoPago = $request->query('status'); 
        $referenciaUuid = $request->query('external_reference');

        $transaccion = TransaccionPago::where('referencia', $referenciaUuid)->firstOrFail();
        $pedido = clone $transaccion->pedido;

        if (!Auth::check()) {
            $usuario = \App\Models\User::find($pedido->user_id);
            if ($usuario) {
                Auth::login($usuario);
                $request->session()->regenerate();
            }
        }

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

        if ($estadoFinal === 'aprobado') {
            return redirect()->route('libros.index')
                ->with('status', '¡Pago aprobado! Tu pedido está confirmado.');
        }

        $mensaje = match ($estadoFinal) {
            'rechazado' => 'El pago fue rechazado. Puedes intentarlo nuevamente.',
            default     => 'El pago quedó pendiente de confirmación.',
        };

        return redirect()->route('pedidos.show', $pedido)->with('status', $mensaje);
    }

    public function verificar(Request $request, Pedido $pedido, PagoService $pagos): RedirectResponse
    {
        $this->autorizar($request, $pedido);

        // Detecta el caso en que retorno() ya procesó el pago en otra pestaña.
        if ($pedido->estado_pago === 'pagado') {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', '¡Tu pago ya fue confirmado! Tu pedido está en proceso.');
        }

        // Localiza la transacción por referencia de sesión o la última pendiente.
        $referencia = $request->input('referencia') ?? session('pago_ref_' . $pedido->id);

        $transaccion = $referencia
            ? TransaccionPago::where('pedido_id', $pedido->id)
                ->where('referencia', $referencia)
                ->where('estado', 'pendiente')
                ->first()
            : null;

        if (!$transaccion) {
            $transaccion = TransaccionPago::where('pedido_id', $pedido->id)
                ->where('estado', 'pendiente')
                ->latest()
                ->first();
        }

        if (!$transaccion) {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'No se encontró un pago pendiente para este pedido.');
        }

        $http = Http::withToken(config('services.mercadopago.access_token'));
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $respuesta = $http->get('https://api.mercadopago.com/v1/payments/search', [
            'external_reference' => $transaccion->referencia,
        ]);

        if (!$respuesta->successful()) {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'No se pudo verificar el pago. Intenta nuevamente.');
        }

        $resultados = $respuesta->json('results', []);

        if (empty($resultados)) {
            return redirect()->route('pedidos.show', $pedido)
                ->with('status', 'Aún no se registró el pago. Espera unos segundos e intenta de nuevo.');
        }

        $estadoMP = $resultados[0]['status'] ?? 'pending';

        $estadoFinal = match ($estadoMP) {
            'approved' => 'aprobado',
            'rejected' => 'rechazado',
            default    => 'pendiente',
        };

        $pagos->procesar([
            'evento_id' => (string) Str::uuid(),
            'referencia' => $transaccion->referencia,
            'monto'      => $transaccion->monto,
            'moneda'     => 'PEN',
            'estado'     => $estadoFinal,
        ]);

        if ($estadoFinal === 'aprobado') {
            return redirect()->route('libros.index')
                ->with('status', '¡Pago aprobado! Tu pedido está confirmado.');
        }

        $mensaje = match ($estadoFinal) {
            'rechazado' => 'El pago fue rechazado. Puedes intentarlo nuevamente.',
            default     => 'El pago aún no fue confirmado. Intenta verificar más tarde.',
        };

        return redirect()->route('pedidos.show', $pedido)->with('status', $mensaje);
    }

    private function autorizar(Request $request, Pedido $pedido): void
    {
        abort_unless($pedido->user_id === $request->user()->id, 403);
    }
}