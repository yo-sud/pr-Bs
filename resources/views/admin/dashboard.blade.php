@extends('layouts.admin')
@section('title', 'Dashboard - BookShop')
@section('contenido')

<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-[#2C1B12]">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Resumen general de BookShop</p>
    </div>

    {{-- Rejilla responsiva optimizada --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
        {{-- Bucle para definir una lista de arreglo, esto representa las tarjetas del dashboard con todo lo necesario para trabajar --}}
        @foreach ([
            ['Ventas del Mes', 'S/ . ' . number_format($totalventas, 2), 'payments', 'text-amber-600 bg-amber-50', false],
            ['Libros', $totalLibros, 'book_2', 'text-blue-600 bg-blue-50', 'admin.libros.index'],
            ['Stock total', $stockTotal, 'inventory_2', 'text-emerald-600 bg-emerald-50', 'admin.inventario.index'],
            ['Categorías', $categorias, 'category', 'text-purple-600 bg-purple-50', 'admin.categorias.index'],
            ['Proveedores', $proveedores, 'local_shipping', 'text-indigo-600 bg-indigo-50', 'admin.proveedores.index'],
            ['Repartidores', $repartidores, 'delivery_dining', 'text-cyan-600 bg-cyan-50', 'admin.repartidores.index'],
            ['Usuarios', $usuarios, 'group', 'text-rose-600 bg-rose-50', 'admin.usuarios.index'],
        ] as [$etiqueta, $valor, $icono, $color, $ruta])

        {{-- Si la tarjeta tiene ruta, se vuelve dinámica, si no, se queda estática --}}
            @if($ruta)
                <a href="{{ route($ruta) }}" class="block bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all flex flex-col justify-between min-h-[140px]">
                    <div class="flex items-start justify-between">
                        <span class="material-symbols-outlined text-2xl {{ $color }} p-2 rounded-xl">{{ $icono }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mt-3 tracking-wider">{{ $etiqueta }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $valor }}</p>
                    </div>
                </a>
            @else
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between min-h-[140px]">
                    <div class="flex items-start justify-between">
                        <span class="material-symbols-outlined text-2xl {{ $color }} p-2 rounded-xl">{{ $icono }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase mt-3 tracking-wider">{{ $etiqueta }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $valor }}</p>
                    </div>
                </div>
            @endif

        @endforeach
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-[#2C1B12]">Rendimiento de Ventas Anual</h3>
                <p class="text-xs text-gray-400">Estadística de ingresos mensuales registrados durante el año en curso</p>
            </div>
            <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md">Moneda: Soles (S/.)</span>
        </div>
        <div class="w-full h-72 relative">
            <canvas id="graficoVentas"></canvas>
        </div>
    </div><script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('graficoVentas').getContext('2d');

    // Inicializamos el gráfico de Chart.js
    new Chart(ctx, {
        type: 'bar', // Tipo de gráfico: Barras verticales
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Ventas en S/.',
                data: {!! json_encode($ventasmensuales) !!},

                backgroundColor: '#D4A373', // Color marrón oscuro predeterminado
                hoverBackgroundColor: '#E6C5A4', // Cambia a dorado/arena al pasar el mouse
                borderRadius: 6, // Esquinas redondeadas suaves superiores para cada barra
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Permite que se estire según el contenedor de Tailwind
            plugins: {
                legend: {
                    display: false // Oculta la leyenda superior automática para mantenerlo limpio
                },
                tooltip: {
                    // Configuración del globo flotante profesional al pasar el mouse
                    backgroundColor: '#1F130B',
                    titleColor: '#FFF',
                    bodyColor: '#D4A373',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' S/. ' + context.raw.toLocaleString('es-PE', { minimumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                // Configuración de los ejes para que se vea minimalista
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#E5E7EB', // Líneas horizontales tenues de fondo
                        drawTicks: false
                    },
                    border: {
                        dash: [5, 5] // Líneas punteadas elegantes
                    }
                },
                x: {
                    grid: {
                        display: false // Oculta las líneas verticales para limpiar la vista
                    }
                }
            }
        }
    });
</script>    
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
