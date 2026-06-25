@extends('layouts.admin')

@section('contenido')
<div class="space-y-6 pt-20 px-6">

    {{-- Encabezado de progreso --}}
    <div class="bg-white p-4 border rounded-xl flex items-center justify-between max-w-3xl mx-auto shadow-sm">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">1</span>
            <span class="text-sm font-bold text-gray-800">Selección de Inventario</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-4"></div>
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">2</span>
            <span class="text-sm font-medium text-gray-500">Proveedores</span>
        </div>
    </div>

    {{-- Título y descripción --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Paso 1: Selección de Inventario</h2>
        <p class="text-sm text-gray-500">Elige qué libros reponer y define sus cantidades de compra.</p>
    </div>

    {{-- Indicadores superiores --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 border rounded-xl">
            <p class="text-xs text-gray-400 font-medium uppercase">Total Libros</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalLibros }}</p>
        </div>
        <div class="bg-white p-4 border rounded-xl">
            <p class="text-xs text-gray-400 font-medium uppercase">Seleccionados</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalSeleccionados }}</p>
        </div>
        <div class="bg-white p-4 border rounded-xl bg-amber-50/50 border-amber-200">
            <p class="text-xs text-amber-600 font-medium uppercase">Inversión Estimada</p>
            <p class="text-xl font-bold text-amber-800">S/ {{ number_format($inversionEstimada, 2) }}</p>
        </div>
    </div>

    {{-- Formulario hacia el controlador --}}
    <form action="{{ route('admin.reposicion.procesarPaso1') }}" method="POST" class="space-y-3">
        

        <div class="bg-white rounded-xl border divide-y shadow-sm">
            @foreach($libros as $libro)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                    
                    {{-- Checkbox e info básica --}}
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="libros[]" value="{{ $libro->id }}" class="rounded border-gray-300 text-[#FF6B00] focus:ring-[#FF6B00] w-5 h-5">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $libro->titulo }}</p>
                            <p class="text-xs text-gray-400">{{ $libro->autor }}</p>
                        </div>
                    </div>

                    {{-- Semáforos de stock, ventas diarias e inputs --}}
                    <div class="flex items-center gap-6">
                        
                        {{-- 🚦 LÓGICA DE COLORES DEL SEMÁFORO --}}
                        @if($libro->stock == 0)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">● Stock: {{ $libro->stock }}</span>
                        @elseif($libro->stock <= 15)
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">● Stock: {{ $libro->stock }}</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">● Stock: {{ $libro->stock }}</span>
                        @endif

                        {{-- Ventas calculadas desde los pedidos --}}
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 block uppercase font-bold">Ventas/Día</span>
                            <span class="text-xs font-bold text-gray-600">{{ $libro->ventas_diarias }} unds</span>
                        </div>

                        {{-- Cantidad individual --}}
                        <input type="number" name="cantidades[{{ $libro->id }}]" value="0" min="0" class="w-16 p-1.5 text-center text-xs font-bold border rounded-lg border-gray-300 focus:border-[#FF6B00] focus:ring-[#FF6B00]">
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Botonera --}}
        <div class="flex justify-end pt-2">
            <button type="submit" class="bg-[#FF6B00] text-white px-5 py-2 rounded-xl font-bold text-xs shadow-sm hover:bg-[#E05E00] transition-colors">
                Siguiente Paso ➔
            </button>
        </div>
    </form>
</div>
@endsection