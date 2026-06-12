@extends('layouts.app')

@section('title', 'Mis pedidos - BookShop')

@section('content')
<main class="px-[7%] py-12 flex-grow">
    <h1 class="font-serif text-3xl font-bold text-[#421605] mb-8">Mis pedidos</h1>

    @if ($pedidos->isEmpty())
        <div class="bg-white border border-[#421605]/10 rounded-2xl p-10 text-center">
            <p class="text-[#8A7A71] mb-5">Todavia no has realizado ninguna compra.</p>
            <a href="{{ route('libros.index') }}" class="inline-flex bg-[#B8500C] text-white px-6 py-3 rounded-full text-sm font-semibold">Explorar libros</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($pedidos as $pedido)
                <a href="{{ route('pedidos.show', $pedido) }}" class="block bg-white border border-[#421605]/10 rounded-2xl p-5 hover:border-[#B8500C]/50 transition-colors">
                    <div class="flex flex-wrap justify-between gap-4">
                        <div>
                            <p class="font-serif text-lg font-bold">Pedido #{{ $pedido->id }}</p>
                            <p class="text-xs text-[#8A7A71] mt-1">{{ $pedido->created_at->format('d/m/Y H:i') }} · {{ $pedido->detalles_count }} productos</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex rounded-full bg-[#F3ECE0] px-3 py-1 text-xs font-semibold uppercase">{{ $pedido->estado_pedido }}</span>
                            <p class="font-bold mt-2">S/ {{ number_format((float) $pedido->total, 2) }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $pedidos->links() }}</div>
    @endif
</main>
@endsection
