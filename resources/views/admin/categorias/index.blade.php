@extends('layouts.admin')

@section('title', 'Categorías - Administración')

@section('contenido')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Categorías</h2>
        <p class="text-sm text-gray-500">Organiza los libros del catálogo.</p>
    </div>

    <form method="POST" action="{{ route('admin.categorias.store') }}" class="bg-white rounded-xl border p-5 flex flex-col sm:flex-row gap-3">
        @csrf
        <input name="nombre" required maxlength="100" placeholder="Nombre de la nueva categoría" class="flex-1 rounded-lg border-gray-300">
        <button class="bg-[#B8500C] text-white px-5 py-2.5 rounded-lg text-sm font-semibold">Agregar categoría</button>
    </form>

    <div class="bg-white rounded-xl border shadow-sm divide-y">
        @foreach ($categorias as $categoria)
            <div class="p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" class="flex-1 flex gap-3">
                    @csrf
                    @method('PUT')
                    <input name="nombre" value="{{ $categoria->nombre }}" required maxlength="100" class="flex-1 rounded-lg border-gray-300">
                    <button class="text-sm font-semibold text-[#B8500C] px-3">Guardar</button>
                </form>
                <span class="text-xs text-gray-500">{{ $categoria->libros_count }} libros</span>
                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" onsubmit="return confirm('¿Eliminar esta categoría?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm text-red-600 disabled:text-gray-300" @disabled($categoria->libros_count > 0)>Eliminar</button>
                </form>
            </div>
        @endforeach
    </div>

    {{ $categorias->links() }}
</div>
@endsection
