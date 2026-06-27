@extends('layouts.app')

@section('title', 'BookShop - Inicio')

@section('content')
{{-- 1. SECCIÓN HERO --}} 
<div class="w-screen relative left-1/2 right-1/2 -mx-[50vw] bg-[#FAF5E6] pt-10 pb-12">
    <section class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 items-center px-6 sm:px-8 py-12 md:py-16 gap-12 w-full">
        <div class="max-w-[540px] text-center md:text-left order-2 md:order-1">
            <h1 class="font-serif text-[34px] sm:text-[42px] md:text-[56px] font-medium leading-[1.15] text-[#522912] leading-tight mb-6">
                Descubre historias para todas las edades
            </h1>
            <p class="text-lg md:text-xl text-gray-700 mb-8">
            Explora nuestra colección cuidadosamente seleccionada de libros que inspiran, educan y entretienen. Tu próxima aventura literaria te espera.
            </p>

            <a href="{{ route('libros.index') }}" class="inline-flex items-center gap-2 bg-[#B8500C] hover:bg-[#963F07] text-white px-10 py-4 rounded-full font-medium text-lg transition-all hover:-translate-y-0.5 shadow-sm mb-12">
                Explorar libros
                <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M5 12h14"></path>
                    <path d="m13 6 6 6-6 6"></path>
                </svg>
            </a>

            <div class="flex flex-wrap gap-6 sm:gap-10 justify-center md:justify-start border-t border-[#421605]/10 pt-8">
                <div>
                    <h3 class="text-3xl font-serif text-amber-900 text-center mb-1">{{ number_format($totalLibros) }}</h3>
                    <p class="text-[10px] sm:text-[11px] text-[#6E7E80] uppercase tracking-wider font-semibold">Libros disponibles</p>
                </div>
            <div>
                 @php
                    $totalCategorias = \App\Models\Categoria::count();
                 @endphp
                <h3 class="text-3xl font-serif text-amber-900 text-center mb-1">{{ number_format($totalCategorias) }}</h3>
                <p class="text-[10px] sm:text-[11px] text-[#6E7E80] uppercase tracking-wider font-semibold">Categorías</p>
            </div>
                <div>
                    <h3 class="text-3xl font-serif text-amber-900 text-center mb-1">24/7</h3>
                    <p class="text-[10px] sm:text-[11px] text-[#6E7E80] uppercase tracking-wider font-semibold">Servicio</p>
                </div>
            </div>
        </div>

        <div class="flex justify-center md:justify-end order-1 md:order-2">
            <div class="w-full max-w-[420px] md:max-w-[500px] aspect-[4/5] overflow-hidden rounded-[24px] md:rounded-[32px] shadow-[0_24px_60px_rgba(66,22,5,0.08)] bg-[#F3ECE0]">
                <img src="https://images.unsplash.com/photo-1756505087014-0cc7a8eda7dc?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHw0fHxjb3p5JTIwYm9va3Nob3AlMjByZWFkaW5nJTIwd2FybSUyMGxpYnJhcnl8ZW58MXx8fHwxNzc4MTIyOTMyfDA&ixlib=rb-4.1.0&q=80&w=1080" alt="Colección de BookShop" class="w-full h-full object-cover">
            </div>
        </div>
    </section>
</div>

{{-- CUERPO PRINCIPAL EN COLOR CLARO --}}
<main class="w-full bg-[#FDFBF7]">
    
    {{-- 2. SECCIÓN DE CATEGORÍAS --}}
    <section class="max-w-7xl mx-auto px-6 sm:px-8 py-16 text-center">
        <div class="mb-12">
            <h2 class="text-3xl md:text-4xl font-serif text-amber-900 mb-4">Explora por Categorías</h2>
            <p class="text-sm text-[#8A7A71] max-w-[600px] mx-auto">
                Encuentra exactamente lo que buscas en nuestra selección literaria.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categorias as $categoria)
                <a href="{{ route('libros.index', ['categoria' => $categoria->id]) }}" class="bg-white border border-[#421605]/15 rounded-[20px] p-6 sm:p-8 flex items-center gap-5 text-left shadow-[0_10px_25px_-5px_rgba(66,22,5,0.06)] hover:shadow-[0_15px_35px_rgba(66,22,5,0.12)] hover:-translate-y-1 transition-all duration-300">
                    
                    <span class="w-12 h-12 bg-[#FFF9EE] text-[#B8500C] rounded-xl flex items-center justify-center shrink-0 border border-[#B8500C]/15">
                        @if($categoria->nombre === 'Literatura')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                <path d="M7.5 10.5c.8-1.2 2.3-3.1 4.5-4.5 0 2.2-1.9 3.7-3.1 4.5H7.5z"/>
                                <path d="m11.5 11.5 5 5"/>
                            </svg>   

                        @elseif($categoria->nombre === 'Ciencia Ficción' || $categoria->nombre === 'Ciencia Ficcion')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4.5 16.5c-1.5 1.5-2.5 3.5-2.5 5.5C4 22 6 21 7.5 19.5"/>
                                <path d="m12 2 3 5 6 1-4 4 1 6-6-3-6 3 1-6-4-4 6-1z"/>
                            </svg>

                        @elseif($categoria->nombre === 'Misterio')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>

                        @elseif($categoria->nombre === 'Desarrollo Personal')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22V12"/>
                                <path d="M12 12H2a10 10 0 0 1 10-10 10 10 0 0 1 10 10H12z"/>
                                <path d="M16 16a4 4 0 1 0-8 0"/>
                            </svg>

                        @elseif($categoria->nombre === 'Historia')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 2h14"/>
                                <path d="M5 22h14"/>
                                <path d="M19 2v4c0 4-3 7-7 7s-7-3-7-7V2"/>
                                <path d="M5 22v-4c0-4 3-7 7-7s7 3 7 7v4"/>
                            </svg>

                        @elseif($categoria->nombre === 'Terror')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 10h.01"/>
                                <path d="M15 10h.01"/>
                                <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z"/>
                            </svg>

                        @else
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"></path>
                            </svg>
                        @endif
                    </span>

                    <span>
                        <strong class="block font-serif text-xl text-[#421605] mb-1">{{ $categoria->nombre }}</strong>
                        <span class="text-xs text-[#8A7A71]">{{ $categoria->libros_count }} títulos disponibles</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- 3. SECCIÓN DE LIBROS DESTACADOS --}}
    <section class="max-w-7xl mx-auto px-6 sm:px-8 py-16 text-center">
        <div class="mb-12">
            <h2 class="text-3xl md:text-4xl font-serif text-amber-900 mb-4">Libros Destacados</h2>
            <p class="text-sm text-[#8A7A71] max-w-[600px] mx-auto">
                Una selección especial basada en los títulos favoritos de nuestros lectores.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-12">
            @foreach ($destacados as $libro)
                <x-book-card :libro="$libro" />
            @endforeach
        </div>

        <a href="{{ route('libros.index') }}" class="inline-flex items-center justify-center border border-[#B8500C] text-[#B8500C] hover:bg-[#B8500C] hover:text-white px-8 py-3 rounded-full font-medium text-sm transition-all">
            Ver todos los libros
        </a>
    </section>
</main>
@endsection