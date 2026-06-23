@extends('layouts.app')

@section('title', 'Pago simulado - BookShop')

@section('content')
<main class="px-[7%] py-12 flex-grow">
    <div class="max-w-xl mx-auto bg-white border border-[#421605]/10 rounded-3xl p-7 md:p-10 shadow-sm">
        <a href="{{ route('pedidos.show', $pedido) }}" class="text-sm text-[#B8500C]">&larr; Volver al pedido</a>
        <h1 class="font-serif text-3xl font-bold mt-5">Pasarela de prueba</h1>
        <p class="text-sm text-[#8A7A71] mt-2 mb-7">
            Esta pantalla simula la respuesta de una pasarela. No solicita ni almacena datos bancarios.
        </p>

        <div class="rounded-2xl bg-[#F3ECE0]/60 p-5 mb-7">
            <div class="flex justify-between text-sm">
                <span>Pedido #{{ $pedido->id }}</span>
                <strong>S/ {{ number_format((float) $pedido->total, 2) }}</strong>
            </div>
        </div>

        <form method="POST" action="{{ route('pagos.store', $pedido) }}" class="space-y-5">
            @csrf
            <fieldset>
                <legend class="text-sm font-bold mb-3">Resultado de la simulacion</legend>
                <div class="space-y-3">
                    @foreach ([
                        'aprobado' => 'Pago aprobado',
                        'rechazado' => 'Pago rechazado',
                        'pendiente' => 'Pago pendiente',
                    ] as $valor => $etiqueta)
                        <label class="flex items-center gap-3 border border-[#421605]/10 rounded-xl p-4 cursor-pointer hover:border-[#B8500C]/50">
                            <input type="radio" name="resultado" value="{{ $valor }}" required @checked(old('resultado', 'aprobado') === $valor)>
                            <span class="text-sm font-semibold">{{ $etiqueta }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
            <button class="w-full bg-[#B8500C] hover:bg-[#963F07] text-white rounded-full py-3 text-sm font-semibold">
                Procesar simulacion
            </button>
        </form>
    </div>
</main>
@endsection