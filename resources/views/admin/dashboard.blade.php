@extends('layouts.admin')

@section('title', 'Dashboard - BookShop')

@section('contenido')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Resumen general de BookShop</p>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-6 gap-4">
        {{-- Bucle para definir una lista de arreglo, esto representa las tarejtas del dashboard con todo lo necesario
        para trabajar --}}
        @foreach ([
            ['Ventas del Mes', 'S/ . ' . number_format($totalventas, 2), 'payments', 'text-amber-600 bg-amber-50', false],
            ['Libros', $totalLibros, 'book_2', 'text-blue-600 bg-blue-50', 'admin.libros.index'],
            ['Stock total', $stockTotal, 'inventory_2', 'text-emerald-600 bg-emerald-50', 'admin.inventario.index'],
            ['Categorías', $categorias, 'category', 'text-purple-600 bg-purple-50', 'admin.categorias.index'],
            ['Proveedores', $proveedores, 'local_shipping', 'text-indigo-600 bg-indigo-50', 'admin.proveedores.index'],
            ['Repartidores', $repartidores, 'delivery_dining', 'text-cyan-600 bg-cyan-50', 'admin.repartidores.index'],  
            ['Usuarios', $usuarios, 'group', 'text-rose-600 bg-rose-50', 'admin.usuarios.index'],
        ] as [$etiqueta, $valor, $icono, $color, $ruta])

        {{-- Si la tarjeta tiene ruta, se vuelve dinamica, si no, se queda estatica, la tarjeta se puede cliquear --}}
            @if($ruta)
                <a href="{{ route($ruta) }}" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all">
                    <span class="material-symbols-outlined text-2xl {{ $color }} p-2 rounded-xl">{{ $icono }}</span>
                    <p class="text-xs font-bold text-gray-400 uppercase mt-3">{{ $etiqueta }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $valor }}</p>
                </a>
            @else
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <span class="material-symbols-outlined text-2xl {{ $color }} p-2 rounded-xl">{{ $icono }}</span>
                    <p class="text-xs font-bold text-gray-400 uppercase mt-3">{{ $etiqueta }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $valor }}</p>
                </div>
            @endif

        @endforeach
    </div>
    {{--Para el mensaje de alerta de stock o si o si --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-[#2C1B12]">Alertas de stock</h3>
                    <p class="text-xs text-gray-500">Libros con 5 unidades o menos.</p>
                </div>
                <a href="{{ route('admin.libros.index', ['stock' => 'bajo']) }}" class="text-sm font-semibold text-[#B8500C]">Ver todos</a>
            </div>
            <div class="divide-y">
                @forelse ($LibrosStockBajo as $libro)
                    <a href="{{ route('admin.libros.edit', $libro) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                        <span>
                            <strong class="block text-sm">{{ $libro->titulo }}</strong>
                            <span class="text-xs text-gray-500">{{ $libro->categoria->nombre }}</span>
                        </span>
                        <span class="font-bold {{ $libro->stock === 0 ? 'text-red-600' : 'text-amber-600' }}">{{ $libro->stock }}</span>
                    </a>
                @empty
                    <p class="p-6 text-sm text-gray-500">No hay alertas de stock.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
