@extends('layouts.app')

@section('title', 'Pedido #'.$pedido->id.' - BookShop')

@section('content')
<main class="px-[4%] sm:px-[7%] py-8 sm:py-12 flex-grow bg-[#F9F6F3]">
    <div class="max-w-5xl mx-auto">
        {{-- Enlace de retorno --}}
        <a href="{{ route('pedidos.index') }}" class="inline-flex items-center gap-2 text-sm text-[#9C4309] hover:underline mb-4 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Mis pedidos
        </a>

        @if (session('status'))
            <div class="mt-2 mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 shadow-sm">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-2 mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 shadow-sm">{{ $errors->first() }}</div>
        @endif

        {{-- Cabecera del pedido responsiva --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#6E7E80]/10 pb-5 mb-6">
            <div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#421605]">Pedido #{{ $pedido->id }}</h1>
                <p class="text-xs sm:text-sm text-[#8A7A71] mt-1">
                    Confirmado el <span class="font-medium text-[#554138]">{{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</span>
                </p>
            </div>
            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-2 bg-[#F3ECE0]/40 sm:bg-transparent p-3 sm:p-0 rounded-xl">
                <span class="inline-flex rounded-full bg-[#EFE6DA] px-3.5 py-1.5 text-xs font-bold uppercase text-[#421605] tracking-wider">
                    {{ $pedido->estado_pedido }}
                </span>
                <p class="text-xs text-[#8A7A71]">
                    Pago: <span class="font-semibold uppercase text-[#9C4309]">{{ $pedido->estado_pago }}</span>
                </p>
            </div>
        </div>

        {{-- Redirección directa a Mercado Pago sin pantallas intermedias --}}
        @if ($pedido->estado_pago !== 'pagado' && $pedido->estado_pedido !== 'cancelado')
            <div class="mb-8 bg-[#FFF9F0] border border-[#B8500C]/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
                <div class="space-y-1">
                    <p class="font-serif font-bold text-[#421605] text-base">Pago {{ $pedido->estado_pago }}</p>
                    <p class="text-xs sm:text-sm text-[#8A7A71]">Serás redirigido de forma segura a Mercado Pago para completar tu compra.</p>
                </div>
                
                <form method="POST" action="{{ route('pagos.store', $pedido) }}" class="w-full sm:w-auto">
                    
                    <button type="submit" class="w-full sm:w-auto text-center bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-3 rounded-full text-sm font-semibold transition-colors shadow-sm whitespace-nowrap">
                        Pagar pedido
                    </button>
                </form>
            </div>
        @endif

        {{-- Contenedor principal de dos columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-8 items-start">
            
            {{-- SECCIÓN DE PRODUCTOS (IZQUIERDA) --}}
            <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h2 class="font-serif text-lg sm:text-xl font-bold text-[#421605] border-b border-[#421605]/5 pb-3 mb-4">Productos</h2>
                <div class="divide-y divide-[#421605]/10">
                    @foreach ($pedido->detalles as $detalle)
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-5">
                            <div class="min-w-0">
                                <p class="font-serif font-bold text-sm sm:text-base text-[#421605] truncate">{{ $detalle->titulo }}</p>
                                <p class="text-xs text-[#8A7A71] mt-0.5">ISBN: <span class="font-mono text-[#554138]">{{ $detalle->isbn ?: 'no registrado' }}</span></p>
                                <p class="text-xs text-[#8A7A71] mt-0.5">
                                    {{ $detalle->cantidad }} x <span class="text-[#9C4309] font-medium">S/ {{ number_format((float) $detalle->precio_unitario, 2) }}</span>
                                </p>
                            </div>
                            <div class="text-left sm:text-right font-serif text-base sm:text-lg font-bold text-[#421605] shrink-0 pt-1 sm:pt-0">
                                <span class="text-sm font-sans mr-0.5">S/</span>{{ number_format((float) $detalle->subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- COLUMNA DE DETALLES Y LOGS (DERECHA) --}}
            <aside class="space-y-5">
                {{-- Bloque Totales --}}
                <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <h2 class="font-serif text-base sm:text-lg font-bold text-[#421605] mb-4">Totales</h2>
                    <dl class="space-y-3.5 text-sm">
                        <div class="flex justify-between text-[#554138]">
                            <dt class="text-[#8A7A71]">Subtotal</dt>
                            <dd class="font-medium text-[#421605]">S/ {{ number_format((float) $pedido->subtotal, 2) }}</dd>
                        </div>
                        <div class="flex justify-between text-[#554138]">
                            <dt class="text-[#8A7A71]">Envío</dt>
                            <dd class="{{ (float) $pedido->envio === 0.0 ? 'text-emerald-600 font-bold tracking-wide' : 'font-medium text-[#421605]' }}">
                                {{ (float) $pedido->envio === 0.0 ? 'Gratis' : 'S/ '.number_format((float) $pedido->envio, 2) }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-baseline font-bold text-[#421605] border-t border-[#421605]/10 pt-3.5">
                            <dt class="text-base">Total</dt>
                            <dd class="font-serif text-xl sm:text-2xl text-[#9C4309] tracking-tight">
                                <span class="text-lg font-sans mr-0.5">S/</span>{{ number_format((float) $pedido->total, 2) }}
                            </dd>
                        </div>
                    </dl>
                </section>

                {{-- Bloque Dirección --}}
                <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <h2 class="font-serif text-base sm:text-lg font-bold text-[#421605] mb-2.5">Dirección de Entrega</h2>
                    <p class="text-xs sm:text-sm text-[#554138] whitespace-pre-line leading-relaxed">{{ $pedido->direccion }}</p>
                </section>

                {{-- Acción Cancelar Pedido --}}
                @if (in_array($pedido->estado_pedido, ['pendiente', 'pagado', 'preparando'], true))
                    <form method="POST" action="{{ route('pedidos.cancel', $pedido) }}">
                        
                        <button class="w-full border border-red-200 hover:border-red-300 text-red-700 hover:bg-red-50/50 bg-white rounded-full py-3 text-sm font-semibold transition-colors shadow-sm">
                            Cancelar pedido
                        </button>
                    </form>
                @endif

                {{-- Bloque Seguimiento / Timeline --}}
                @if ($pedido->historialEstados->isNotEmpty())
                    <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                        <h2 class="font-serif text-base sm:text-lg font-bold text-[#421605] mb-4">Seguimiento</h2>
                        <div class="space-y-5 relative before:absolute before:top-1 before:bottom-1 before:left-[7px] before:w-0.5 before:bg-[#F3ECE0]">
                            @foreach ($pedido->historialEstados->sortByDesc('created_at') as $cambio)
                                <div class="relative pl-6">
                                    {{-- Nodo decorativo cálido --}}
                                    <div class="absolute top-1.5 left-0 w-3 h-3 rounded-full bg-[#B8500C] ring-4 ring-[#FFF9F0]"></div>
                                    
                                    <p class="text-xs font-bold uppercase text-[#421605] tracking-wide">{{ $cambio->estado_nuevo }}</p>
                                    <p class="text-[10px] text-[#8A7A71] mt-0.5">{{ $cambio->created_at->format('d/m/Y H:i \h\r\s') }}</p>
                                    @if ($cambio->observacion)
                                        <p class="text-xs mt-1.5 text-[#554138] bg-[#FDFBF7] border border-[#421605]/5 p-2 rounded-xl italic">
                                            "{{ $cambio->observacion }}"
                                        </p>
                                    @endif
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