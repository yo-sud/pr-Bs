@extends('layouts.admin')

@section('contenido')
<div class="space-y-6 px-6 max-w-5xl mx-auto">

    {{-- Barra de Progreso --}}
    <div class="bg-white p-4 border rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">1</span>
            <span class="text-sm font-medium text-gray-500">Inventario</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">2</span>
            <span class="font-serif font-semibold text-amber-900 text-sm">Proveedores</span>
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

    {{-- Tarjeta de Libros Seleccionados (Resumen) --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="p-2 bg-amber-100 rounded-xl text-amber-700">
            <span class="material-symbols-outlined text-xl">deployed_code</span>
        </div>
        <div>
            <span class="text-xs font-bold text-amber-600 block uppercase tracking-wider">Libros seleccionados</span>
            <span class="text-sm font-extrabold text-amber-900">
                {{ $resumen['titulos'] }} títulos · {{ $resumen['unidades'] }} unidades
            </span>
        </div>
    </div>

    {{-- Formulario Principal hacia el Paso 3 --}}
    <form action="{{ route('admin.reposicion.procesarpaso2') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Grid de Proveedores (2 Columnas) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($proveedores as $prov)
                @php
                    $dias = $prov->tiempo_entrega_dias;
                    $velocidad = $dias <= 3 ? 'Muy rápido' : ($dias <= 6 ? 'Rápido' : ($dias <= 9 ? 'Moderado' : 'Estándar'));
                @endphp
                <label class="relative bg-white border border-gray-200 rounded-2xl p-5 block cursor-pointer hover:shadow-md transition-all select-none has-[:checked]:border-amber-400 has-[:checked]:ring-2 has-[:checked]:ring-amber-100">

                    {{-- Radio button --}}
                    <input type="radio" name="proveedor_id" value="{{ $prov->id }}" class="absolute top-5 right-5 w-5 h-5 accent-[#B8500C]" required>

                    {{-- Nombre del proveedor --}}
                    <div class="pr-8 mb-4">
                        <h3 class="font-serif font-semibold text-amber-900 text-lg leading-tight">{{ $prov->nombre_empresa }}</h3>
                    </div>

                    {{-- Bloques de datos --}}
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Tiempo de entrega --}}
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-blue-500 tracking-wide mb-2">
                                <span class="material-symbols-outlined text-[14px] text-blue-500">schedule</span>
                                Tiempo de<br>Entrega
                            </span>
                            <span class="text-2xl font-extrabold text-gray-900 block">{{ $prov->tiempo_entrega_dias }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">{{ $velocidad }} · días hábiles</span>
                        </div>

                        {{-- Costo de envío --}}
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-purple-500 tracking-wide mb-2">
                                <span class="material-symbols-outlined text-[14px] text-purple-500">local_shipping</span>
                                Costo de<br>Envío
                            </span>
                            <span class="text-2xl font-extrabold text-gray-900 block">S/ {{ number_format($prov->costo_envio, 0) }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Por pedido completo</span>
                        </div>
                    </div>

                    {{-- MOQ --}}
                    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-1.5 text-[10px] font-extrabold text-gray-700 uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[14px] text-amber-600">inventory_2</span>
                                Mínimo de Compra (MOQ)
                            </div>
                            <span class="text-[10px] font-semibold text-amber-600 block mt-0.5">Por libro individual</span>
                        </div>
                        <span class="text-2xl font-extrabold text-amber-700">10</span>
                    </div>

                    {{-- Confiabilidad --}}
                    <div class="mt-4 flex items-center justify-between text-[11px] font-medium text-gray-400">
                        <span>Confiabilidad</span>
                        <div class="flex items-center gap-2">
                            <div class="w-28 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full" style="width: 92%"></div>
                            </div>
                            <span class="font-bold text-emerald-600">92%</span>
                        </div>
                    </div>

                </label>
            @endforeach
        </div>

        {{-- Botones de Navegación --}}
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <a href="{{ route('admin.reposicion.paso1') }}" class="bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                ← Atrás
            </a>
            <button type="submit" class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
                Siguiente Paso ➔
            </button>
        </div>
    </form>
</div>
@endsection
