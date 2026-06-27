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
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">2</span>
            <span class="text-sm font-medium text-gray-500">Proveedores</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs">3</span>
            <span class="text-sm font-medium text-gray-500">Optimización Inteligente</span>
        </div>
        <div class="h-0.5 bg-gray-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">4</span>
            <span class="font-serif font-semibold text-amber-900 text-sm">Resumen</span>
        </div>
    </div>

    {{-- Banner superior naranja --}}
    <div class="bg-gradient-to-r from-[#FF6B00] to-[#E05000] rounded-2xl p-6 text-white flex flex-col md:flex-row md:items-center md:justify-evenly gap-4 md:gap-0 shadow-md">

        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl text-white [font-variation-settings:'FILL'_1]">attach_money</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-orange-100 tracking-widest block">Inversión Total</span>
                <span class="text-2xl font-sans font-black tabular-nums block leading-tight">S/ {{ number_format($resumenFinal['inversion_total'], 2) }}</span>
                <span class="text-xs text-orange-100/80 block mt-0.5">Incluye envío: S/ {{ number_format($resumenFinal['costo_envio'], 2) }}</span>
            </div>
        </div>

        <div class="flex items-center gap-4 md:border-x md:border-white/20 md:px-8">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl text-white [font-variation-settings:'FILL'_1]">calendar_month</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-orange-100 tracking-widest block">Fecha Estimada de Entrega</span>
                <span class="text-2xl font-sans font-black tabular-nums block leading-tight">{{ $resumenFinal['fecha_entrega'] }}</span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-2xl text-white [font-variation-settings:'FILL'_1]">deployed_code</span>
            </div>
            <div>
                <span class="text-xs uppercase font-semibold text-orange-100 tracking-widest block">Total de Unidades</span>
                <span class="text-2xl font-sans font-black tabular-nums block leading-tight">{{ $resumenFinal['total_unidades'] }}</span>
                <span class="text-xs text-orange-100/80 block mt-0.5">{{ $resumenFinal['total_titulos'] }} {{ $resumenFinal['total_titulos'] === 1 ? 'título' : 'títulos' }}</span>
            </div>
        </div>

    </div>

    {{-- Sección central --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Órdenes por Proveedor --}}
        <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="material-symbols-outlined text-xl text-amber-600">local_shipping</span>
                <h3 class="font-serif font-semibold text-amber-900 text-base">Órdenes por Proveedor</h3>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-800 text-sm">{{ $resumenFinal['proveedor_nombre'] }}</span>
                    <span class="text-xs text-gray-500">{{ $resumenFinal['total_unidades'] }} unidades</span>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500 pt-2 border-t border-gray-100">
                    <span>Costo de libros</span>
                    <span class="font-bold text-gray-800">S/ {{ number_format($resumenFinal['costo_libros'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>Estado</span>
                    <span class="border border-amber-400 text-amber-700 px-2.5 py-1 rounded-full font-semibold text-[10px] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[11px]">schedule</span> Pendiente de envío
                    </span>
                </div>
            </div>
        </div>

        {{-- Libros Seleccionados --}}
        <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="material-symbols-outlined text-xl text-amber-600 [font-variation-settings:'FILL'_1]">description</span>
                <h3 class="font-serif font-semibold text-amber-900 text-base">Libros Seleccionados</h3>
            </div>

            <div class="divide-y divide-gray-100 max-h-52 overflow-y-auto pr-1">
                @foreach($resumenFinal['libros'] as $item)
                    <div class="py-3 flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $item['titulo'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item['autor'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-bold text-gray-700 text-sm">×{{ $item['cantidad'] }}</p>
                            <p class="text-xs text-gray-400">S/ {{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Estrategia seleccionada --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-600 text-xl mt-0.5 flex-shrink-0">insights</span>
        <div>
            <p class="font-serif font-semibold text-amber-900 text-sm mb-0.5">Estrategia seleccionada: {{ $resumenFinal['estrategia_texto'] }}</p>
            <p class="text-xs text-amber-700 leading-relaxed">Esta optimización garantiza el mejor balance entre costo y tiempo de entrega, cumpliendo con los requisitos mínimos de compra (MoQ) de cada proveedor.</p>
        </div>
    </div>

    {{-- Confirmación --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 space-y-3">
        <div class="flex items-center gap-2.5">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[14px] [font-variation-settings:'FILL'_1]">check</span>
            </span>
            <h4 class="font-serif font-semibold text-amber-900 text-sm">¿Listo para generar las órdenes?</h4>
        </div>
        <p class="text-xs text-amber-700 leading-relaxed">
            Al confirmar, se generarán automáticamente las órdenes de compra para los proveedores seleccionados. Los productos se marcarán como <span class="font-bold text-amber-900">"En Tránsito"</span> en el inventario general.
        </p>
        <div class="flex flex-wrap gap-4 text-[10px] font-bold text-amber-800 pt-1">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-amber-600">check_circle</span> Órdenes generadas</span>
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-amber-600">check_circle</span> Proveedores notificados</span>
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-amber-600">check_circle</span> Inventario actualizado</span>
        </div>
    </div>

    {{-- Formulario final --}}
    <form action="{{ route('admin.reposicion.confirmar') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Datos de la Orden --}}
        <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="material-symbols-outlined text-xl text-amber-600">receipt_long</span>
                <h3 class="font-serif font-semibold text-amber-900 text-base">Datos de la Orden</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Número de orden --}}
                <div>
                    <label class="block text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1.5">
                        N° de Orden de Compra
                    </label>
                    <input type="text" name="numero_orden"
                           value="OC-{{ date('Y') }}-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}"
                           class="w-full border border-amber-200 rounded-xl px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-300 font-mono tracking-wider">
                </div>

                {{-- Método de pago --}}
                <div>
                    <label class="block text-xs font-semibold text-amber-700 uppercase tracking-wide mb-1.5">
                        Método de Pago al Proveedor
                    </label>
                    <select name="metodo_pago"
                            class="w-full border border-amber-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white">
                        <option value="transferencia">Transferencia bancaria</option>
                        <option value="credito_30">Crédito a 30 días</option>
                        <option value="credito_60">Crédito a 60 días</option>
                        <option value="efectivo">Efectivo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center pt-2 border-t border-amber-100">
            <a href="{{ route('admin.reposicion.paso3') }}" class="bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 px-5 py-2.5 rounded-xl font-semibold text-sm transition-colors">
                ← Atrás
            </a>
            <button type="submit" class="bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-2.5 rounded-xl font-semibold text-sm shadow-md transition-colors flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-base">shopping_cart</span>
                Confirmar y Generar Órdenes de Compra
            </button>
        </div>
    </form>

</div>
@endsection
