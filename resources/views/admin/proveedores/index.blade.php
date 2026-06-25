@extends('layouts.admin')

@section('title', 'Proveedores - Administración')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Gestión de Proveedores</h2>
            <p class="text-stone-500 text-sm mt-1">Administra los proveedores de libros.</p>
        </div>
        <a href="{{ route('admin.proveedores.create') }}"
           class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
            + Agregar Proveedor
        </a>
    </div>

    {{-- Buscador --}}
    <div class="bg-white p-4 rounded-xl border">
        <form action="{{ route('admin.proveedores.index') }}" method="GET">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
                    </svg>
                </span>
                <input name="search" value="{{ request('search') }}"
                       placeholder="Buscar por empresa, responsable o email..."
                       class="w-full pl-9 rounded-lg border-gray-300 text-sm py-2 px-4 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-amber-50 border-b border-amber-100 text-left text-xs uppercase text-amber-900 font-semibold">
                <tr>
                    <th class="px-5 py-3">Empresa</th>
                    <th class="px-5 py-3">Responsable</th>
                    <th class="px-5 py-3">Contacto</th>
                    <th class="px-5 py-3">Dirección</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-50">
                @forelse ($proveedores as $proveedor)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        {{-- Empresa --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="font-semibold text-stone-800">{{ $proveedor->nombre_empresa }}</span>
                            </div>
                        </td>
                        {{-- Responsable --}}
                        <td class="px-5 py-4 text-stone-600">{{ $proveedor->contacto_ejecutivo ?? '—' }}</td>
                        {{-- Contacto --}}
                        <td class="px-5 py-4">
                            @if($proveedor->correo)
                                <div class="flex items-center gap-1.5 text-stone-500 text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $proveedor->correo }}
                                </div>
                            @endif
                            @if($proveedor->telefono)
                                <div class="flex items-center gap-1.5 text-stone-500 text-xs mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $proveedor->telefono }}
                                </div>
                            @endif
                            @if(!$proveedor->correo && !$proveedor->telefono)
                                <span class="text-stone-400">—</span>
                            @endif
                        </td>
                        {{-- Dirección --}}
                        <td class="px-5 py-4 text-stone-500 text-xs">{{ $proveedor->direccion ?? '—' }}</td>
                        {{-- Estado --}}
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.proveedores.toggle-status', $proveedor) }}">
                                
                                @method('PATCH')
                                <button type="submit"
                                    class="px-2.5 py-1 rounded-full text-xs font-semibold border transition-colors
                                    {{ $proveedor->activo
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                        : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' }}"
                                    title="{{ $proveedor->activo ? 'Clic para desactivar' : 'Clic para activar' }}">
                                    {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </td>
                        {{-- Acciones --}}
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.proveedores.edit', $proveedor) }}"
                               class="text-stone-400 hover:text-[#B8500C] transition-colors" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-stone-400 text-sm">
                            No se encontraron proveedores.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-3 border-t border-amber-50 text-xs text-stone-400">
            {{ $proveedores->total() }} proveedores encontrados
        </div>
    </div>

    {{ $proveedores->links() }}
</div>
@endsection
