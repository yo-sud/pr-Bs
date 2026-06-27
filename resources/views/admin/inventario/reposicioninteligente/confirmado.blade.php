@extends('layouts.admin')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="max-w-sm mx-auto px-4 space-y-5">

    {{-- Header cálido --}}
    <div class="bg-gradient-to-b from-[#FF6B00] to-[#C94E00] rounded-3xl p-8 text-white text-center">

        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-4xl text-white [font-variation-settings:'FILL'_1]">workspace_premium</span>
        </div>

        <h1 class="font-serif font-semibold text-2xl leading-tight mb-2">¡Órdenes Generadas!</h1>
        <p class="text-sm text-orange-100">
            <span class="material-symbols-outlined text-[13px] align-middle">auto_awesome</span>
            Los productos están marcados como <span class="font-bold text-white">En Tránsito</span>
        </p>
    </div>

    {{-- Tarjetas de estado --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex flex-col items-center gap-2 text-center">
            <span class="material-symbols-outlined text-3xl text-amber-600 [font-variation-settings:'FILL'_1]">inventory_2</span>
            <span class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Estado</span>
            <span class="font-serif font-semibold text-amber-900 text-base">En Tránsito</span>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 flex flex-col items-center gap-2 text-center">
            <span class="material-symbols-outlined text-3xl text-[#B8500C] [font-variation-settings:'FILL'_1]">local_shipping</span>
            <span class="text-xs font-semibold text-[#B8500C] uppercase tracking-wide">Seguimiento</span>
            <span class="font-serif font-semibold text-amber-900 text-base">Activo</span>
        </div>
    </div>

    {{-- Datos de la orden --}}
    <div class="bg-white border border-amber-100 rounded-2xl p-5 space-y-3">
        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
            <span class="material-symbols-outlined text-amber-600 text-xl">receipt_long</span>
            <h3 class="font-serif font-semibold text-amber-900 text-base">Datos de la Orden</h3>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-500">N° de Orden</span>
            <span class="font-mono font-bold text-gray-800 tracking-wider">{{ $orden['numero_orden'] }}</span>
        </div>
        <div class="flex justify-between items-center text-sm border-t border-gray-100 pt-3">
            <span class="text-gray-500">Método de pago</span>
            <span class="font-semibold text-gray-800">{{ $orden['metodo_pago'] }}</span>
        </div>
    </div>

    {{-- Confirmación exitosa --}}
    <div class="bg-white border border-amber-100 rounded-2xl p-5 space-y-3">
        <div class="flex items-center gap-2 mb-1">
            <span class="material-symbols-outlined text-amber-600 text-xl [font-variation-settings:'FILL'_1]">check_circle</span>
            <h3 class="font-serif font-semibold text-amber-900 text-base">Confirmación Exitosa</h3>
        </div>
        @foreach([
            'Órdenes de compra generadas automáticamente',
            'Proveedores notificados por email',
            'Inventario actualizado con estado "En Tránsito"',
            'Sistema de seguimiento activado',
        ] as $item)
            <div class="flex items-start gap-2.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0 mt-1.5"></span>
                <span class="text-sm text-gray-600">{{ $item }}</span>
            </div>
        @endforeach
    </div>

    {{-- Botones --}}
    <div class="space-y-3 pb-4">
        <a href="{{ route('admin.inventario.index') }}"
           class="w-full bg-[#B8500C] hover:bg-[#963F07] text-white rounded-2xl py-3.5 font-semibold text-sm transition-colors flex items-center justify-center gap-2 shadow-md">
            <span class="material-symbols-outlined text-base [font-variation-settings:'FILL'_1]">check_circle</span>
            Ir al Inventario
        </a>

        <button onclick="window.print()"
                class="w-full bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 rounded-2xl py-3 font-semibold text-sm transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-base">print</span>
            Imprimir / Exportar PDF
        </button>

        <p class="text-center text-xs text-gray-400">Los productos ahora están marcados como "En Tránsito"</p>
    </div>

</div>

{{-- Estilos solo para impresión --}}
<style>
    @media print {
        aside, nav, button, a[href="{{ route('admin.inventario.index') }}"] { display: none !important; }
        body { background: white !important; }
        .max-w-sm { max-width: 100% !important; }
        .rounded-3xl, .rounded-2xl { border-radius: 0 !important; }
        .shadow-md { box-shadow: none !important; }
    }
</style>
@endsection
