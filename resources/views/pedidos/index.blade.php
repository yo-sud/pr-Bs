@extends('layouts.app')

@section('title', 'Mis pedidos - BookShop')

@section('content')
<main class="px-[4%] sm:px-[7%] py-8 sm:py-12 flex-grow" style="background-color: #F9F6F3;">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#421605] mb-6 sm:mb-8">Mis pedidos</h1>

        @if ($pedidos->isEmpty())
            <div class="bg-white border border-[#421605]/10 rounded-2xl p-8 sm:p-12 text-center shadow-sm">
                <p class="text-[#8A7A71] text-sm sm:text-base mb-6">Todavía no has realizado ninguna compra.</p>
                <a href="{{ route('libros.index') }}" class="inline-flex bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-3 rounded-full text-sm font-semibold transition-colors shadow-sm">
                    Explorar libros
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pedidos as $pedido)
                    @php
                        $estado = strtolower($pedido->estado_pedido);
                        $badgeClass = match($estado) {
                            'pagado'     => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                            'pendiente'  => 'bg-amber-50 text-amber-700 border border-amber-200',
                            'preparando' => 'bg-blue-50 text-blue-700 border border-blue-200',
                            'enviado'    => 'bg-purple-50 text-purple-700 border border-purple-200',
                            'entregado'  => 'bg-teal-50 text-teal-700 border border-teal-200',
                            'cancelado'  => 'bg-red-50 text-red-600 border border-red-200',
                            default      => 'bg-[#F3ECE0] text-[#421605] border border-[#421605]/10',
                        };
                        $titulos = $pedido->detalles->map(fn($d) => $d->libro?->titulo)->filter()->values();
                    @endphp

                    <a href="{{ route('pedidos.show', $pedido) }}"
                       class="block bg-white border border-[#421605]/10 rounded-2xl p-4 sm:p-5 hover:border-[#B8500C]/30 transition-all duration-200 shadow-sm hover:shadow-md group">

                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 sm:gap-6">

                            {{-- Bloque Izquierdo --}}
                            <div class="min-w-0 flex-1">
                                {{-- Número de pedido --}}
                                <div class="flex items-baseline gap-2">
                                    <span class="font-serif text-base sm:text-lg font-bold text-[#421605] group-hover:text-[#B8500C] transition-colors">Pedido</span>
                                    <span class="font-mono font-bold text-[#B8500C] text-base sm:text-lg tracking-tight">#{{ $pedido->id }}</span>
                                </div>

                                {{-- Fecha --}}
                                <p class="text-xs text-[#8A7A71] mt-1">
                                    {{ $pedido->created_at->format('d/m/Y') }}
                                    <span class="font-mono text-[#6E7E80]">{{ $pedido->created_at->format('H:i') }}</span>
                                </p>

                                {{-- Títulos de libros --}}
                                @if($titulos->isNotEmpty())
                                    <p class="text-xs text-[#554138] mt-2 truncate">
                                        {{ $titulos->join(', ') }}
                                        @if($pedido->detalles_count > 2)
                                            <span class="text-[#8A7A71]"> +{{ $pedido->detalles_count - 2 }} más</span>
                                        @endif
                                    </p>
                                @endif

                                {{-- Cantidad de productos --}}
                                <p class="text-[11px] text-[#8A7A71] mt-1.5 font-medium">
                                    {{ $pedido->detalles_count }} {{ $pedido->detalles_count === 1 ? 'producto' : 'productos' }}
                                </p>
                            </div>

                            {{-- Bloque Derecho: Estado y Total --}}
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-3 pt-3 sm:pt-0 border-t border-[#421605]/5 sm:border-0 flex-shrink-0">
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide {{ $badgeClass }}">
                                    {{ $pedido->estado_pedido }}
                                </span>
                                <div class="text-right">
                                    <span class="text-[10px] font-sans text-[#8A7A71] uppercase tracking-widest block">Total</span>
                                    <p class="font-sans font-black tabular-nums text-lg sm:text-xl text-[#9C4309] leading-tight">
                                        <span class="text-sm font-semibold mr-0.5">S/</span>{{ number_format((float) $pedido->total, 2) }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>
            
            {{-- Paginación estilizada --}}
            <div class="mt-8 warm-pagination">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
</main>
@endsection