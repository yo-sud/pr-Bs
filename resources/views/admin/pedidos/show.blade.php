@extends('layouts.admin')

@section('title', 'Pedido #'.$pedido->id.' - Administración')

@section('contenido')
<a href="{{ route('admin.pedidos.index') }}" class="text-sm font-semibold text-[#B8500C]">&larr; Volver a pedidos</a>

<div class="flex flex-wrap justify-between gap-4 mt-4 mb-7">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Pedido #{{ $pedido->id }}</h2>
        <p class="text-sm text-gray-500">{{ $pedido->usuario?->name }} · {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="text-right text-sm">
        <p>Pago: <strong class="uppercase">{{ $pedido->estado_pago }}</strong></p>
        <p>Pedido: <strong class="uppercase">{{ $pedido->estado_pedido }}</strong></p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-6 items-start">
    <div class="space-y-6">
        <section class="bg-white rounded-xl border p-6">
            <h3 class="font-bold text-lg mb-4">Productos</h3>
            <div class="divide-y">
                @foreach ($pedido->detalles as $detalle)
                    <div class="py-3 flex justify-between gap-4 text-sm">
                        <span>{{ $detalle->cantidad }} x {{ $detalle->titulo }}</span>
                        <strong>S/ {{ number_format((float) $detalle->subtotal, 2) }}</strong>
                    </div>
                @endforeach
            </div>
            <div class="border-t mt-3 pt-4 flex justify-between font-bold">
                <span>Total</span><span>S/ {{ number_format((float) $pedido->total, 2) }}</span>
            </div>
        </section>

        <section class="bg-white rounded-xl border p-6">
            <h3 class="font-bold text-lg mb-4">Trazabilidad</h3>
            <div class="space-y-4">
                @forelse ($pedido->historialEstados->sortByDesc('created_at') as $cambio)
                    <div class="border-l-2 border-[#D4A373] pl-4">
                        <p class="text-sm font-semibold uppercase">{{ $cambio->estado_nuevo }}</p>
                        <p class="text-xs text-gray-500">{{ $cambio->created_at->format('d/m/Y H:i') }} · {{ $cambio->usuario?->name ?: 'Sistema' }}</p>
                        @if ($cambio->observacion)<p class="text-sm mt-1">{{ $cambio->observacion }}</p>@endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Sin cambios registrados.</p>
                @endforelse
            </div>
        </section>
    </div>

    <aside class="space-y-6">
        <section class="bg-white rounded-xl border p-6">
            <h3 class="font-bold text-lg mb-3">Direccion</h3>
            <p class="text-sm whitespace-pre-line">{{ $pedido->direccion }}</p>
        </section>

        <section class="bg-white rounded-xl border p-6">
            <h3 class="font-bold text-lg mb-4">Transacciones</h3>
            <div class="space-y-3">
                @forelse ($pedido->transaccionesPago->sortByDesc('created_at') as $transaccion)
                    <div class="rounded-lg bg-gray-50 p-3 text-xs">
                        <p class="font-bold uppercase">{{ $transaccion->estado }}</p>
                        <p>S/ {{ number_format((float) $transaccion->monto, 2) }} · {{ $transaccion->referencia }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Todavia no hay intentos de pago.</p>
                @endforelse
            </div>
        </section>

        @if ($pedido->estado_pago === 'pagado' && in_array($pedido->estado_pedido, ['pagado', 'preparando', 'enviado'], true))
            @php
                $siguienteEstado = match ($pedido->estado_pedido) {
                    'pagado' => 'preparando',
                    'preparando' => 'enviado',
                    'enviado' => 'entregado',
                };
            @endphp
            <form method="POST" action="{{ route('admin.pedidos.update-status', $pedido) }}" class="bg-white rounded-xl border p-6 space-y-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="{{ $siguienteEstado }}">
                <h3 class="font-bold text-lg">Actualizar despacho</h3>
                <p class="text-sm text-gray-600">
                    Siguiente estado: <strong class="uppercase">{{ $siguienteEstado }}</strong>
                </p>
                <textarea name="observacion" rows="3" required minlength="3" maxlength="500" placeholder="Describe la preparacion, envio o entrega" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                <button class="w-full rounded-lg bg-[#B8500C] text-white py-2.5 text-sm font-semibold">
                    Marcar como {{ $siguienteEstado }}
                </button>
            </form>
        @endif
    </aside>
</div>
@endsection
