@extends('layouts.admin')

@section('title', 'Libros - Administración')

@section('contenido')
<div class="space-y-6">
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Gestión de Libros</h2>
            <p class="text-stone-500 text-sm mt-1">Administra el catálogo completo de libros.</p>
        </div>
        <a href="{{ route('admin.libros.create') }}" class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
            + Agregar libro
        </a>
    </div>

    {{-- Filtros y Buscador --}}
    <div class="bg-white p-4 rounded-xl border flex flex-col md:flex-row items-center gap-4">
        
        <form action="{{ route('admin.libros.index') }}" method="GET" class="flex-1 w-full">
            {{-- Mantenemos el estado actual si existe al buscar --}}
            @if(request('estado'))
                <input type="hidden" name="estado" value="{{ request('estado') }}">
            @endif
            <input name="search" value="{{ request('search') }}" placeholder="Buscar por título, autor o isbn..." class="w-full rounded-lg border-gray-300 text-sm py-2 px-4 focus:border-amber-500 focus:ring-amber-500">
        </form>

        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
            @php
                $opciones = [
                    'todos' => null,
                    'disponibles' => 'disponibles',
                    'bajo' => 'bajo',
                    'agotados' => 'agotados'
                ];
            @endphp

            @foreach($opciones as $label => $valor)
                <a href="{{ route('admin.libros.index', array_filter(array_merge(request()->query(), ['estado' => $valor]))) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors
                   {{ (request('estado') == $valor || ($valor === null && !request('estado'))) 
                       ? 'bg-[#B8500C] text-white' 
                       : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                   {{ ucfirst($label) }} ({{ $totales[$label] ?? 0 }})
                </a>
            @endforeach
        </div>
    </div>

    {{-- Tabla de Libros --}}
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-amber-50 border-b border-amber-100 text-left text-xs uppercase text-amber-900 font-semibold">
                <tr>
                    <th class="px-5 py-3">Libro</th>
                    <th class="px-5 py-3">Categoría</th>
                    <th class="px-5 py-3">Precio</th>
                    <th class="px-5 py-3">Stock</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-50">
                @forelse ($libros as $libro)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($libro->portada_url)
                                    <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}"
                                         class="w-10 h-14 object-cover rounded shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-10 h-14 bg-amber-100 rounded flex items-center justify-center flex-shrink-0" title="Sin portada">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <strong class="block text-stone-800">{{ $libro->titulo }}</strong>
                                    <span class="text-xs text-stone-500">{{ $libro->autor }} · {{ $libro->isbn ?: 'Sin ISBN' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-stone-600">{{ $libro->categoria->nombre }}</td>
                        <td class="px-5 py-4 text-stone-700">S/ {{ number_format((float) $libro->precio, 2) }}</td>
                        <td class="px-5 py-4 font-bold {{ $libro->stock <= 5 ? 'text-amber-700' : 'text-stone-800' }}">
                            {{ $libro->stock }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $libro->estado === 'activo' ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-600' }}">
                                {{ ucfirst($libro->estado) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.libros.edit', $libro) }}" 
                               class="inline-flex items-center text-[#B8500C] hover:text-[#963F07] transition-colors"
                               title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-stone-500">No se encontraron libros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $libros->links() }}
</div>
@endsection