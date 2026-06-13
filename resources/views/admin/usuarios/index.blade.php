@extends('layouts.admin')

@section('title', 'Usuarios - Administración')

@section('contenido')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Usuarios</h2>
        <p class="text-sm text-gray-500 mt-1">Listado de cuentas registradas en la tienda.</p>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Nombre</th>
                    <th class="px-5 py-4">Correo</th>
                    <th class="px-5 py-4">Rol</th>
                    <th class="px-5 py-4">Registrado</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold">{{ $usuario->name }}</td>
                        <td class="px-5 py-4">{{ $usuario->email }}</td>
                        <td class="px-5 py-4 uppercase text-xs font-semibold">{{ $usuario->role }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $usuarios->links() }}</div>
</div>
@endsection