@props(['libro', 'badge' => null])

<article class="bg-white rounded-[20px] overflow-hidden border border-[#421605]/10 shadow-[0_8px_20px_rgba(66,22,5,0.04)] flex flex-col group">
    <a href="{{ route('libros.show', $libro) }}" class="block w-full aspect-[4/5] bg-gray-100 overflow-hidden relative">
        @if ($badge)
            <span class="absolute top-3 left-3 bg-[#B8500C] text-white text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full z-10">
                {{ $badge }}
            </span>
        @endif

        @if ($libro->stock === 0)
            <span class="absolute top-3 right-3 bg-[#421605] text-white text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full z-10">
                Agotado
            </span>
        @endif

        <img
            src="{{ $libro->portada_url }}"
            alt="Portada de {{ $libro->titulo }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        >
    </a>

    <div class="p-4 text-left flex flex-col flex-grow">
        <span class="text-[10px] font-medium tracking-wide text-[#C48C45] mb-1">
            {{ $libro->categoria->nombre }}
        </span>
        <a href="{{ route('libros.show', $libro) }}" class="hover:text-[#B8500C] transition-colors">
            <h3 class="font-serif text-sm font-bold text-[#421605] mb-0.5 line-clamp-1">
                {{ $libro->titulo }}
            </h3>
        </a>
        <p class="text-[11px] text-[#8A7A71] mb-4">{{ $libro->autor }}</p>

        <div class="mt-auto flex items-center justify-between gap-3">
            <div>
                <span class="block text-sm font-bold text-[#421605]">S/ {{ number_format((float) $libro->precio, 2) }}</span>
                <span class="text-[10px] {{ $libro->stock > 0 ? 'text-orange-900' : 'text-red-600' }}">
                    {{ $libro->stock > 0 ? $libro->stock.' disponibles' : 'Sin stock' }}
                </span>
            </div>
            @if ($libro->stock > 0)
                <form method="POST" action="{{ route('carrito.store', $libro) }}">
                    @csrf
                    <input type="hidden" name="cantidad" value="1">
<button
    class="w-8 h-8 bg-[#F09200] hover:bg-[#d68200] text-white rounded-full flex items-center justify-center transition-colors"
    aria-label="Agregar {{ $libro->titulo }} al carrito"
>
    {{-- Icono de Carrito de Compras --}}
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
</button>
                </form>
            @endif
        </div>
    </div>
</article>
