@extends('layouts.admin')

@section('contenido')
<div class="space-y-6 px-6 max-w-6xl mx-auto">

    {{-- Barra de Progreso --}}
    <div class="bg-white p-4 border rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">1</span>
            <span class="text-sm font-medium text-gray-500">Inventario</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">2</span>
            <span class="text-sm font-medium text-gray-500">Proveedores</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">3</span>
            <span class="font-serif font-semibold text-amber-900 text-sm">Optimización Inteligente</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">4</span>
            <span class="text-sm font-medium text-gray-500">Resumen</span>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('admin.reposicion.procesarpaso3') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- TARJETA 1: OPCIÓN MÁS RÁPIDA --}}
            <label class="relative bg-white rounded-2xl block cursor-pointer overflow-hidden shadow-sm hover:shadow-lg transition-all select-none border-2 border-transparent has-[:checked]:border-blue-500">
                <input type="radio" name="estrategia" value="rapida" class="absolute top-4 right-4 w-5 h-5 accent-blue-600 z-10" checked>
                <input type="hidden" name="proveedor_id_rapida" value="{{ $opcionRapida['proveedor_id'] }}">

                {{-- Header azul --}}
                <div class="bg-blue-500 p-5 text-white">
                    <div class="flex items-center gap-3 pr-8">
                        <div class="w-10 h-10 bg-blue-400/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-white text-xl [font-variation-settings:'FILL'_1]">bolt</span>
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-lg leading-tight">Opción Más Rápida</h3>
                            <p class="text-xs text-blue-100 mt-0.5">Minimiza el tiempo de entrega</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="bg-blue-400/40 rounded-xl p-3">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-blue-100 tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[12px]">attach_money</span> Inversión Total
                            </span>
                            <span class="text-xl font-black block">S/ {{ number_format($opcionRapida['inversion_total'], 2) }}</span>
                        </div>
                        <div class="bg-blue-400/40 rounded-xl p-3">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-blue-100 tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[12px]">schedule</span> Entrega Promedio
                            </span>
                            <span class="text-xl font-black block">{{ $opcionRapida['entrega_promedio'] }} días</span>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo --}}
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">local_shipping</span> Costo de envío
                        </span>
                        <span class="font-bold text-gray-800">S/ {{ number_format($opcionRapida['costo_envio'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">inventory_2</span> Unidades totales
                        </span>
                        <span class="font-bold text-gray-800">{{ $opcionRapida['unidades_totales'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">target</span> Cumplimiento MoQ
                        </span>
                        <span class="font-extrabold text-emerald-600">100%</span>
                    </div>

                    <div class="pt-1">
                        <span class="text-[9px] uppercase font-bold text-gray-400 tracking-widest block mb-2">Distribución por Proveedor</span>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">{{ $opcionRapida['proveedor_nombre'] }}</span>
                            <span class="text-gray-400">{{ $opcionRapida['unidades_totales'] }} unidades
                                <b class="text-gray-800 ml-1">S/ {{ number_format($opcionRapida['costo_libros'], 2) }}</b>
                            </span>
                        </div>
                    </div>
                </div>
            </label>

            {{-- TARJETA 2: OPCIÓN MÁS ECONÓMICA --}}
            <label class="relative bg-white rounded-2xl block cursor-pointer overflow-hidden shadow-sm hover:shadow-lg transition-all select-none border-2 border-transparent has-[:checked]:border-emerald-500">
                <input type="radio" name="estrategia" value="economica" class="absolute top-4 right-4 w-5 h-5 accent-emerald-600 z-10">
                <input type="hidden" name="proveedor_id_economica" value="{{ $opcionEconomica['proveedor_id'] }}">

                {{-- Header verde --}}
                <div class="bg-emerald-500 p-5 text-white">
                    <div class="flex items-center gap-3 pr-8">
                        <div class="w-10 h-10 bg-emerald-400/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-white text-xl [font-variation-settings:'FILL'_1]">attach_money</span>
                        </div>
                        <div>
                            <h3 class="font-serif font-semibold text-lg leading-tight">Opción Más Económica</h3>
                            <p class="text-xs text-emerald-100 mt-0.5">Optimiza costos de envío y cumple MoQ</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="bg-emerald-400/40 rounded-xl p-3">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-emerald-100 tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[12px]">attach_money</span> Inversión Total
                            </span>
                            <span class="text-xl font-black block">S/ {{ number_format($opcionEconomica['inversion_total'], 2) }}</span>
                        </div>
                        <div class="bg-emerald-400/40 rounded-xl p-3">
                            <span class="flex items-center gap-1 text-[9px] uppercase font-bold text-emerald-100 tracking-wider mb-1">
                                <span class="material-symbols-outlined text-[12px]">schedule</span> Entrega Promedio
                            </span>
                            <span class="text-xl font-black block">{{ $opcionEconomica['entrega_promedio'] }} días</span>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo --}}
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">local_shipping</span> Costo de envío
                        </span>
                        <span class="font-bold text-gray-800">S/ {{ number_format($opcionEconomica['costo_envio'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">inventory_2</span> Unidades totales
                        </span>
                        <span class="font-bold text-gray-800">{{ $opcionEconomica['unidades_totales'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1.5 border-b border-gray-100">
                        <span class="flex items-center gap-2 text-gray-400 font-medium">
                            <span class="material-symbols-outlined text-[15px]">target</span> Cumplimiento MoQ
                        </span>
                        <span class="font-extrabold text-emerald-600">100%</span>
                    </div>

                    <div class="pt-1">
                        <span class="text-[9px] uppercase font-bold text-gray-400 tracking-widest block mb-2">Distribución por Proveedor</span>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">{{ $opcionEconomica['proveedor_nombre'] }}</span>
                            <span class="text-gray-400">{{ $opcionEconomica['unidades_totales'] }} unidades
                                <b class="text-gray-800 ml-1">S/ {{ number_format($opcionEconomica['costo_libros'], 2) }}</b>
                            </span>
                        </div>
                    </div>
                </div>
            </label>

        </div>

        {{-- Recomendación del Sistema --}}
        <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-500 text-xl mt-0.5 flex-shrink-0">leaderboard</span>
            <div>
                <p class="text-sm font-bold text-gray-800 mb-1">Recomendación del Sistema</p>
                <p class="text-xs text-amber-700 leading-relaxed">
                    Si tienes stock crítico (menos de 7 días de inventario), te recomendamos la <span class="font-bold text-amber-700">Opción Más Rápida</span>. Si tu stock es suficiente y buscas optimizar costos, la <span class="font-bold text-amber-700">Opción Más Económica</span> es ideal.
                </p>
            </div>
        </div>

        {{-- Botonera --}}
        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
            <a href="{{ route('admin.reposicion.paso2') }}" class="bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                ← Atrás
            </a>
            <button type="submit" class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
                Siguiente Paso ➔
            </button>
        </div>

    </form>
</div>
@endsection
