@extends('layouts.admin')
@section('title', 'Categorías - Administración')
@section('contenido')

<div class="space-y-6">

    <div>
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Categorías</h2>
        <p class="text-sm text-gray-500">Organiza los libros del catálogo.</p>
    </div>

    <form method="POST" action="{{ route('admin.categorias.store') }}" class="bg-white rounded-xl border p-5 flex flex-col sm:flex-row gap-3">
        
        <input name="nombre" required maxlength="100" placeholder="Nombre de la nueva categoría" class="flex-1 rounded-lg border-gray-300">
        <button class="bg-[#B8500C] text-white px-5 py-2.5 rounded-lg text-sm font-semibold">Agregar categoría</button>
    </form>

    <div class="bg-white rounded-xl border shadow-sm divide-y">
        @foreach ($categorias as $categoria)
        <div class="p-4 flex flex-col sm:flex-row sm:items-center gap-3">
            <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" class="flex-1 flex gap-3">
                
                @method('PUT')

                <input name="nombre" value="{{ $categoria->nombre }}" required maxlength="100" class="flex-1 rounded-lg border-gray-300">

                <button class="text-sm font-semibold text-[#B8500C] px-3">Guardar</button>

            </form>

            <span class="text-xs text-gray-500">{{ $categoria->libros_count }} libros</span>
                
                {{-- ACCIÓN DE DESACTIVAR / ACTIVAR (Sustituye al Delete antiguo) --}}
                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" 
                      onsubmit="return confirm('¿Seguro que deseas {{ $categoria->activo ? 'desactivar' : 'activar' }} esta categoría?')">
                    
                    @method('DELETE')
                    
                    @if($categoria->activo)
                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">
                            Desactivar
                        </button>
                    @else
                        <button type="submit" class="text-sm font-semibold text-green-600 hover:text-green-800 transition-colors">
                            Activar
                        </button>
                    @endif
                </form>
            </div>
        @endforeach
    </div>
    {{ $categorias->links() }}
</div>
@endsection