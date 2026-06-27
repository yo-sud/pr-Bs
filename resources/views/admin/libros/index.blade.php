@extends('layouts.admin')

@section('title', 'Libros - Administración')

@section('mainClass', 'bg-white')

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
                            <div class="inline-flex items-center gap-3">
                                <button type="button"
                                    onclick="verLibro(this)"
                                    data-titulo="{{ $libro->titulo }}"
                                    data-autor="{{ $libro->autor }}"
                                    data-categoria="{{ $libro->categoria->nombre }}"
                                    data-precio="{{ number_format((float) $libro->precio, 2) }}"
                                    data-stock="{{ $libro->stock }}"
                                    data-editorial="{{ $libro->editorial ?? '' }}"
                                    data-anio="{{ $libro->fecha_publicacion?->format('Y') ?? '' }}"
                                    data-isbn="{{ $libro->isbn ?? '' }}"
                                    data-descripcion="{{ $libro->descripcion ?? '' }}"
                                    data-portada="{{ $libro->portada_url }}"
                                    class="text-stone-400 hover:text-amber-700 transition-colors" title="Ver detalle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <a href="{{ route('admin.libros.edit', $libro) }}"
                                   class="inline-flex items-center text-[#B8500C] hover:text-[#963F07] transition-colors"
                                   title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
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

{{-- Modal Detalle del Libro --}}
<div id="modalLibro" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between bg-[#B8500C] px-6 py-4">
            <h3 class="text-white font-bold text-lg">Detalle del Libro</h3>
            <button onclick="cerrarModal()" class="text-white/80 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        {{-- Body --}}
        <div class="p-6 flex gap-5">
            <img id="ml-portada" src="" alt="Portada" class="w-28 h-40 object-cover rounded-lg shadow-md flex-shrink-0">
            <div class="flex-1 min-w-0">
                <h4 id="ml-titulo" class="text-xl font-bold text-[#B8500C] leading-tight mb-1"></h4>
                <p id="ml-autor" class="text-stone-600 mb-2"></p>
                <span id="ml-categoria" class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full mb-4"></span>
                <dl class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1.5 text-sm">
                    <dt class="text-stone-400 font-medium">Precio</dt>
                    <dd id="ml-precio" class="text-stone-800 font-semibold"></dd>
                    <dt class="text-stone-400 font-medium">Stock</dt>
                    <dd id="ml-stock" class="text-stone-800"></dd>
                    <dt id="ml-editorial-label" class="text-stone-400 font-medium">Editorial</dt>
                    <dd id="ml-editorial" class="text-stone-800"></dd>
                    <dt id="ml-anio-label" class="text-stone-400 font-medium">Año</dt>
                    <dd id="ml-anio" class="text-stone-800"></dd>
                    <dt id="ml-isbn-label" class="text-stone-400 font-medium">ISBN</dt>
                    <dd id="ml-isbn" class="text-stone-800"></dd>
                </dl>
                <p id="ml-descripcion" class="mt-3 text-sm text-stone-600 leading-relaxed"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verLibro(btn) {
    const d = btn.dataset;
    document.getElementById('ml-portada').src    = d.portada;
    document.getElementById('ml-titulo').textContent    = d.titulo;
    document.getElementById('ml-autor').textContent     = d.autor;
    document.getElementById('ml-categoria').textContent = d.categoria;
    document.getElementById('ml-precio').textContent    = 'S/ ' + d.precio;
    document.getElementById('ml-stock').textContent     = d.stock + ' unidades';
    document.getElementById('ml-descripcion').textContent = d.descripcion;

    const setFila = (labelId, valueId, value) => {
        const show = value && value.trim() !== '';
        document.getElementById(labelId).classList.toggle('hidden', !show);
        document.getElementById(valueId).classList.toggle('hidden', !show);
        document.getElementById(valueId).textContent = value;
    };
    setFila('ml-editorial-label', 'ml-editorial', d.editorial);
    setFila('ml-anio-label',      'ml-anio',      d.anio);
    setFila('ml-isbn-label',      'ml-isbn',      d.isbn);

    const modal = document.getElementById('modalLibro');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function cerrarModal() {
    const modal = document.getElementById('modalLibro');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('modalLibro').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endpush
@endsection