<?php

namespace App\Http\Controllers;

use App\Services\PagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PagoWebhookController extends Controller
{
    public function __invoke(Request $request, PagoService $pagos): JsonResponse
    {
        $firma = (string) $request->header('X-BookShop-Signature');
        $esperada = hash_hmac('sha256', $request->getContent(), (string) config('services.fake_payment.webhook_secret'));

        if ($firma === '' || ! hash_equals($esperada, $firma)) {
            throw new HttpException(401, 'Firma de webhook invalida.');
        }

        $datos = $request->validate([
            'evento_id' => ['required', 'uuid'],
            'referencia' => ['required', 'uuid'],
            'monto' => ['required', 'numeric', 'min:0'],
            'moneda' => ['required', 'in:PEN'],
            'estado' => ['required', 'in:aprobado,rechazado,pendiente'],
        ]);

        $transaccion = $pagos->procesar($datos);

        return response()->json([
            'procesado' => true,
            'transaccion_id' => $transaccion->id,
            'estado' => $transaccion->fresh()->estado,
        ]);
    }
}
