@extends('layouts.admin')

@section('title', 'Usuarios - Administración')

@section('mainClass', 'bg-white')

@section('contenido')
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-serif text-2xl font-semibold text-amber-900">Gestión de Usuarios</h2>
            <p class="text-stone-500 text-sm mt-1">Administra los usuarios del sistema.</p>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="bg-white p-4 rounded-xl border">
        <form action="{{ route('admin.usuarios.index') }}" method="GET">
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z" />
                    </svg>
                </span>
                <input name="search" value="{{ request('search') }}"
                       placeholder="Buscar por nombre, email o rol..."
                       class="w-full pl-9 rounded-lg border-gray-300 text-sm py-2 px-4 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-amber-50 border-b border-amber-100 text-left text-xs uppercase text-amber-900 font-semibold">
                <tr>
                    <th class="px-5 py-3">Usuario</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Rol</th>
                    <th class="px-5 py-3">Teléfono</th>
                    <th class="px-5 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-50">
                @forelse ($usuarios as $usuario)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-5 py-4 font-semibold text-stone-800">{{ $usuario->name }}</td>
                        <td class="px-5 py-4 text-stone-600">{{ $usuario->email }}</td>
                        <td class="px-5 py-4 uppercase text-xs font-semibold text-stone-600">{{ $usuario->role }}</td>
                        <td class="px-5 py-4 text-stone-500">{{ $usuario->phone ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.usuarios.toggle-status', $usuario) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-2 py-1 rounded-full text-xs font-semibold transition-colors
                                    {{ $usuario->status
                                        ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                                        : 'bg-stone-100 text-stone-500 hover:bg-stone-200' }}"
                                    title="{{ $usuario->status ? 'Clic para desactivar' : 'Clic para activar' }}">
                                    {{ $usuario->status ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-stone-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-5.196-3.796M9 20H4v-2a4 4 0 015.196-3.796M15 7a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm">No se encontraron usuarios</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer con conteo --}}
        <div class="px-5 py-3 border-t border-amber-50 text-xs text-stone-400">
            {{ $usuarios->total() }} usuarios encontrados
        </div>
    </div>

    {{ $usuarios->links() }}
</div>
@endsection
