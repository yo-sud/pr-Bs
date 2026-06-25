@extends('layouts.admin')

@section('title', 'Agregar Proveedor')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="max-w-2xl space-y-6">

    <div>
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Agregar Proveedor</h2>
        <p class="text-stone-500 text-sm mt-1">Completa los datos del nuevo proveedor.</p>
    </div>

    <form method="POST" action="{{ route('admin.proveedores.store') }}"
          class="bg-white rounded-xl border border-amber-100 shadow-sm p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Empresa *</label>
                <input name="nombre_empresa" value="{{ old('nombre_empresa') }}" required maxlength="150"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                @error('nombre_empresa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Responsable</label>
                <input name="contacto_ejecutivo" value="{{ old('contacto_ejecutivo') }}" maxlength="100"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Correo</label>
                <input type="email" name="correo" value="{{ old('correo') }}" maxlength="100"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                @error('correo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">Teléfono</label>
                <input name="telefono" value="{{ old('telefono') }}" maxlength="20"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-stone-600 mb-1">Dirección</label>
                <input name="direccion" value="{{ old('direccion') }}" maxlength="200"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 mb-1">RUC</label>
                <input name="ruc" value="{{ old('ruc') }}" maxlength="11"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                @error('ruc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-6 py-2.5 rounded-xl text-sm font-semibold">
                Guardar proveedor
            </button>
            <a href="{{ route('admin.proveedores.index') }}"
               class="px-6 py-2.5 rounded-xl text-sm font-semibold bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
