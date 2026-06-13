@extends('layouts.app')

@section('title', 'Mis pedidos - BookShop')

@section('content')
<main class="px-[4%] sm:px-[7%] py-8 sm:py-12 flex-grow bg-[#F9F6F3]">
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
                    <a href="{{ route('pedidos.show', $pedido) }}" class="block bg-white border border-[#421605]/10 rounded-2xl p-4 sm:p-5 hover:border-[#B8500C]/40 transition-all duration-200 shadow-sm hover:shadow-md group">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                            
                            {{-- Bloque Izquierdo: Información general --}}
                            <div class="min-w-0">
                                <p class="font-serif text-base sm:text-lg font-bold text-[#421605] group-hover:text-[#B8500C] transition-colors">
                                    Pedido #{{ $pedido->id }}
                                </p>
                                <p class="text-xs text-[#8A7A71] mt-1 flex flex-wrap items-center gap-1.5">
                                    <span>{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                                    <span class="text-[#6E7E80]/30">•</span>
                                    <span class="font-medium text-[#554138]">{{ $pedido->detalles_count }} {{ $pedido->detalles_count === 1 ? 'producto' : 'productos' }}</span>
                                </p>
                            </div>
                            
                            {{-- Bloque Derecho: Estado y Monto --}}
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-start gap-2 pt-2 sm:pt-0 border-t border-[#421605]/5 sm:border-0">
                                <span class="inline-flex rounded-full bg-[#F3ECE0] px-3 py-1 text-[11px] font-bold uppercase text-[#421605] tracking-wide">
                                    {{ $pedido->estado_pedido }}
                                </span>
                                <p class="font-serif text-base sm:text-lg font-bold text-[#9C4309] tracking-tight">
                                    <span class="text-sm font-sans mr-0.5">S/</span>{{ number_format((float) $pedido->total, 2) }}
                                </p>
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