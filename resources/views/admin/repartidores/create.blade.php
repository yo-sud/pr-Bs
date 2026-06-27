@extends('layouts.admin')

@section('title', 'Agregar Empresa de Reparto - BookShop')

@section('contenido')
<div class="max-w-2xl mx-auto pt-8 pb-16 px-4">
    
    {{-- Tarjeta Principal del Formulario --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        
        {{-- Encabezado con el estilo exacto del mockup --}}
        <div class="bg-[#B8500C] px-6 py-4 flex items-center justify-between">
            <h3 class="text-white font-serif font-semibold text-lg">Agregar Repartidor</h3>
            <a href="{{ route('admin.repartidores.index') }}" class="text-white/80 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        {{-- Alertas de Errores Manuales de Validación --}}
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

        {{-- Formulario --}}
        <form action="{{ route('admin.repartidores.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Campo: Nombre de la Empresa --}}
            <div class="space-y-1.5">
                <label for="nombre_empresa" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Nombre Completo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre_empresa" id="nombre_empresa" value="{{ old('nombre_empresa') }}" required
                       placeholder="Ej. Servientrega S.A.C."
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
            </div>

            {{-- 📍 AQUÍ ESTÁ EL NUEVO CAMPO: Contacto / Encargado --}}
            <div class="space-y-1.5">
                <label for="contacto_ejecutivo" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Persona de Contacto / Encargado
                </label>
                <input type="text" name="contacto_ejecutivo" id="contacto_ejecutivo" value="{{ old('contacto_ejecutivo') }}"
                       placeholder="Ej. Carlos Mendoza (Gerente de Logística)"
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
            </div>

            {{-- Fila con dos columnas: RUC y Teléfono --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Campo: RUC --}}
                <div class="space-y-1.5">
                    <label for="ruc" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                        Número de RUC
                    </label>
                    <input type="text" name="ruc" id="ruc" value="{{ old('ruc') }}" maxlength="11"
                           placeholder="11 dígitos"
                           class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
                </div>

                {{-- Campo: Teléfono --}}
                <div class="space-y-1.5">
                    <label for="telefono" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required
                           placeholder="Ej: +51 987 654 321"
                           class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
                </div>
            </div>

            {{-- Campo: Correo Electrónico --}}
            <div class="space-y-1.5">
                <label for="correo" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email" name="correo" id="correo" value="{{ old('correo') }}" required
                       placeholder="contacto@empresa.com"
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
            </div>

            {{-- Campo: Ciudad / Zona de Reparto --}}
            <div class="space-y-1.5">
                <label for="ciudad" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Ciudad / Zona de Reparto
                </label>
                <input type="text" name="ciudad" id="ciudad" value="{{ old('ciudad') }}"
                       placeholder="Ej: Lima, Arequipa, Norte, Sur..."
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
            </div>

            {{-- Campo: Tiempo de Entrega Estimado --}}
            <div class="space-y-1.5">
                <label for="tiempo_entrega_estimado" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Tiempo de Entrega Estimado
                </label>
                <input type="text" name="tiempo_entrega_estimado" id="tiempo_entrega_estimado" value="{{ old('tiempo_entrega_estimado') }}"
                       placeholder="Ej: 1-2 días hábiles, 24 horas..."
                       class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">
            </div>

            {{-- Campo: Observaciones --}}
            <div class="space-y-1.5">
                <label for="observaciones" class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Observaciones o Notas adicionales
                </label>
                <textarea name="observaciones" id="observaciones" rows="3"
                          placeholder="Horarios de entrega preferenciales, flota disponible, etc..."
                          class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-4 focus:border-[#B8500C] focus:ring-[#B8500C] transition-all placeholder:text-stone-300">{{ old('observaciones') }}</textarea>
            </div>

            {{-- Campo: Estado (Desplegable Deshabilitado/Fijo al crear) --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide">
                    Estado
                </label>
                <select class="w-full rounded-xl border-gray-300 bg-stone-50 text-stone-500 text-sm py-2.5 px-4 cursor-not-allowed" disabled>
                    <option value="1" selected>Activo</option>
                </select>
                <p class="text-[11px] text-stone-400">Las empresas de reparto nuevas se registran activas por defecto.</p>
            </div>

            {{-- Botonera Inferior con el estilo exacto de la imagen --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                <a href="{{ route('admin.repartidores.index') }}" 
                   class="border border-stone-200 text-stone-700 hover:bg-stone-50 transition-colors px-6 py-2.5 rounded-xl text-sm font-semibold">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-[#B8500C] hover:bg-[#963F07] transition-all text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-md flex items-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>
@endsection