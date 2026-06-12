@extends('layouts.app')

@section('title', 'BookShop - Carrito de Compras')

@section('content')
<main class="px-[7%] py-12 flex-grow bg-[#F9F6F3]">
    {{-- Título y "Seguir Comprando" basado en el diseño --}}
    <div class="max-w-6xl mx-auto mb-8">
        <a href="{{ route('libros.index') }}" class="inline-flex items-center gap-2 text-sm text-[#9C4309] hover:underline mb-3 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Seguir comprando
        </a>
        
        <div class="flex flex-wrap items-baseline justify-between gap-4">
            <div>
                <h1 class="font-serif text-4xl text-[#421605]">Mi Carrito</h1>
                <p class="text-sm text-[#8A7A71] mt-1">
                    {{ $items->count() }} {{ $items->count() === 1 ? 'libro' : 'libros' }} en tu carrito
                </p>
            </div>
            @if ($items->isNotEmpty())
                <form method="POST" action="{{ route('carrito.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">Vaciar carrito</button>
                </form>
            @endif
        </div>
    </div>

    @if (session('status'))
        <div class="max-w-6xl mx-auto mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="max-w-6xl mx-auto mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="max-w-6xl mx-auto">
        @if ($items->isEmpty())
            <section class="bg-white border border-[#421605]/10 rounded-3xl p-10 md:p-16 text-center">
                <h2 class="font-serif text-2xl font-bold text-[#421605] mb-3">Tu carrito está vacío</h2>
                <p class="text-sm text-[#8A7A71] mb-7">Explora el catálogo y agrega los libros que quieras comprar.</p>
                <a href="{{ route('libros.index') }}" class="inline-flex bg-[#B8500C] hover:bg-[#963F07] text-white px-7 py-3 rounded-full text-sm font-semibold transition-colors">
                    Explorar libros
                </a>
            </section>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
                
                {{-- SECCIÓN DE ITEMS (IZQUIERDA) --}}
                <div class="flex flex-col gap-4">
                    @foreach ($items as $item)
                        @php($libro = $item['libro'])
                        <article class="bg-white p-6 rounded-2xl border border-[#421605]/10 flex gap-5 items-center justify-between shadow-sm relative group">
                            
                            <div class="flex gap-4 items-center">
                                {{-- Portada --}}
                                <a href="{{ route('libros.show', $libro) }}" class="w-16 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-[#421605]/10">
                                    <img src="{{ $libro->portada_url }}" alt="Portada de {{ $libro->titulo }}" class="w-full h-full object-cover">
                                </a>
                                
                                {{-- Información del Libro --}}
                                <div class="min-w-0">
                                    <a href="{{ route('libros.show', $libro) }}" class="font-serif text-base font-bold text-[#421605] hover:text-[#B8500C] block truncate">{{ $libro->titulo }}</a>
                                    <p class="text-sm text-[#8A7A71] mt-0.5">{{ $libro->autor }}</p>
                                    
                                    {{-- Controles de cantidad integrados --}}
                                    <div class="flex items-center gap-3 mt-3">
                                        <div class="flex items-center bg-[#F4F1ED] rounded-full px-2 py-1 gap-3 border border-[#421605]/5">
                                            {{-- Botón de bajar cantidad --}}
                                            <form method="POST" action="{{ route('carrito.update', $libro) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="cantidad" value="{{ max(1, $item['cantidad'] - 1) }}">
                                                <button type="submit" class="w-6 h-6 flex items-center justify-center text-[#554138] hover:bg-[#EAE6E1] rounded-full text-lg font-medium" {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>-</button>
                                            </form>
                                            
                                            <span class="text-sm font-semibold text-[#421605] w-4 text-center select-none">{{ $item['cantidad'] }}</span>
                                            
                                            {{-- Botón de subir cantidad --}}
                                            <form method="POST" action="{{ route('carrito.update', $libro) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="cantidad" value="{{ min($libro->stock, $item['cantidad'] + 1) }}">
                                                <button type="submit" class="w-6 h-6 flex items-center justify-center text-[#554138] hover:bg-[#EAE6E1] rounded-full text-lg font-medium" {{ $item['cantidad'] >= $libro->stock ? 'disabled' : '' }}>+</button>
                                            </form>
                                        </div>

                                        {{-- Botón Eliminar --}}
                                        <form method="POST" action="{{ route('carrito.destroy', $libro) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-[#8A7A71] hover:text-red-600 p-1.5 rounded-lg transition-colors ml-1" aria-label="Eliminar {{ $libro->titulo }}">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    @if ($item['cantidad'] > $libro->stock)
                                        <p class="text-xs text-red-600 mt-1">Solo quedan {{ $libro->stock }} unidades.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Precio por Ítem con tipografía Serif y Marrón Claro --}}
                            <div class="text-right shrink-0">
                                <span class="font-serif text-2xl text-[#9C4309] tracking-tight">
                                    <span class="text-xl mr-0.5">S/</span>{{ number_format((float) ($libro->precio * $item['cantidad']), 2) }}
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- COLUMNA DERECHA: RESUMEN DE COMPRA --}}
                <div class="flex flex-col gap-4 lg:sticky lg:top-8">
                    <aside class="bg-white p-6 rounded-2xl border border-[#421605]/10 shadow-sm">
                        <h2 class="font-serif text-2xl text-amber-900 mb-6">Resumen del Pedido</h2>
                        
                        <dl class="space-y-4 text-sm">
                            {{-- Subtotal --}}
                            <div class="flex justify-between text-[#554138]">
                                <dt class="font-medium text-[#8A7A71]">Subtotal</dt>
                                <dd class="font-medium text-[#421605]">S/ {{ number_format((float) $subtotal, 2) }}</dd>
                            </div>
                            
                            {{-- Envío --}}
                            <div class="flex justify-between text-[#554138]">
                                <dt class="text-[#8A7A71]">Envío</dt>
                                <dd class="text-sm text-emerald-600 font-medium tracking-wide">
                                    Gratis
                                </dd>
                            </div>
                            
                            {{-- Total en Marrón Claro con tipografía Serif --}}
                            <div class="border-t border-[#421605]/10 pt-4 flex justify-between items-baseline font-bold text-[#421605]">
                                <dt class="text-base font-bold">Total</dt>
                                <dd class="font-serif text-2xl text-[#9C4309] tracking-tight">
                                    <span class="text-xl mr-0.5">S/</span>{{ number_format((float) $total, 2) }}
                                </dd>
                            </div>
                        </dl>

                        {{-- Botón Proceder al Pago --}}
                        <a href="{{ route('checkout.create') }}" class="w-full flex items-center justify-center gap-2 bg-[#B8500C] hover:bg-[#963F07] text-white text-sm font-semibold py-3.5 rounded-full mt-6 transition-colors shadow-sm">
                            Proceder al Pago
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </aside>

                    {{-- Banner inferior: Envío gratis en todos los pedidos --}}
                    <div class="bg-[#FCF7F2] border border-[#B8500C]/10 rounded-2xl py-3 px-4 flex items-center justify-center gap-2 text-xs text-[#8A7A71] font-medium">
                        <span>🎉</span>
                        <span>Envío gratis en todos los pedidos</span>
                    </div>
                </div>

            </div>
        @endif
    </div>
</main>
@endsection