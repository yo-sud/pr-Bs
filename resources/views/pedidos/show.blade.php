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

        {{-- Pasarela de pago simulada adaptada a móviles --}}
        @if ($pedido->estado_pago !== 'pagado' && $pedido->estado_pedido !== 'cancelado')
            <div class="mb-8 bg-[#FFF9F0] border border-[#B8500C]/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
                <div class="space-y-1">
                    <p class="font-serif font-bold text-[#421605] text-base">Pago {{ $pedido->estado_pago }}</p>
                    <p class="text-xs sm:text-sm text-[#8A7A71]">Serás redirigido a MercadoPago para completar tu compra.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <form method="POST" action="{{ route('pagos.store', $pedido) }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto text-center bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-3 rounded-full text-sm font-semibold transition-colors shadow-sm whitespace-nowrap">
                            Pagar pedido
                        </button>
                    </form>
                    <form method="POST" action="{{ route('pagos.verificar', $pedido) }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto text-center bg-white border border-[#B8500C] text-[#B8500C] hover:bg-[#FFF0E5] px-6 py-3 rounded-full text-sm font-semibold transition-colors shadow-sm whitespace-nowrap">
                            Ya pagué · Verificar
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Contenedor principal de dos columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-8 items-start">
            
            {{-- SECCIÓN DE PRODUCTOS (IZQUIERDA) --}}
            <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h2 class="font-serif text-lg sm:text-xl font-bold text-[#421605] border-b border-[#421605]/5 pb-3 mb-4">Productos</h2>
                <div class="divide-y divide-[#421605]/10">
                    @foreach ($pedido->detalles as $detalle)
                        <div class="py-4 first:pt-0 last:pb-0 flex gap-4 items-start">
                            {{-- Portada --}}
                            <div class="shrink-0 w-14 h-20 rounded-lg overflow-hidden border border-[#421605]/10 bg-[#F3ECE0]">
                                @if ($detalle->libro)
                                    <img src="{{ $detalle->libro->portada_url }}" alt="{{ $detalle->titulo }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#B8500C]/40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <p class="font-serif font-bold text-sm sm:text-base text-[#421605] leading-snug">{{ $detalle->titulo }}</p>
                                <p class="text-xs text-[#8A7A71] mt-0.5">ISBN: <span class="font-mono text-[#554138]">{{ $detalle->isbn ?: 'no registrado' }}</span></p>
                                <p class="text-xs text-[#8A7A71] mt-0.5">
                                    {{ $detalle->cantidad }} x <span class="text-[#9C4309] font-medium">S/ {{ number_format((float) $detalle->precio_unitario, 2) }}</span>
                                </p>
                            </div>
                            {{-- Subtotal --}}
                            <div class="text-right font-sans text-base sm:text-lg font-bold text-[#421605] shrink-0">
                                <span class="text-sm font-sans mr-0.5">S/</span>{{ number_format((float) $detalle->subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- COLUMNA DE DETALLES Y LOGS (DERECHA) --}}
            <aside class="space-y-5">

                {{-- Bloque Datos del Cliente --}}
                <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <h2 class="font-serif text-base sm:text-lg font-bold text-[#421605] mb-4">Datos del cliente</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9C4309] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <div>
                                <dt class="text-[#8A7A71] text-xs uppercase tracking-wide mb-0.5">Nombre</dt>
                                <dd class="font-medium text-[#421605]">{{ $pedido->usuario->name }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9C4309] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <div>
                                <dt class="text-[#8A7A71] text-xs uppercase tracking-wide mb-0.5">Correo</dt>
                                <dd class="font-medium text-[#421605] break-all">{{ $pedido->usuario->email }}</dd>
                            </div>
                        </div>
                        @if ($pedido->usuario->phone)
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9C4309] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                                <div>
                                    <dt class="text-[#8A7A71] text-xs uppercase tracking-wide mb-0.5">Teléfono</dt>
                                    <dd class="font-medium text-[#421605]">{{ $pedido->usuario->phone }}</dd>
                                </div>
                            </div>
                        @endif
                        @if ($pedido->pagado_at)
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9C4309] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <div>
                                    <dt class="text-[#8A7A71] text-xs uppercase tracking-wide mb-0.5">Pagado el</dt>
                                    <dd class="font-medium text-emerald-700">{{ $pedido->pagado_at->format('d/m/Y \a \l\a\s H:i') }}</dd>
                                </div>
                            </div>
                        @endif
                        @php $ultimaTransaccion = $pedido->transaccionesPago->where('estado', 'pagado')->first() ?? $pedido->transaccionesPago->last(); @endphp
                        @if ($ultimaTransaccion)
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#9C4309] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                </svg>
                                <div>
                                    <dt class="text-[#8A7A71] text-xs uppercase tracking-wide mb-0.5">Referencia de pago</dt>
                                    <dd class="font-mono text-xs text-[#554138] break-all">{{ $ultimaTransaccion->referencia }}</dd>
                                </div>
                            </div>
                        @endif
                    </dl>
                </section>

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
                            {{-- MODIFICADO: Cambiado font-serif por font-sans para unificar el estilo de la cifra --}}
                            <dd class="font-sans text-xl sm:text-2xl text-[#9C4309] tracking-tight">
                                <span class="text-lg font-sans mr-0.5">S/</span>{{ number_format((float) $pedido->total, 2) }}
                            </dd>
                        </div>
                    </dl>
                </section>

                {{-- Bloque Entrega: empresa + dirección en una sola tarjeta --}}
                <section class="bg-white border border-[#421605]/10 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                    <h2 class="font-serif text-base sm:text-lg font-bold text-[#421605]">Entrega</h2>

                    {{-- Empresa de reparto --}}
                    @if ($pedido->repartidor)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#FFF0E0] flex items-center justify-center shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B8500C]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-[#8A7A71] uppercase tracking-wide mb-0.5">Empresa de reparto</p>
                                <p class="font-semibold text-sm text-[#421605]">{{ $pedido->repartidor->nombre_empresa }}</p>
                                @if ($pedido->repartidor->tiempo_entrega_estimado)
                                    <p class="text-xs text-[#8A7A71] mt-0.5">Entrega estimada: <span class="font-medium text-[#554138]">{{ $pedido->repartidor->tiempo_entrega_estimado }}</span></p>
                                @endif
                                @if ($pedido->repartidor->telefono)
                                    <p class="text-xs text-[#8A7A71]">Tel: <span class="font-medium text-[#554138]">{{ $pedido->repartidor->telefono }}</span></p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-[#8A7A71]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B8500C]/40 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                            <p class="text-xs">La empresa de reparto se asignará al confirmar tu pago.</p>
                        </div>
                    @endif

                    <div class="border-t border-[#421605]/8"></div>

                    {{-- Dirección --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#FFF0E0] flex items-center justify-center shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#B8500C]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#8A7A71] uppercase tracking-wide mb-0.5">Dirección de entrega</p>
                            <p class="text-xs sm:text-sm text-[#554138] leading-relaxed">{{ $pedido->direccion }}</p>
                        </div>
                    </div>
                </section>

                {{-- Acción Cancelar Pedido --}}
                @if (in_array($pedido->estado_pedido, ['pendiente', 'pagado', 'preparando'], true))
                    <form method="POST" action="{{ route('pedidos.cancel', $pedido) }}">
                        @csrf
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