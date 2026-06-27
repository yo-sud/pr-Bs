@extends('layouts.app')

@section('title', 'Pagar Pedido #'.$pedido->id.' - BookShop')

@section('content')
<main class="px-[4%] sm:px-[7%] py-12 flex-grow flex items-center justify-center" style="background-color: #F9F6F3;">
    <div class="max-w-md w-full mx-auto text-center space-y-6">

        {{-- Ícono --}}
        <div class="w-20 h-20 bg-[#FFF0E0] rounded-full flex items-center justify-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#B8500C]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
            </svg>
        </div>

        {{-- Título --}}
        <div>
            <h1 class="font-serif text-2xl font-bold text-[#421605]">Completa tu pago</h1>
            <p class="text-sm text-[#8A7A71] mt-2">
                Se abrirá MercadoPago en una pestaña nueva.<br>
                Cuando termines de pagar, vuelve aquí y presiona el botón de abajo.
            </p>
        </div>

        {{-- Botón abrir MP --}}
        <a href="{{ $mpUrl }}" target="_blank" rel="noopener"
           class="inline-flex items-center justify-center gap-2 w-full bg-[#D97706] hover:bg-[#B45309] text-white px-6 py-4 rounded-2xl font-semibold text-base transition-colors shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            Ir a MercadoPago
        </a>

        {{-- Divisor --}}
        <div class="flex items-center gap-3 text-xs text-[#8A7A71]">
            <div class="flex-1 h-px bg-[#E5D8CC]"></div>
            <span>¿Ya pagaste?</span>
            <div class="flex-1 h-px bg-[#E5D8CC]"></div>
        </div>

        {{-- Botón verificar --}}
        <form method="POST" action="{{ route('pagos.verificar', $pedido) }}">
            @csrf
            <input type="hidden" name="referencia" value="{{ $referencia }}">
            <button type="submit"
                    class="w-full bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-4 rounded-2xl font-semibold text-base transition-colors shadow-md">
                ✓ Ya pagué — Confirmar pedido
            </button>
        </form>

        {{-- Cancelar --}}
        <a href="{{ route('pedidos.show', $pedido) }}"
           class="block text-sm text-[#8A7A71] hover:text-[#421605] transition-colors">
            Volver al pedido sin pagar
        </a>

    </div>
</main>
@endsection
