@extends('layouts.admin')

@section('title', 'Gestión de Empresas de Reparto - BookShop')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Gestión de Empresas de Reparto</h2>
            <p class="text-stone-500 text-sm mt-1">Administra y supervisa las empresas de reparto registradas en la plataforma.</p>
        </div>
        <a href="{{ route('admin.repartidores.create') }}"
           class="bg-[#B8500C] hover:bg-[#963F07] transition-colors text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md">
            + Agregar Empresa
        </a>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Total empresas --}}
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-700">{{ $repartidoresCount ?? 0 }}</p>
                <p class="text-sm font-semibold text-stone-700">Total empresas</p>
                <p class="text-xs text-stone-400">Empresas registradas</p>
            </div>
        </div>

        {{-- Empresas activas --}}
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-700">{{ $repartidoresActivosCount ?? 0 }}</p>
                <p class="text-sm font-semibold text-stone-700">Empresas activas</p>
                <p class="text-xs text-stone-400">Operando actualmente</p>
            </div>
        </div>

        {{-- Repartidores activos --}}
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-700">{{ $repartidoresActivosCount ?? 0 }}</p>
                <p class="text-sm font-semibold text-stone-700">Repartidores activos</p>
                <p class="text-xs text-stone-400">Trabajando actualmente</p>
            </div>
        </div>

        {{-- Pedidos asignados --}}
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V8l-4-4h-2m-2 0v4h4M8 12h8M8 16h4" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-500">{{ $pedidosAsignadosCount ?? 0 }}</p>
                <p class="text-sm font-semibold text-stone-700">Pedidos asignados</p>
                <p class="text-xs text-stone-400">Este mes</p>
            </div>
        </div>

    </div>

    {{-- Buscador --}}
    <div class="bg-white p-4 rounded-xl border">
        <form action="{{ route('admin.repartidores.index') }}" method="GET">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
                    </svg>
                </span>
                <input name="search" value="{{ request('search') }}"
                       placeholder="Buscar por nombre de empresa, email, teléfono o zona..."
                       class="w-full pl-9 rounded-lg border-gray-300 text-sm py-2 px-4 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-amber-50 border-b border-amber-100 text-left text-xs uppercase text-amber-900 font-semibold">
                <tr>
                    <th class="px-5 py-3">Empresa Repartidora</th>
                    <th class="px-5 py-3">Contacto</th>
                    <th class="px-5 py-3">Teléfono</th>
                    <th class="px-5 py-3">Zona de Cobertura</th>
                    <th class="px-5 py-3">Pedidos (este mes)</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-50">
                @forelse ($repartidores as $repartidor)
                    <tr class="hover:bg-amber-50/50 transition-colors">

                        {{-- Empresa con ícono de caja --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <strong class="block text-stone-800">{{ $repartidor->nombre_empresa }}</strong>
                                    <span class="text-xs text-stone-400">{{ $repartidor->correo ?? 'Sin correo' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Contacto / Responsable --}}
                        <td class="px-5 py-4 text-stone-600">{{ $repartidor->contacto_ejecutivo ?? '—' }}</td>

                        {{-- Teléfono --}}
                        <td class="px-5 py-4 text-stone-600">{{ $repartidor->telefono ?? '—' }}</td>

                        {{-- Zona --}}
                        <td class="px-5 py-4">
                            <span class="flex items-center gap-1 text-stone-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $repartidor->tiempo_entrega_estimado ?? '—' }}
                            </span>
                        </td>

                        {{-- Pedidos este mes --}}
                        <td class="px-5 py-4 font-bold text-amber-700">
                            {{ $repartidor->pedidos_count ?? 0 }}
                        </td>

                        {{-- Estado --}}
                        <td class="px-5 py-4">
                            @if($repartidor->activo)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Activo</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-200">Inactiva</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.repartidores.edit', $repartidor) }}"
                                   class="text-stone-400 hover:text-[#B8500C] transition-colors" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.repartidores.destroy', $repartidor) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta empresa?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-stone-400 hover:text-red-600 transition-colors" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-stone-500">
                            No se encontraron empresas de reparto registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $repartidores->links() }}

</div>
@endsection
