@extends('layouts.admin')

@section('title', 'Nuevo Usuario - Administración')

@section('contenido')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- 1. ENCABEZADO: Título de la pantalla y botón de retorno al listado principal --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#2C1B12]">Registrar Usuario</h2>
            <p class="text-sm text-gray-500 mt-1">Crea una nueva cuenta.</p>
        </div>
        <a href="{{ route('admin.usuarios.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al index :P
        </a>
    </div>

    {{-- 2. FORMULARIO PRINCIPAL: Conecta con el método 'store' del controlador mediante POST --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-5">
            {{-- Token de seguridad obligatorio en Laravel para evitar ataques CSRF --}}
            @csrf

            {{-- Campo: Nombre Completo --}}
            <div class="space-y-1">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2C1B12] focus:border-[#2C1B12] text-sm">
                @error('name') <p class="text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- Campo: Correo Electrónico --}}
            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2C1B12] focus:border-[#2C1B12] text-sm">
                {{-- Muestra el error en rojo si el correo ya está repetido en la base de datos --}}
                @error('email') <p class="text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- Campo: Selección de Rol --}}
            <div class="space-y-1">
                <label for="role" class="block text-sm font-medium text-gray-700">Rol del Sistema</label>
                <select name="role" id="role" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2C1B12] focus:border-[#2C1B12] text-sm bg-white">
                    <option value="cliente" {{ old('role') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('role') <p class="text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100 my-2">

            {{-- 3. BOTONES DE ACCIÓN --}}
            <div class="flex items-center justify-end space-x-3 pt-2">
                <a href="{{ route('admin.usuarios.index') }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-[#2C1B12] hover:bg-[#42281b] rounded-lg shadow-sm transition-colors">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>

</div>
@endsection