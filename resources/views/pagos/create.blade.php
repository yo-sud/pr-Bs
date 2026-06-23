@extends('layouts.app')

@section('title', 'Pagar Pedido - BookShop')

@section('content')
<main class="px-[7%] py-12 flex-grow">
    <div class="max-w-xl mx-auto bg-white border border-[#421605]/10 rounded-3xl p-7 md:p-10 shadow-sm">
        <a href="{{ route('pedidos.show', $pedido) }}" class="text-sm text-[#B8500C]">&larr; Volver al pedido</a>
        
        <h1 class="font-serif text-3xl font-bold mt-5">Resumen de Pago</h1>
        <p class="text-sm text-[#8A7A71] mt-2 mb-7">
            Estás a un paso de completar tu compra. Serás redirigido a una plataforma segura para procesar tu pago.
        </p>

        <div class="rounded-2xl bg-[#F3ECE0]/60 p-5 mb-7">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between text-sm">
                    <span class="text-[#8A7A71]">Número de pedido:</span>
                    <strong>#{{ $pedido->id }}</strong>
                </div>
                <hr class="border-[#421605]/10 my-1">
                <div class="flex justify-between text-base">
                    <span class="font-semibold text-[#421605]">Total a pagar:</span>
                    <strong class="text-lg text-[#B8500C]">S/ {{ number_format((float) $pedido->total, 2) }}</strong>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pagos.store', $pedido) }}">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#B8500C] hover:bg-[#96400A] transition-colors text-white rounded-full py-3 text-sm font-bold shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Pagar de forma segura con Mercado Pago
            </button>
        </form>
    </div>
</main>
@endsection