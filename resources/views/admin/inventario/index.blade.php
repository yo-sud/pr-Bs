@extends('layouts.admin')

@section('title', 'Inventario - Administración')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-start justify-between w-full gap-6">
        <div class="flex-1">
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Inventario</h2>
            <p class="text-sm text-gray-500 mt-1">Monitorea el stock de todos los libros</p>
        </div>
        <div class="flex-shrink-0 pt-1">
            <a href="{{ route('admin.reposicion.paso1') }}"
               class="inline-flex items-center gap-2 bg-[#FF6B00] text-white hover:bg-[#E05E00] px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined text-lg">trending_up</span>
                Cálculo de Reposición Inteligente
            </a>
        </div>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Disponibles --}}
        <a href="{{ route('admin.inventario.index', ['estado' => 'disponibles']) }}"
           class="bg-[#F0FDF4] rounded-xl p-4 flex flex-col items-center gap-1 hover:shadow-md transition-shadow cursor-pointer {{ $estado === 'disponibles' ? 'border-2 border-[#16A34A]' : 'border border-[#BBF7D0]' }}">
            <span class="material-symbols-outlined text-2xl text-[#16A34A]">check_circle</span>
            <span class="text-3xl font-bold text-[#16A34A]">{{ $totales['disponibles'] }}</span>
            <span class="text-xs text-gray-500 font-medium">Disponibles</span>
        </a>

        {{-- Stock bajo --}}
        <a href="{{ route('admin.inventario.index', ['estado' => 'bajo']) }}"
           class="bg-[#FFFBEB] rounded-xl p-4 flex flex-col items-center gap-1 hover:shadow-md transition-shadow cursor-pointer {{ $estado === 'bajo' ? 'border-2 border-[#D97706]' : 'border border-[#FDE68A]' }}">
            <span class="material-symbols-outlined text-2xl text-[#D97706]">trending_down</span>
            <span class="text-3xl font-bold text-[#D97706]">{{ $totales['bajo'] }}</span>
            <span class="text-xs text-gray-500 font-medium">Stock bajo (&lt;10)</span>
        </a>

        {{-- Agotados --}}
        <a href="{{ route('admin.inventario.index', ['estado' => 'agotados']) }}"
           class="bg-[#FFF1F2] rounded-xl p-4 flex flex-col items-center gap-1 hover:shadow-md transition-shadow cursor-pointer {{ $estado === 'agotados' ? 'border-2 border-[#DC2626]' : 'border border-[#FECDD3]' }}">
            <span class="material-symbols-outlined text-2xl text-[#DC2626]">warning</span>
            <span class="text-3xl font-bold text-[#DC2626]">{{ $totales['agotados'] }}</span>
            <span class="text-xs text-gray-500 font-medium">Agotados</span>
        </a>
    </div>

    {{-- Buscador --}}
    <form action="{{ route('admin.inventario.index') }}" method="GET">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Buscar libro o categoría..."
                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FF6B00]/30 focus:border-[#FF6B00]"
            >
        </div>
    </form>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-[#F5E6C8] shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#FDF6E9] border-b border-[#F5E6C8] text-xs uppercase font-bold text-[#92400E] tracking-wide">
                    <th class="px-5 py-4 text-left">Libro</th>
                    <th class="px-5 py-4 text-left">Categoría</th>
                    <th class="px-5 py-4 text-left">Formato</th>
                    <th class="px-5 py-4 text-left">Stock Actual</th>
                    <th class="px-5 py-4 text-left">Indicador</th>
                    <th class="px-5 py-4 text-left">Estado</th>
                    <th class="px-5 py-4 text-left">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($libros as $libro)
                    @php
                        $stock = $libro->stock;
                        $barMax = max($stock, 50);
                        $barPct = $barMax > 0 ? min(100, round($stock / $barMax * 100)) : 0;

                        if ($stock === 0) {
                            $barColor   = 'bg-red-500';
                            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                            $badgeLabel = 'Agotado';
                        } elseif ($stock < 10) {
                            $barColor   = 'bg-amber-400';
                            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                            $badgeLabel = 'Stock bajo';
                        } else {
                            $barColor   = 'bg-green-500';
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            $badgeLabel = 'Disponible';
                        }
                    @endphp
                    <tr class="hover:bg-[#FFFDF8] transition-colors">
                        {{-- Libro --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $libro->portada_url }}"
                                     alt="{{ $libro->titulo }}"
                                     class="w-10 h-14 object-cover rounded shadow-sm flex-shrink-0">
                                <div>
                                    <span class="font-semibold text-gray-800 block">{{ $libro->titulo }}</span>
                                    <span class="text-xs text-gray-400">{{ $libro->autor }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Categoría --}}
                        <td class="px-5 py-4">
                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $libro->categoria->nombre ?? '—' }}
                            </span>
                        </td>

                        {{-- Formato --}}
                        <td class="px-5 py-4 text-gray-600">Ambos</td>

                        {{-- Stock actual --}}
                        <td class="px-5 py-4">
                            <span class="text-base font-bold {{ $stock === 0 ? 'text-red-600' : ($stock < 10 ? 'text-amber-600' : 'text-green-600') }}">
                                {{ $stock }}
                            </span>
                        </td>

                        {{-- Indicador --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-28 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $barPct }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">{{ $stock }}</span>
                            </div>
                        </td>

                        {{-- Estado --}}
                        <td class="px-5 py-4">
                            <span class="inline-block border text-xs font-semibold px-3 py-1 rounded-full {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </td>

                        {{-- Acción --}}
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.libros.edit', $libro) }}"
                               class="inline-flex items-center gap-1 text-[#FF6B00] hover:text-[#E05E00] text-xs font-semibold transition-colors">
                                <span class="material-symbols-outlined text-base">edit</span>
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            No se encontraron libros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div>{{ $libros->links() }}</div>

</div>
@endsection
