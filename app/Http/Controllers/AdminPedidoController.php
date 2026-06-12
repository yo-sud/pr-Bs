<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\PedidoEstadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPedidoController extends Controller
{
    public function index(): View
    {
        return view('admin.pedidos.index', [
            'pedidos' => Pedido::query()
                ->with('usuario')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(Pedido $pedido): View
    {
        return view('admin.pedidos.show', [
            'pedido' => $pedido->load([
                'usuario',
                'detalles',
                'transaccionesPago',
                'historialEstados.usuario',
            ]),
        ]);
    }

    /**
     * Reemplaza e integra la lógica de ActualizarPedidoEstadoRequest.php
     */
    public function updateStatus(
        Request $request,
        Pedido $pedido,
        PedidoEstadoService $estados,
    ): RedirectResponse {
        // 1. Autorización integrada (Solo administradores)
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validación integrada
        $datosValidados = $request->validate([
            'estado' => ['required', Rule::in(['preparando', 'enviado', 'entregado'])],
            'observacion' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        // 3. Reglas de negocio existentes
        if ($pedido->estado_pago !== 'pagado') {
            throw ValidationException::withMessages([
                'pedido' => 'Solo se puede despachar un pedido con pago aprobado.',
            ]);
        }

        $siguienteEstado = match ($pedido->estado_pedido) {
            'pagado' => 'preparando',
            'preparando' => 'enviado',
            'enviado' => 'entregado',
            default => null,
        };

        if ($siguienteEstado === null || $datosValidados['estado'] !== $siguienteEstado) {
            throw ValidationException::withMessages([
                'estado' => $siguienteEstado
                    ? "La siguiente accion permitida es marcar el pedido como {$siguienteEstado}."
                    : 'El pedido ya no admite cambios de despacho.',
            ]);
        }

        // 4. Ejecución del cambio de estado usando los datos validados internamente
        $estados->cambiar(
            $pedido,
            $siguienteEstado,
            $request->user(),
            $datosValidados['observacion'],
        );

        return back()->with('status', 'Estado del pedido actualizado correctamente.');
    }
}