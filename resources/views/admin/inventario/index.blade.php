@extends('layouts.admin')

@section('title', 'Inventario - Administración')

@section('contenido')
<div class="space-y-6 pt-20 px-4 sm:px-0">
    {{-- Cabecera con elementos distribuidos a los extremos (Izquierda y Derecha) --}}
    <div class="flex items-start justify-between w-full gap-6">
        {{-- Bloque Izquierdo: Título y Descripción juntos --}}
        <div class="flex-1">
            <h2 class="text-3xl font-bold text-[#2C1B12]">Inventario</h2>
            <p class="text-sm text-gray-500 mt-1">Monitorea el stock de todos los libros</p>
        </div>
        
        {{-- Bloque Derecho: Botón empujado al extremo derecho --}}
        <div class="flex-shrink-0 pt-1">
            <a href="#" class="inline-flex items-center gap-2 bg-[#FF6B00] text-white hover:bg-[#E05E00] px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined text-lg">trending_up</span>
                Cálculo de Reposición Inteligente
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Fecha</th>
                    <th class="px-5 py-4">Libro</th>
                    <th class="px-5 py-4">Usuario</th>
                    <th class="px-5 py-4">Tipo</th>
                    <th class="px-5 py-4">Cantidad</th>
                    <th class="px-5 py-4">Stock</th>
                    <th class="px-5 py-4">Motivo</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($movimientos as $movimiento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 whitespace-nowrap">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4">
                            <div class="font-semibold">{{ $movimiento->libro?->titulo ?? 'Libro eliminado' }}</div>
                            <div class="text-xs text-gray-500">{{ $movimiento->libro?->isbn ?? 'Sin ISBN' }}</div>
                        </td>
                        <td class="px-5 py-4">{{ $movimiento->usuario?->name ?? 'Usuario eliminado' }}</td>
                        <td class="px-5 py-4 uppercase text-xs font-semibold">{{ $movimiento->tipo }}</td>
                        <td class="px-5 py-4 font-bold {{ $movimiento->cantidad > 0 ? 'text-green-700' : 'text-red-600' }}">
                            {{ $movimiento->cantidad > 0 ? '+' : '' }}{{ $movimiento->cantidad }}
                        </td>
                        <td class="px-5 py-4">
                            {{ $movimiento->stock_anterior }} → {{ $movimiento->stock_nuevo }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">{{ $movimiento->motivo }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-500">No hay movimientos de inventario todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $movimientos->links() }}</div>
</div>
@endsection
