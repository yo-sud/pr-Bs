@extends('layouts.admin')

@section('title', 'Libros - Administración')

@section('contenido')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#2C1B12]">Libros</h2>
            <p class="text-sm text-gray-500">Administra el catálogo.</p>
        </div>
        <a href="{{ route('admin.libros.create') }}" class="bg-[#B8500C] hover:bg-[#963F07] text-white px-5 py-2.5 rounded-xl text-sm font-semibold">Nuevo libro</a>
    </div>

    <form class="bg-white p-4 rounded-xl border flex flex-col sm:flex-row gap-3">
        <input name="search" value="{{ request('search') }}" placeholder="Título, autor o ISBN" class="flex-1 rounded-lg border-gray-300 text-sm">
        <select name="stock" class="rounded-lg border-gray-300 text-sm">
            <option value="">Todo el inventario</option>
            <option value="bajo" @selected(request('stock') === 'bajo')>Stock bajo</option>
        </select>
        <button class="bg-[#2C1B12] text-white px-5 py-2 rounded-lg text-sm">Filtrar</button>
    </form>

    <div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-3">Libro</th>
                    <th class="px-5 py-3">Categoría</th>
                    <th class="px-5 py-3">Precio</th>
                    <th class="px-5 py-3">Stock</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($libros as $libro)
                    <tr>
                        <td class="px-5 py-4">
                            <strong class="block">{{ $libro->titulo }}</strong>
                            <span class="text-xs text-gray-500">{{ $libro->autor }} · {{ $libro->isbn ?: 'Sin ISBN' }}</span>
                        </td>
                        <td class="px-5 py-4">{{ $libro->categoria->nombre }}</td>
                        <td class="px-5 py-4">S/ {{ number_format((float) $libro->precio, 2) }}</td>
                        <td class="px-5 py-4 font-bold {{ $libro->stock <= 5 ? 'text-amber-600' : 'text-gray-800' }}">{{ $libro->stock }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $libro->estado === 'activo' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($libro->estado) }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.libros.edit', $libro) }}" class="font-semibold text-[#B8500C]">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $libros->links() }}
</div>
@endsection
