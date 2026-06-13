@extends('layouts.admin')

@section('title', 'Usuarios - Administración')

@section('contenido')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-[#2C1B12]">Usuarios</h2>
            <p class="text-sm text-gray-500 mt-1">Listado de cuentas registradas en la tienda.</p>
        </div>
        </div>

        <div>
            {{-- Enlace al formulario de creación del controlador (admin.usuarios.create) --}}
            <a href="{{ route('admin.usuarios.create') }}" class="inline-flex items-center justify-center px-4 
            py-2 text-sm font-medium text-white bg-[#2C1B12] hover:bg-[#42281b] rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nuevo Usuario
            </a>
        </div>
    </div>

    {{-- 2. TABLA DE DATOS: Estructura principal con scroll horizontal responsivo para pantallas pequeñas --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-5 py-4">Nombre</th>
                    <th class="px-5 py-4">Correo</th>
                    <th class="px-5 py-4">Rol</th>
                    <th class="px-5 py-4">Registrado</th>
                    <th class="px-5 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                {{-- Bucle que recorre la colección de usuarios enviada desde el controlador --}}
                @forelse ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold">{{ $usuario->name }}</td>
                        <td class="px-5 py-4">{{ $usuario->email }}</td>
                        <td class="px-5 py-4 uppercase text-xs font-semibold">{{ $usuario->role }}</td>
                        <td class="px-5 py-4 whitespace-nowrap">{{ $usuario->created_at->format('d/m/Y H:i') }}</td>

                        {{-- 3. BLOQUE DE ACCIONES (CRUD): Gestión individual de cada registro --}}
                        <td class="px-5 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-3">
                                {{-- Enlace de edición: Envía el ID del usuario actual al formulario de edición --}}
                                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="Editar usuario">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Formulario de eliminación: Usa método DELETE por seguridad con confirmación integrada de JS --}}
                                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Eliminar usuario">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                
                            </div>
                        </td>
                    </tr>
                @empty
                {{--Si consulta en la base de datos y no hay registros--}}
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
