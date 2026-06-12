@extends('layouts.admin')

@section('title', 'Inventario - Administración')

@section('contenido')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Inventario</h2>
        <p class="text-sm text-gray-500 mt-1">Movimientos registrados por libro, usuario y motivo.</p>
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
