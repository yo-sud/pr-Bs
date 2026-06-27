@extends('layouts.admin')

@section('contenido')
<div class="space-y-6 px-6 max-w-5xl mx-auto">
    <div class="bg-white p-4 border border-stone-200 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2 opacity-40">
            <span class="w-7 h-7 rounded-full bg-stone-200 text-stone-600 flex items-center justify-center font-bold text-xs">1</span>
            <span class="text-sm font-medium text-stone-500">Inventario</span>
        </div>
        <div class="h-0.5 bg-stone-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs">2</span>
            <span class="font-serif font-semibold text-amber-900 text-sm">Proveedores</span>
        </div>
        <div class="h-0.5 bg-stone-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-stone-200 text-stone-600 flex items-center justify-center font-bold text-xs">3</span>
            <span class="text-sm font-medium text-stone-500">Optimización Inteligente</span>
        </div>
        <div class="h-0.5 bg-stone-200 flex-1 mx-2"></div>
        <div class="flex items-center gap-2 opacity-30">
            <span class="w-7 h-7 rounded-full bg-stone-200 text-stone-600 flex items-center justify-center font-bold text-xs">4</span>
            <span class="text-sm font-medium text-stone-500">Resumen</span>
        </div>
    </div>

    {{-- libros seleccionados tarjeta pequeñ--}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="p-2 bg-amber-100 rounded-xl text-amber-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-2 w-5 h-5"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
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
                    
                    $confiabilidad = $prov->confiabilidad ?? 98; 
                @endphp
                
                <label class="relative bg-white border border-stone-200 rounded-2xl p-6 block cursor-pointer hover:shadow-md transition-all select-none has-[:checked]:border-amber-400 has-[:checked]:ring-2 has-[:checked]:ring-amber-100">

                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 pr-6">
                            <h3 class="font-serif text-lg font-semibold text-stone-800 mb-1 leading-tight">{{ $prov->nombre_empresa }}</h3>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    {{-- Estrellas de puntuación estéticas --}}
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <svg class="w-3.5 h-3.5 text-stone-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <span class="text-xs text-stone-500 font-medium">4.8</span>
                            </div>
                        </div>
                        
                        {{-- Input Radio --}}
                        <input type="radio" name="proveedor_id" value="{{ $prov->id }}" class="w-5 h-5 accent-amber-700 cursor-pointer mt-1" required>
                    </div>

                    {{-- Bloques de datos --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        {{-- Tiempo de entrega --}}
                        <div class="bg-white rounded-xl border border-stone-100 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-4 h-4 text-sky-600"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span class="text-[10px] font-semibold text-stone-500 uppercase tracking-wider">Tiempo de entrega</span>
                            </div>
                            <p class="text-xl font-bold text-stone-800">{{ $prov->tiempo_entrega_dias }}</p>
                            <p class="text-xs text-stone-400">{{ $velocidad }} · días hábiles</p>
                        </div>

                        {{-- Costo de envío --}}
                        <div class="bg-white rounded-xl border border-stone-100 p-3">
                            <div class="flex items-center gap-2 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck w-4 h-4 text-violet-600"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg>
                                <span class="text-[10px] font-semibold text-stone-500 uppercase tracking-wider">Costo de envío</span>
                            </div>
                            <p class="text-xl font-bold text-stone-800">S/ {{ number_format($prov->costo_envio, 0) }}</p>
                            <p class="text-xs text-stone-400">Por pedido completo</p>
                        </div>
                    </div>

                    {{-- MOQ --}}
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-4 h-4 text-amber-600"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><path d="m7.5 4.27 9 5.15"></path></svg>
                                <div>
                                    <p class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider">Mínimo de Compra (MoQ)</p>
                                    <p class="text-xs text-amber-600">Por libro individual</p>
                                </div>
                            </div>
                            {{-- Usa la propiedad MOQ real del proveedor o un fallback si es nulo --}}
                            <span class="text-2xl font-bold text-amber-900">{{ $prov->moq ?? '10' }}</span>
                        </div>
                    </div>

                </label>
            @endforeach
        </div>

        {{-- Botones de Navegación --}}
        <div class="flex justify-between items-center pt-4 border-t border-stone-100">
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