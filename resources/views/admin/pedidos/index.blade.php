@extends('layouts.admin')

@section('title', 'Pedidos - Administración')

@section('contenido')
<div class="flex items-center justify-between gap-4 mb-7">
    <div>
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Pedidos</h2>
        <p class="text-sm text-gray-500 mt-1">Pagos, preparacion, envio y entrega.</p>
    </div>
</div>

<div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-5 py-4">Pedido</th>
                <th class="px-5 py-4">Cliente</th>
                <th class="px-5 py-4">Pago</th>
                <th class="px-5 py-4">Estado</th>
                <th class="px-5 py-4 text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($pedidos as $pedido)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.pedidos.show', $pedido) }}" class="font-bold text-[#B8500C]">#{{ $pedido->id }}</a>
                        <p class="text-xs text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </td>
                    <td class="px-5 py-4">{{ $pedido->usuario?->name ?: 'Usuario eliminado' }}</td>
                    <td class="px-5 py-4 uppercase text-xs font-semibold">{{ $pedido->estado_pago }}</td>
                    <td class="px-5 py-4 uppercase text-xs font-semibold">{{ $pedido->estado_pedido }}</td>
                    <td class="px-5 py-4 text-right font-bold">S/ {{ number_format((float) $pedido->total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No hay pedidos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $pedidos->links() }}</div>
@endsection
