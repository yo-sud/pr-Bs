@extends('layouts.app')

@section('title', 'Pedido #'.$pedido->id.' - BookShop')

@section('content')
<main class="px-[7%] py-12 flex-grow">
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('pedidos.index') }}" class="text-sm text-[#B8500C]">&larr; Mis pedidos</a>

        @if (session('status'))
            <div class="mt-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="flex flex-wrap items-start justify-between gap-5 mt-5 mb-8">
            <div>
                <h1 class="font-serif text-3xl font-bold">Pedido #{{ $pedido->id }}</h1>
                <p class="text-sm text-[#8A7A71] mt-2">Confirmado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex rounded-full bg-[#F3ECE0] px-4 py-2 text-xs font-bold uppercase">{{ $pedido->estado_pedido }}</span>
                <p class="text-xs text-[#8A7A71] mt-2">Pago: {{ $pedido->estado_pago }}</p>
            </div>
        </div>

        @if ($pedido->estado_pago !== 'pagado' && $pedido->estado_pedido !== 'cancelado')
            <div class="mb-8 bg-[#FFF7E8] border border-[#F09200]/30 rounded-2xl p-5 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="font-bold">Pago {{ $pedido->estado_pago }}</p>
                    <p class="text-sm text-[#8A7A71]">Usa la pasarela simulada para continuar con el despacho.</p>
                </div>
                <a href="{{ route('pagos.create', $pedido) }}" class="bg-[#B8500C] text-white px-6 py-3 rounded-full text-sm font-semibold">Pagar pedido</a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">
            <section class="bg-white border border-[#421605]/10 rounded-2xl p-6">
                <h2 class="font-serif text-xl font-bold mb-5">Productos</h2>
                <div class="divide-y divide-[#421605]/10">
                    @foreach ($pedido->detalles as $detalle)
                        <div class="py-4 first:pt-0 last:pb-0 flex justify-between gap-5">
                            <div>
                                <p class="font-semibold">{{ $detalle->titulo }}</p>
                                <p class="text-xs text-[#8A7A71] mt-1">ISBN {{ $detalle->isbn ?: 'no registrado' }}</p>
                                <p class="text-xs text-[#8A7A71]">{{ $detalle->cantidad }} x S/ {{ number_format((float) $detalle->precio_unitario, 2) }}</p>
                            </div>
                            <span class="font-semibold">S/ {{ number_format((float) $detalle->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="space-y-5">
                <section class="bg-white border border-[#421605]/10 rounded-2xl p-6">
                    <h2 class="font-serif text-lg font-bold mb-4">Totales</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between"><dt>Subtotal</dt><dd>S/ {{ number_format((float) $pedido->subtotal, 2) }}</dd></div>
                        <div class="flex justify-between"><dt>Envio</dt><dd>{{ (float) $pedido->envio === 0.0 ? 'Gratis' : 'S/ '.number_format((float) $pedido->envio, 2) }}</dd></div>
                        <div class="flex justify-between font-bold text-base border-t border-[#421605]/10 pt-3"><dt>Total</dt><dd>S/ {{ number_format((float) $pedido->total, 2) }}</dd></div>
                    </dl>
                </section>

                <section class="bg-white border border-[#421605]/10 rounded-2xl p-6">
                    <h2 class="font-serif text-lg font-bold mb-3">Direccion</h2>
                    <p class="text-sm text-[#554138] whitespace-pre-line">{{ $pedido->direccion }}</p>
                </section>

                @if (in_array($pedido->estado_pedido, ['pendiente', 'pagado', 'preparando'], true))
                    <form method="POST" action="{{ route('pedidos.cancel', $pedido) }}">
                        @csrf
                        <button class="w-full border border-red-300 text-red-700 hover:bg-red-50 rounded-full py-3 text-sm font-semibold">
                            Cancelar pedido
                        </button>
                    </form>
                @endif

                @if ($pedido->historialEstados->isNotEmpty())
                    <section class="bg-white border border-[#421605]/10 rounded-2xl p-6">
                        <h2 class="font-serif text-lg font-bold mb-4">Seguimiento</h2>
                        <div class="space-y-4">
                            @foreach ($pedido->historialEstados->sortByDesc('created_at') as $cambio)
                                <div class="border-l-2 border-[#B8500C] pl-3">
                                    <p class="text-xs font-bold uppercase">{{ $cambio->estado_nuevo }}</p>
                                    <p class="text-[11px] text-[#8A7A71]">{{ $cambio->created_at->format('d/m/Y H:i') }}</p>
                                    @if ($cambio->observacion)<p class="text-xs mt-1">{{ $cambio->observacion }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </aside>
        </div>
    </div>
</main>
@endsection
