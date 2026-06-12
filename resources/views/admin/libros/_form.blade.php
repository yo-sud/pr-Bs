@csrf
@if (isset($libro))
    @method('PUT')
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <label class="md:col-span-2">
        <span class="block text-sm font-semibold mb-1">Título *</span>
        <input name="titulo" value="{{ old('titulo', $libro->titulo ?? '') }}" required class="w-full rounded-lg border-gray-300">
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Autor *</span>
        <input name="autor" value="{{ old('autor', $libro->autor ?? '') }}" required class="w-full rounded-lg border-gray-300">
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">ISBN</span>
        <input name="isbn" value="{{ old('isbn', $libro->isbn ?? '') }}" class="w-full rounded-lg border-gray-300">
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Categoría *</span>
        <select name="categoria_id" required class="w-full rounded-lg border-gray-300">
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected((string) old('categoria_id', $libro->categoria_id ?? '') === (string) $categoria->id)>{{ $categoria->nombre }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Proveedor *</span>
        <select name="proveedor_id" required class="w-full rounded-lg border-gray-300">
            @foreach ($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}" @selected((string) old('proveedor_id', $libro->proveedor_id ?? '') === (string) $proveedor->id)>{{ $proveedor->nombre }}</option>
            @endforeach
        </select>
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Editorial</span>
        <input name="editorial" value="{{ old('editorial', $libro->editorial ?? '') }}" class="w-full rounded-lg border-gray-300">
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Fecha de publicación</span>
        <input type="date" name="fecha_publicacion" value="{{ old('fecha_publicacion', isset($libro) ? $libro->fecha_publicacion?->format('Y-m-d') : '') }}" class="w-full rounded-lg border-gray-300">
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Precio *</span>
        <input type="number" step="0.01" min="0.01" name="precio" value="{{ old('precio', $libro->precio ?? '') }}" required class="w-full rounded-lg border-gray-300">
    </label>
    @unless (isset($libro))
        <label>
            <span class="block text-sm font-semibold mb-1">Stock inicial *</span>
            <input type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required class="w-full rounded-lg border-gray-300">
        </label>
    @endunless
    <label>
        <span class="block text-sm font-semibold mb-1">Estado *</span>
        <select name="estado" class="w-full rounded-lg border-gray-300">
            <option value="activo" @selected(old('estado', $libro->estado ?? 'activo') === 'activo')>Activo</option>
            <option value="inactivo" @selected(old('estado', $libro->estado ?? '') === 'inactivo')>Inactivo</option>
        </select>
    </label>
    <label>
        <span class="block text-sm font-semibold mb-1">Portada</span>
        <input type="file" name="portada" accept="image/*" class="w-full rounded-lg border border-gray-300 p-2">
    </label>
    <label class="md:col-span-2 flex items-center gap-2">
        <input type="checkbox" name="destacado" value="1" @checked(old('destacado', $libro->destacado ?? false)) class="rounded border-gray-300 text-[#B8500C]">
        <span class="text-sm font-semibold">Mostrar como destacado</span>
    </label>
    <label class="md:col-span-2">
        <span class="block text-sm font-semibold mb-1">Descripción</span>
        <textarea name="descripcion" rows="5" class="w-full rounded-lg border-gray-300">{{ old('descripcion', $libro->descripcion ?? '') }}</textarea>
    </label>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.libros.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600">Cancelar</a>
    <button class="bg-[#B8500C] hover:bg-[#963F07] text-white px-6 py-2.5 rounded-lg text-sm font-semibold">Guardar libro</button>
</div>
