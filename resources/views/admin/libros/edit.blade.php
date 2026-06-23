@extends('layouts.admin')

@section('title', 'Editar '.$libro->titulo)

@section('contenido')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Editar libro</h2>
        <p class="text-sm text-gray-500">{{ $libro->titulo }} · Stock actual: <strong>{{ $libro->stock }}</strong></p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6">
        <form method="POST" action="{{ route('admin.libros.update', $libro) }}" enctype="multipart/form-data" class="bg-white rounded-xl border p-6 shadow-sm">
            @include('admin.libros._form')
        </form>

        <div class="space-y-6">
            <section class="bg-white rounded-xl border p-5 shadow-sm">
                <h3 class="font-bold text-[#2C1B12] mb-1">Ajustar stock</h3>
                <p class="text-xs text-gray-500 mb-4">Usa cantidades negativas para descontar.</p>
                <form method="POST" action="{{ route('admin.libros.stock', $libro) }}" class="space-y-4">
                    @csrf
                    <label>
                        <span class="block text-sm font-semibold mb-1">Cantidad</span>
                        <input type="number" name="cantidad" required placeholder="Ej. 10 o -2" class="w-full rounded-lg border-gray-300">
                    </label>
                    <label>
                        <span class="block text-sm font-semibold mb-1">Motivo</span>
                        <input name="motivo" required maxlength="255" placeholder="Compra a proveedor, corrección..." class="w-full rounded-lg border-gray-300">
                    </label>
                    <button class="w-full bg-[#2C1B12] text-white py-2.5 rounded-lg text-sm font-semibold">Aplicar ajuste</button>
                </form>
            </section>

            <section class="bg-white rounded-xl border shadow-sm overflow-hidden">
                <h3 class="font-bold text-[#2C1B12] p-5 border-b">Últimos movimientos</h3>
                <div class="divide-y">
                    @forelse ($movimientos as $movimiento)
                        <div class="p-4 text-sm">
                            <div class="flex justify-between">
                                <strong>{{ $movimiento->cantidad > 0 ? '+' : '' }}{{ $movimiento->cantidad }}</strong>
                                <span class="text-xs text-gray-500">{{ $movimiento->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $movimiento->motivo }}</p>
                            <p class="text-xs text-gray-400">{{ $movimiento->stock_anterior }} → {{ $movimiento->stock_nuevo }}</p>
                        </div>
                    @empty
                        <p class="p-5 text-sm text-gray-500">Sin movimientos registrados.</p>
                    @endforelse
                </div>
            </section>

            @if ($libro->estado === 'activo')
                <form method="POST" action="{{ route('admin.libros.destroy', $libro) }}" onsubmit="return confirm('¿Desactivar este libro?')">
                    @csrf
                    @method('DELETE')
                    <button class="w-full border border-red-300 text-red-700 py-2.5 rounded-lg text-sm font-semibold">Desactivar libro</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
