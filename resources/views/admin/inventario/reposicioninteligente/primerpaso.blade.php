@extends('layouts.admin')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="space-y-6">

    {{-- Barra de Progreso --}}
    <div class="bg-white p-4 border rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">1</span>
            <span class="font-serif font-semibold text-amber-900 text-sm">Inventario</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">2</span>
            <span class="text-sm font-medium text-gray-500">Proveedores</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">3</span>
            <span class="text-sm font-medium text-gray-500">Optimización Inteligente</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">4</span>
            <span class="text-sm font-medium text-gray-500">Resumen</span>
        </div>
    </div>

    {{-- Indicadores superiores --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl shadow-sm">
            <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Total Libros</p>
            <p class="text-2xl font-bold text-amber-900 mt-1">{{ $totalLibros }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl shadow-sm">
            <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Seleccionados</p>
            <p class="text-2xl font-bold text-amber-900 mt-1" id="contador-seleccionados">0</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl shadow-sm">
            <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Inversión Estimada</p>
            <p class="text-2xl font-bold text-amber-900 mt-1">S/ {{ number_format($inversionEstimada, 2) }}</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('admin.reposicion.procesarpaso1') }}" method="POST" class="space-y-3">
        @csrf

        @error('libros')
        <p class="text-red-600 text-sm font-medium">Debes seleccionar al menos un libro.</p>
        @enderror

        <div class="bg-white rounded-xl border border-amber-100 divide-y divide-amber-50 shadow-sm overflow-hidden">
            @foreach($libros as $libro)
            <div class="px-5 py-4 flex items-center justify-between hover:bg-amber-50/50 transition-colors">

                {{-- Checkbox e info básica --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="libros[]" value="{{ $libro->id }}"
                        class="checkbox-libro rounded border-amber-300 text-[#B8500C] focus:ring-amber-500 w-5 h-5">
                    <div>
                        <p class="font-semibold text-stone-800 text-sm">{{ $libro->titulo }}</p>
                        <p class="text-xs text-stone-400">{{ $libro->autor }}</p>
                    </div>
                </div>

                {{-- Cantidad a reponer --}}
                <div class="w-28 ml-auto flex items-center gap-1.5">
                    <label class="text-[10px] text-stone-400 uppercase font-semibold tracking-wide">Cant.</label>
                    <input type="number" name="cantidades[{{ $libro->id }}]"
                        value="1" min="1" max="999"
                        class="cantidad-input w-14 border border-amber-200 rounded-lg px-2 py-1 text-sm text-center">
                </div>

                {{-- Stock y ventas/día --}}
                <div class="flex items-center gap-6">

                    @if($libro->stock == 0)
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">● Stock: {{ $libro->stock }}</span>
                    @elseif($libro->stock <= 15)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">● Stock: {{ $libro->stock }}</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">● Stock: {{ $libro->stock }}</span>
                        @endif

                        <div class="text-right">
                            <span class="text-[10px] text-stone-400 block uppercase font-semibold tracking-wide">Ventas/Día</span>
                            <span class="text-xs font-bold text-stone-600">{{ $libro->ventas_diarias }} unds</span>
                        </div>

                </div>

            </div>
            @endforeach
        </div>

        {{-- Botonera --}}
        <div class="flex justify-end pt-2">
            <button type="submit"
                class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
                Siguiente Paso ➔
            </button>
        </div>
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.checkbox-libro');
    const contador = document.getElementById('contador-seleccionados');

    function actualizarContador() {
        contador.textContent = document.querySelectorAll('.checkbox-libro:checked').length;
    }

    function actualizarCantidad(cb) {
        const input = document.querySelector(`input[name="cantidades[${cb.value}]"]`);
        if (!input) return;
        input.disabled = !cb.checked;
        if (!cb.checked) input.value = 1;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            actualizarContador();
            actualizarCantidad(cb);
        });
    });
</script>
@endsection