@extends('layouts.app')

@section('title', 'Sesión Expirada - BookShop')

@section('content')
<main class="min-h-[65vh] flex items-center justify-center bg-[#F9F6F3] px-[4%] py-12">
    <div class="max-w-md w-full bg-white border border-[#421605]/10 rounded-2xl p-6 sm:p-8 text-center shadow-sm space-y-5">
        <div class="w-16 h-16 bg-[#F3ECE0]/50 rounded-full flex items-center justify-center mx-auto text-[#B8500C]">
            {{-- Icono de reloj/tiempo --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
        
        <div class="space-y-2">
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#421605]">Tu sesión ha expirado</h1>
            <p class="text-xs sm:text-sm text-[#8A7A71] leading-relaxed">
                Por razones de seguridad y para proteger tus datos, la página se cerró debido a inactividad. ¡No te preocupes! Tus libros siguen esperándote en el carrito.
            </p>
        </div>

        <div class="pt-2">
            <a href="{{ url()->previous() }}" class="inline-block w-full sm:w-auto bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-3 rounded-full text-sm font-semibold transition-colors shadow-sm">
                Regresar y reintentar
            </a>
        </div>
    </div>
</main>
@endsection