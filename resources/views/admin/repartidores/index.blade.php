@extends('layouts.admin')

@section('title', 'Gestión de Repartidores - BookShop')

@section('contenido')
<div class="space-y-6">
    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Gestión de Empresas Repartidoras</h2>
            <p class="text-sm text-gray-500">Administra el personal de entregas.</p>
        </div>
        <a href="{{ route('admin.repartidores.create') }}" class="bg-[#B8500C] hover:bg-[#963F07] text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">add</span> Agregar Repartidor
        </a>
    </div>

    {{-- Tarjetas de Estadísticas Superiores (Tal cual tu captura) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Total Repartidores --}}
        <div class="bg-amber-50/40 p-4 rounded-xl border border-amber-100 text-center shadow-sm">
            <p class="text-3xl font-bold text-amber-800">{{ $repartidoresCount ?? 4 }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Total repartidores</p>
        </div>
        {{-- Activos --}}
        <div class="bg-emerald-50/40 p-4 rounded-xl border border-emerald-100 text-center shadow-sm">
            <p class="text-3xl font-bold text-emerald-700">{{ $repartidoresActivosCount ?? 3 }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Activos</p>
        </div>
        {{-- Pedidos Asignados --}}
        <div class="bg-blue-50/40 p-4 rounded-xl border border-blue-100 text-center shadow-sm">
            <p class="text-3xl font-bold text-blue-700">{{ $pedidosAsignadosCount ?? 15 }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Pedidos asignados</p>
        </div>
    </div>

    {{-- Buscador Integrado --}}
    <form method="GET" action="{{ route('admin.repartidores.index') }}" class="bg-white p-4 rounded-xl border flex gap-3 shadow-sm">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">search</span>
            <input name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, email o zona..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 text-sm focus:border-[#B8500C] focus:ring-[#B8500C]">
        </div>
        <button type="submit" class="bg-[#2C1B12] hover:bg-[#1f130d] text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
    </form>

    {{-- Tabla de Datos --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3">Empresa Repartidora</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Teléfono</th>
                    <th class="px-5 py-3">Zona</th>
                    <th class="px-5 py-3 text-center">Pedidos</th>
                    <th class="px-5 py-3">Estado</th>
                    <th class="px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($repartidores as $repartidor)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        {{-- Avatar y Nombre --}}
                        <td class="px-5 py-4 flex items-center gap-3">
                            <img src="{{ $repartidor->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($repartidor->nombre_empresa).'&background=EFEAE4&color=2C1B12' }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            <span class="font-bold text-[#2C1B12]">{{ $repartidor->nombre_empresa }}</span>
                        </td>
                        {{-- Email --}}
                        <td class="px-5 py-4 text-gray-600">{{ $repartidor->correo ?? 'Sin correo' }}</td>
                        {{-- Teléfono --}}
                        <td class="px-5 py-4 text-gray-600">{{ $repartidor->telefono ?? 'Sin teléfono' }}</td>
                        {{-- Zona / Tiempo Estimado --}}
                        <td class="px-5 py-4">
                            <span class="flex items-center gap-1 text-gray-700">
                                <span class="material-symbols-outlined text-sm text-amber-600">location_on</span>
                                {{ $repartidor->tiempo_entrega_estimado ?? 'Centro' }}
                            </span>
                        </td>
                        {{-- Pedidos --}}
                        <td class="px-5 py-4 text-center font-bold text-amber-700">
                            {{ $repartidor->pedidos_count ?? 0 }}
                        </td>
                        {{-- Estado (Activo / Inactivo con tus badges) --}}
                        <td class="px-5 py-4">
                            @if($repartidor->activo)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Activo</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Inactivo</span>
                            @endif
                        </td>
                        {{-- Acciones (Iconos limpios de tu captura) --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.repartidores.edit', $repartidor) }}" class="text-gray-400 hover:text-[#B8500C] transition-colors">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </a>
                                <form action="{{ route('admin.repartidores.destroy', $repartidor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de desactivar este repartidor?')" class="inline">
                                    
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors pt-1">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                            No se encontraron repartidores registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación limpia --}}
    <div class="mt-4">
        {{ $repartidores->links() }}
    </div>
</div>
@endsection