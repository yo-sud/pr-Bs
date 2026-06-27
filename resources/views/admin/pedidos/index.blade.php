@extends('layouts.admin')

@section('title', 'Pedidos - Administración')

@section('contenido')
<div class="flex items-center justify-between gap-4 mb-7">
    <div>
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Pedidos</h2>
        <p class="text-sm text-gray-500 mt-1">Gestión de pagos, preparación, envío y entrega.</p>
    </div>
</div>

<div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500 tracking-wider">
            <tr>
                <th class="px-5 py-4">Pedido</th>
                <th class="px-5 py-4">Cliente</th>
                <th class="px-5 py-4 text-center">Ítems</th>
                <th class="px-5 py-4">Pago</th>
                <th class="px-5 py-4">Repartidor</th>
                <th class="px-5 py-4 text-right">Total</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse ($pedidos as $pedido)
                @php
                    $badgePago = match($pedido->estado_pago) {
                        'pagado'      => 'bg-emerald-100 text-emerald-800',
                        'fallido'     => 'bg-red-100 text-red-800',
                        'reembolsado' => 'bg-sky-100 text-sky-800',
                        default       => 'bg-amber-100 text-amber-800',
                    };
                @endphp
                <tr class="hover:bg-orange-50/40 transition-colors">
                    <td class="px-5 py-4">
                        <span class="font-bold text-[#B8500C]">#{{ $pedido->id }}</span>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $pedido->usuario?->name ?: 'Usuario eliminado' }}</p>
                        @if ($pedido->usuario?->email)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $pedido->usuario->email }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center font-medium text-gray-600">
                        {{ $pedido->detalles_count }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $badgePago }}">
                            {{ $pedido->estado_pago }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-500">
                        {{ $pedido->repartidor?->nombre_empresa ?? '—' }}
                    </td>
                    <td class="px-5 py-4 text-right font-bold text-gray-800 tabular-nums">
                        S/ {{ number_format((float) $pedido->total, 2) }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('admin.pedidos.show', $pedido) }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[#B8500C] hover:bg-orange-100 transition-colors"
                           title="Ver pedido">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-14 text-center text-gray-400 text-sm">
                        No hay pedidos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $pedidos->links() }}</div>
@endsection
