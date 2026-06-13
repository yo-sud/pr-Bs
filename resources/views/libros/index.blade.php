@extends('layouts.app')

@section('title', 'BookShop - Todos los Libros')

@section('content')
<main class="px-[7%] py-12 flex-grow bg-[#FDFBF7]">
    {{-- ENCABEZADO DE LA PÁGINA --}}
    <div class="border-b border-[#6E7E80]/10 pb-6 mb-8">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-[#421605] mb-2">Todos los Libros</h1>
        <p class="text-sm text-[#8A7A71]">Explora el catálogo, busca por título, autor o ISBN y filtra por categoría.</p>
    </div>

    {{-- CONTENEDOR DE FILTROS UNIFICADO --}}
    <div class="mb-10">
        
        {{-- BARRA SUPERIOR: BOTÓN DISPARADOR (IZQUIERDA) Y ORDENAMIENTO (DERECHA) --}}
        <div class="flex items-center justify-between gap-4 mb-4">
            
            {{-- Botón Filtros (Lado Izquierdo) --}}
            <button type="button" id="btn-toggle-filtros" class="inline-flex items-center gap-2 px-4 py-2 border border-[#FFC107] hover:bg-[#FFC107]/5 rounded-xl text-sm font-medium text-[#421605] transition-all shadow-sm group">
                <svg id="icon-filtro" class="w-4 h-4 text-[#421605] transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"></path>
                </svg>
                <span>Filtros</span>
            </button>

            {{-- Selector Destacados / Ordenar (Lado Derecho) --}}
            <div class="relative min-w-[160px]">
                <form action="{{ route('libros.index') }}" method="GET" id="form-ordenar">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('categoria')) <input type="hidden" name="categoria" value="{{ request('categoria') }}"> @endif
                    
                    <select name="orden" onchange="document.getElementById('form-ordenar').submit()" class="appearance-none w-full px-4 py-2 pr-10 border border-[#FFC107] rounded-xl text-sm font-medium text-[#421605] focus:outline-none focus:ring-2 focus:ring-[#FFC107]/20 bg-white cursor-pointer">
                        <option value="destacados" @selected(request('orden', 'destacados') === 'destacados')>Destacados</option>
                        <option value="recientes" @selected(request('orden') === 'recientes')>Más recientes</option>
                        <option value="precio_asc" @selected(request('orden') === 'precio_asc')>Menor precio</option>
                        <option value="precio_desc" @selected(request('orden') === 'precio_desc')>Mayor precio</option>
                        <option value="titulo" @selected(request('orden') === 'titulo')>Título A-Z</option>
                    </select>
                    
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#421605]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </form>
            </div>
        </div>

        {{-- PANEL DESPLEGABLE CON LOS FILTROS INTERNOS --}}
        <form action="{{ route('libros.index') }}" method="GET" id="panel-filtros" class="{{ request()->has('search') || request()->has('categoria') ? '' : 'hidden' }} bg-white border border-[#421605]/10 rounded-2xl p-6 shadow-sm space-y-5 transition-all duration-300">
            @if(request('orden')) <input type="hidden" name="orden" value="{{ request('orden') }}"> @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Campo Buscar --}}
                <div>
                    <label for="search" class="block text-xs font-semibold text-[#554138] mb-1.5">Buscar</label>
                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Título, autor o ISBN"
                        class="w-full rounded-xl border-[#421605]/15 text-sm focus:border-[#B8500C] focus:ring-[#B8500C] bg-[#FDFBF7]/30"
                    >
                </div>

                {{-- Campo Categoría --}}
                <div>
                    <label for="categoria" class="block text-xs font-semibold text-[#554138] mb-1.5">Categoría</label>
                    <div class="relative">
                        <select id="categoria" name="categoria" class="appearance-none w-full px-4 py-2.5 pr-10 rounded-xl border border-[#421605]/15 text-sm focus:border-[#B8500C] focus:ring-[#B8500C] bg-[#FDFBF7]/30 cursor-pointer">
                            <option value="">Todas</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected((string) request('categoria') === (string) $categoria->id)>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BARRA INFERIOR DEL PANEL DESPLEGABLE --}}
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#421605]/5">
                <p class="text-xs text-[#8A7A71]">
                    {{ $libros->total() }} {{ $libros->total() === 1 ? 'libro encontrado' : 'libros encontrados' }}
                </p>
                <div class="flex gap-2">
                    @if (request()->hasAny(['search', 'categoria']))
                        <a href="{{ route('libros.index') }}" class="px-4 py-2 rounded-full text-xs font-semibold text-[#554138] hover:bg-[#F3ECE0] transition-colors flex items-center">
                            Limpiar filtros
                        </a>
                    @endif
                    <button type="submit" class="px-5 py-2 rounded-full bg-[#B8500C] hover:bg-[#963F07] text-white text-xs font-semibold transition-colors shadow-sm">
                        Aplicar filtros
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- LISTADO DE LIBROS / RESULTADOS --}}
    @if ($libros->isEmpty())
        <div class="bg-white border border-[#421605]/10 rounded-2xl py-16 px-6 text-center">
            <h2 class="font-serif text-2xl font-bold text-[#421605] mb-2">No encontramos libros</h2>
            <p class="text-sm text-[#8A7A71] mb-6">Prueba con otra búsqueda o elimina los filtros actuales.</p>
            <a href="{{ route('libros.index') }}" class="inline-flex bg-[#B8500C] text-white px-6 py-2.5 rounded-full text-sm font-medium">
                Ver todo el catálogo
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($libros as $libro)
                <x-book-card :libro="$libro" />
            @endforeach
        </div>

        <div class="mt-12">
            {{ $libros->links() }}
        </div>
    @endif
</main>

{{-- SCRIPT INTERACTIVO ACTUALIZADO PARA LOS CAMBIOS DE COLOR --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnToggle = document.getElementById('btn-toggle-filtros');
        const panelFiltros = document.getElementById('panel-filtros');
        const iconFiltro = document.getElementById('icon-filtro');

        function actualizarEstiloBoton() {
            if (panelFiltros.classList.contains('hidden')) {
                btnToggle.classList.remove('bg-[#B8500C]', 'text-white', 'border-[#B8500C]');
                btnToggle.classList.add('border-[#FFC107]', 'text-[#421605]');
                iconFiltro.classList.remove('text-white');
                iconFiltro.classList.add('text-[#421605]');
            } else {
                btnToggle.classList.remove('border-[#FFC107]', 'text-[#421605]');
                btnToggle.classList.add('bg-[#B8500C]', 'text-white', 'border-[#B8500C]');
                iconFiltro.classList.remove('text-[#421605]');
                iconFiltro.classList.add('text-white');
            }
        }

        actualizarEstiloBoton();

        btnToggle.addEventListener('click', function () {
            panelFiltros.classList.toggle('hidden');
            actualizarEstiloBoton();
        });
    });
</script>
@endsection