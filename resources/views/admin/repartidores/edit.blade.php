@extends('layouts.admin')

@section('title', 'Editar Empresa de Reparto - BookShop')

@section('contenido')
<div class="max-w-2xl mx-auto pt-8 pb-16 px-4">
    
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        
        {{-- Encabezado con título adaptado --}}
        <div class="bg-[#B8500C] px-6 py-4 flex items-center justify-between">
            <h3 class="text-white font-serif font-semibold text-lg">Editar Repartidor</h3>
            <a href="{{ route('admin.repartidores.index') }}" class="text-white/80 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-b border-red-200 p-4 text-xs text-red-600">
                <p class="font-bold mb-1">Por favor corrige los siguientes campos:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario apuntando a Update con método PUT --}}
        <form action="{{ route('admin.repartidores.update', $repartidor->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Campo: Nombre de la Empresa --}}
            <div class="space-y-1.5">
                <label for="nombre_empresa" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Nombre de la Empresa / Razón Social <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_empresa" id="nombre_empresa" value="{{ old('nombre_empresa', $repartidor->nombre_empresa) }}" required
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
            </div>

            {{-- Campo: Contacto / Encargado --}}
            <div class="space-y-1.5">
                <label for="contacto_ejecutivo" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Persona de Contacto / Encargado
                </label>
                <input type="text" name="contacto_ejecutivo" id="contacto_ejecutivo" value="{{ old('contacto_ejecutivo', $repartidor->contacto_ejecutivo) }}"
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
            </div>

            {{-- Fila: RUC y Teléfono --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="ruc" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                        Número de RUC
                    </label>
                    <input type="text" name="ruc" id="ruc" value="{{ old('ruc', $repartidor->ruc) }}" maxlength="11"
                           class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
                </div>

                <div class="space-y-1.5">
                    <label for="telefono" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $repartidor->telefono) }}" required
                           class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
                </div>
            </div>

            {{-- Campo: Correo Electrónico --}}
            <div class="space-y-1.5">
                <label for="correo" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email" name="correo" id="correo" value="{{ old('correo', $repartidor->correo) }}" required
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
            </div>

            {{-- Campo: Zona de Reparto --}}
            <div class="space-y-1.5">
                <label for="tiempo_entrega_estimado" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Zona de Reparto <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tiempo_entrega_estimado" id="tiempo_entrega_estimado" value="{{ old('tiempo_entrega_estimado', $repartidor->tiempo_entrega_estimado) }}" required
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">
            </div>

            {{-- Campo: Observaciones --}}
            <div class="space-y-1.5">
                <label for="observaciones" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Observaciones o Notas adicionales
                </label>
                <textarea name="observaciones" id="observaciones" rows="3"
                          class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all">{{ old('observaciones', $repartidor->observaciones) }}</textarea>
            </div>

            {{-- 🟢 AQUÍ CAMBIA: El Estado ahora es totalmente editable --}}
            <div class="space-y-1.5">
                <label for="activo" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select name="activo" id="activo" required
                        class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all cursor-pointer">
                    <option value="1" {{ old('activo', $repartidor->activo) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo', $repartidor->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
                <p class="text-[11px] text-stone-400">Si cambias el estado a Inactivo, el repartidor dejará de figurar como elegible en los nuevos pedidos.</p>
            </div>

            {{-- Botonera --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                <a href="{{ route('admin.repartidores.index') }}" 
                   class="border border-stone-200 text-stone-700 hover:bg-stone-50 transition-colors px-6 py-2.5 rounded-xl text-sm font-semibold">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-[#B8500C] hover:bg-[#963F07] transition-all text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.5" />
                    </svg>
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection