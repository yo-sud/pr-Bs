@extends('layouts.admin')

@section('contenido')
<div class="space-y-6 pt-20 px-6 max-w-5xl mx-auto">

    {{-- Encabezado e Instrucción --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Paso 2: Condiciones de Proveedores</h2>
        <p class="text-sm text-gray-500">Revisa las condiciones y selecciona los proveedores para tu pedido.</p>
    </div>

    {{-- Tarjeta de Libros Seleccionados (Resumen) --}}
    <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-4 flex items-center gap-3">
        <div class="p-2 bg-indigo-100 rounded-xl text-indigo-600">
            <span class="material-symbols-outlined text-xl">deployed_code</span>
        </div>
        <div>
            <span class="text-xs font-bold text-indigo-500 block uppercase tracking-wider">Libros seleccionados</span>
            <span class="text-sm font-extrabold text-indigo-900">
                {{ $resumen['titulos'] }} títulos · {{ $resumen['unidades'] }} unidades
            </span>
        </div>
    </div>

    {{-- Formulario Principal hacia el Paso 3 --}}
    <form action="#" method="POST" class="space-y-6">
        @csrf

        {{-- Grid de Proveedores (2 Columnas) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($proveedores as $prov)
                <label class="relative bg-white border border-gray-200 rounded-2xl p-5 block cursor-pointer hover:shadow-md transition-all group select-none">
                    
                    {{-- Input de selección oculto pero funcional --}}
                    <input type="radio" name="proveedor_id" value="{{ $prov->id }}" class="absolute top-5 right-5 w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500 accent-indigo-600" required>

                    {{-- Nombre y Estrellas simuladas del diseño --}}
                    <div>
                        <h3 class="font-bold text-gray-800 text-base group-hover:text-indigo-600 transition-colors">{{ $prov->nombre_empresa }}</h3>
                        <div class="flex items-center gap-1 text-amber-400 text-xs mt-0.5">
                            <span>★★★★★</span>
                            <span class="text-gray-400 font-bold ml-1">4.8</span>
                        </div>
                    </div>

                    {{-- Bloque de Tiempo de Entrega y Costo de Envío --}}
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        {{-- Tiempo --}}
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <span class="text-[9px] uppercase font-bold text-gray-400 block tracking-wide">⏱ Tiempo de Entrega</span>
                            <span class="text-lg font-extrabold text-gray-800 block mt-0.5">{{ $prov->tiempo_entrega_dias }}</span>
                            <span class="text-[10px] text-gray-400 block">Muy rápido · días hábiles</span>
                        </div>
                        {{-- Costo --}}
                        <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100">
                            <span class="text-[9px] uppercase font-bold text-gray-400 block tracking-wide">📦 Costo de Envío</span>
                            <span class="text-lg font-extrabold text-gray-800 block mt-0.5">S/ {{ number_format($prov->costo_envio, 2) }}</span>
                            <span class="text-[10px] text-gray-400 block">Por pedido completo</span>
                        </div>
                    </div>

                    {{-- Alerta de Mínimo de Compra (MOQ) --}}
                    <div class="mt-3 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-amber-700 text-[10px] font-bold uppercase">
                            <span>📦</span> Mínimo de Compra (MOQ)
                        </div>
                        <span class="text-sm font-extrabold text-amber-800">10</span>
                    </div>

                    {{-- Barra de Confiabilidad --}}
                    <div class="mt-4 flex items-center justify-between text-[11px] font-medium text-gray-400">
                        <span>Confiabilidad</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: 92%"></div>
                            </div>
                            <span class="font-bold text-emerald-600">92%</span>
                        </div>
                    </div>

                </label>
            @endforeach
        </div>

        {{-- Botones Inferiores de Navegación --}}
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <a href="{{ route('admin.reposicion.paso1') }}" class="bg-gray-100 text-gray-600 hover:bg-gray-200 px-5 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-1">
                ➔ Atrás
            </a>
            <button type="submit" class="bg-gray-300 text-gray-500 px-6 py-2.5 rounded-xl font-bold text-xs shadow-sm cursor-not-allowed select-none">
                Siguiente ➔
            </button>
        </div>
    </form>
</div>
@endsection