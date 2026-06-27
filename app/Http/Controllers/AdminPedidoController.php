<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Repartidor;
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
                ->with(['usuario', 'repartidor'])
                ->withCount('detalles')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load([
            'usuario',
            'repartidor',
            'detalles',
            'transaccionesPago',
            'historialEstados.usuario',
        ]);

        $ciudadPedido = $this->extraerCiudad($pedido->direccion);

        $repartidores = Repartidor::where('activo', true)
            ->orderByRaw("CASE WHEN ciudad = ? THEN 0 ELSE 1 END", [$ciudadPedido])
            ->orderBy('nombre_empresa')
            ->get();

        return view('admin.pedidos.show', compact('pedido', 'repartidores', 'ciudadPedido'));
    }

    private function extraerCiudad(?string $direccion): string
    {
        if (!$direccion) return '';

        $partes = array_map('trim', explode(',', $direccion));

        // Descarta el código postal si el último segmento de la dirección es numérico.
        if (count($partes) > 1 && is_numeric(end($partes))) {
            array_pop($partes);
        }

        return ucfirst(strtolower(trim(end($partes))));
    }

    public function updateStatus(
        Request $request,
        Pedido $pedido,
        PedidoEstadoService $estados,
    ): RedirectResponse {
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        $datosValidados = $request->validate([
            'estado' => ['required', Rule::in(['preparando', 'enviado', 'entregado'])],
            'observacion' => ['required', 'string', 'min:3', 'max:500'],
            'repartidor_id' => ['nullable', 'exists:repartidores,id'],
        ]);

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

        if ($siguienteEstado === 'enviado' && !empty($datosValidados['repartidor_id'])) {
            $pedido->update(['repartidor_id' => $datosValidados['repartidor_id']]);
        }

        $estados->cambiar(
            $pedido,
            $siguienteEstado,
            $request->user(),
            $datosValidados['observacion'],
        );

        return back()->with('status', 'Estado del pedido actualizado correctamente.');
    }
}