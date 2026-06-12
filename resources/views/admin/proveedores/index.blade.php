@extends('layouts.admin')

@section('title', 'Proveedores - Administración')

@section('contenido')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Proveedores</h2>
        <p class="text-sm text-gray-500">Gestiona las fuentes de abastecimiento.</p>
    </div>

    <form method="POST" action="{{ route('admin.proveedores.store') }}" class="bg-white rounded-xl border p-5 grid grid-cols-1 md:grid-cols-4 gap-3">
        @csrf
        <input name="nombre" required maxlength="100" placeholder="Nombre *" class="rounded-lg border-gray-300">
        <input name="telefono" maxlength="20" placeholder="Teléfono" class="rounded-lg border-gray-300">
        <input type="email" name="correo" maxlength="100" placeholder="Correo" class="rounded-lg border-gray-300">
        <button class="bg-[#B8500C] text-white px-5 py-2.5 rounded-lg text-sm font-semibold">Agregar proveedor</button>
    </form>

    <div class="space-y-4">
        @foreach ($proveedores as $proveedor)
            <form method="POST" action="{{ route('admin.proveedores.update', $proveedor) }}" class="bg-white rounded-xl border p-4 grid grid-cols-1 md:grid-cols-[1fr_180px_1fr_auto_auto] gap-3 items-center">
                @csrf
                @method('PUT')
                <input name="nombre" value="{{ $proveedor->nombre }}" required maxlength="100" class="rounded-lg border-gray-300">
                <input name="telefono" value="{{ $proveedor->telefono }}" maxlength="20" class="rounded-lg border-gray-300">
                <input type="email" name="correo" value="{{ $proveedor->correo }}" maxlength="100" class="rounded-lg border-gray-300">
                <span class="text-xs text-gray-500">{{ $proveedor->libros_count }} libros</span>
                <button class="text-sm font-semibold text-[#B8500C]">Guardar</button>
            </form>

            @if ($proveedor->libros_count === 0)
                <form method="POST" action="{{ route('admin.proveedores.destroy', $proveedor) }}" class="-mt-3 text-right" onsubmit="return confirm('¿Eliminar este proveedor?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-xs text-red-600">Eliminar proveedor</button>
                </form>
            @endif
        @endforeach
    </div>

    {{ $proveedores->links() }}
</div>
@endsection
