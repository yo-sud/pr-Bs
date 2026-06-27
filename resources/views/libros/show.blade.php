@extends('layouts.app')

@section('title', $libro->titulo.' - BookShop')

@section('content')
<main class="px-[8%] py-10 flex-grow bg-white min-h-screen text-[#78350F] font-sans antialiased">
    
    <div class="max-w-5xl mx-auto mb-6">
        <a href="{{ route('home') }}" class="text-[#D97706] hover:text-[#78350F] text-sm font-medium flex items-center gap-1 transition-colors duration-200">
            <span class="text-base select-none">&lt;</span>
            Volver
        </a>
    </div>

    <section class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-[410px_1fr] gap-12 items-start">
        <div class="flex flex-col gap-4 w-full lg:sticky lg:top-10">
            <div class="bg-white border border-gray-100 rounded-[24px] p-6 shadow-[0_25px_50px_-12px_rgba(66,22,5,0.18),0_12px_20px_-8px_rgba(66,22,5,0.12)] flex justify-center items-center">
                <img src="{{ $libro->portada_url }}" alt="Portada de {{ $libro->titulo }}" class="w-full h-auto object-contain rounded-xl">
            </div>
        </div>

        <div class="w-full flex flex-col gap-5 pt-1">
            
            <div>
                <span class="text-xs font-semibold text-[#D97706] block mb-1">
                    {{ $libro->categoria->nombre }}
                </span>
                <h1 class="font-sans text-[32px] font-bold text-[#78350F] tracking-tight leading-tight mb-2">
                    {{ $libro->titulo }}
                </h1>
                <p class="text-sm text-[#C2410C]">
                    por {{ $libro->autor }}
                </p>
            </div>

            <div class="bg-gradient-to-r from-[#FFF7D6] via-[#FFFBF0] to-[#FFFFFF] border border-[#FDE68A] rounded-[18px] py-4 px-5">
                <p class="text-[32px] font-bold text-[#78350F] tracking-tight">
                    S/ {{ number_format((float) $libro->precio, 2) }}
                </p>
            </div>

            @if ($libro->stock > 0)
                <form method="POST" action="{{ route('carrito.store', $libro) }}" class="w-full flex flex-col gap-6">
                    
                    
                    <div class="flex flex-col gap-2">
                        <span class="text-sm font-bold text-[#78350F]">Cantidad</span>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 bg-[#FFFDF9]">
                                <button type="button" onclick="decrementarCantidad()" class="w-9 h-9 bg-[#FEF3C7] text-[#78350F] hover:bg-[#FDE68A] transition rounded-[10px] font-bold text-lg flex items-center justify-center select-none">
                                    -
                                </button>
                                
                                <input type="number" 
                                       id="cantidad_input" 
                                       name="cantidad" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $libro->stock }}" 
                                       data-max-stock="{{ $libro->stock }}"
                                       oninput="validarCantidad()"
                                       onblur="corregirVacio()"
                                       class="w-12 h-9 text-center bg-transparent border-0 font-bold text-base text-[#78350F] focus:outline-none focus:ring-0 p-0 [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:hidden [&::-webkit-inner-spin-button]:hidden">
                                
                                <button type="button" onclick="incrementarCantidad()" class="w-9 h-9 bg-[#FEF3C7] text-[#78350F] hover:bg-[#FDE68A] transition rounded-[10px] font-bold text-lg flex items-center justify-center select-none">
                                    +
                                </button>
                            </div>
                            
                            <span class="text-sm text-[#C2410C]/70 font-medium">
                                ({{ $libro->stock }} disponibles)
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#FF9F00] hover:bg-[#EA580C] text-white py-4 px-6 rounded-2xl font-bold text-base tracking-wide transition-all duration-300 transform shadow-[0_12px_24px_-6px_rgba(255,159,0,0.35)] hover:shadow-[0_16px_32px_-6px_rgba(234,88,12,0.45)] flex items-center justify-center gap-2.5 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                        Añadir al Carrito
                    </button>
                </form>
            @endif

            <hr class="border-t border-[#FDE68A] mt-4 mb-1">
            <div class="grid grid-cols-3 gap-2 py-3 text-center text-[10px] text-[#C2410C] font-semibold">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-9 h-9 text-[#FF8A00]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 18H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="17" cy="18" r="2" stroke="currentColor" stroke-width="2" fill="white"/>
                        <circle cx="7" cy="18" r="2" stroke="currentColor" stroke-width="2" fill="white"/>
                    </svg>
                    <span>Envío Gratis</span>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <svg class="w-9 h-9 text-[#FF8A00]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Compra Segura</span>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <svg class="w-9 h-9 text-[#FF8A00]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1M12 21c-3-1.5-7-1.5-9-1V4c2-.5 6-.5 9 1v16z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 5c3-1.5 7-1.5 9-1v16c-2-.5-6-.5-9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Original</span>
                </div>
            </div>

            <div class="border border-[#FDE68A] rounded-[16px] p-5">
                <h3 class="font-bold text-sm text-[#78350F] mb-4">Información del Libro</h3>
                <div class="flex flex-col gap-3.5 text-xs">
                    <div class="flex justify-between items-center text-[#C2410C]">
                        <span class="font-medium">ISBN:</span>
                        <span class="text-[#78350F] font-semibold">{{ $libro->isbn ?: '978-0321965516' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#C2410C]">
                        <span class="font-medium">Editorial:</span>
                        <span class="text-[#78350F] font-semibold">{{ $libro->editorial ?: 'New Riders' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#C2410C]">
                        <span class="font-medium">Publicación:</span>
                        <span class="text-[#78350F] font-semibold">{{ $libro->fecha_publicacion?->format('F \d\e Y') ?: 'enero de 2014' }}</span>
                    </div>
                </div>
            </div>

            <div class="border border-[#FDE68A] rounded-[16px] p-5">
                <h3 class="font-bold text-sm text-[#78350F] mb-3">Descripción</h3>
                <p class="text-xs text-[#C2410C] leading-relaxed">
                    {{ $libro->descripcion ?: 'Principios fundamentales del diseño web intuitivo.' }}
                </p>
            </div>
        </div>
    </section>
</main>

<script>
    function incrementarCantidad() {
        const input = document.getElementById('cantidad_input');
        const maxStock = parseInt(input.getAttribute('data-max-stock')) || 0;
        let value = parseInt(input.value) || 0;
        if (value < maxStock) {
            input.value = value + 1;
        }
    }

    function decrementarCantidad() {
        const input = document.getElementById('cantidad_input');
        let value = parseInt(input.value) || 1;
        if (value > 1) {
            input.value = value - 1;
        }
    }

    function validarCantidad() {
        const input = document.getElementById('cantidad_input');
        const maxStock = parseInt(input.getAttribute('data-max-stock')) || 0;
        let value = parseInt(input.value);

        if (value > maxStock) {
            input.value = maxStock;
        }
        else if (value < 1) {
            input.value = 1;
        }
    }

    function corregirVacio() {
        const input = document.getElementById('cantidad_input');
        if (input.value === "" || parseInt(input.value) < 1) {
            input.value = 1;
        }
    }
</script>
@endsection