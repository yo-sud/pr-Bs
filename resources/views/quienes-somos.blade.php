@extends('layouts.app')

@section('title', 'Quiénes Somos - BookShop')

@section('content')
<main class="flex-grow bg-[#FFFDF9] antialiased py-16 px-[7%]">
    <div class="max-w-4xl mx-auto">
        
        {{-- BLOQUE 1 EN EL MEDIO (ENCERRADO EN TU CAPTURA) --}}
        <div class="mb-12 text-center">
            <span class="text-[#B8500C] uppercase tracking-widest text-xs font-bold font-sans block mb-2">Conócenos</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-[#421605] leading-tight">
                Sobre BookShop
            </h1>
        </div>

        {{-- TEXTO DE INTRODUCCIÓN --}}
        <div class="prose max-w-none mb-12">
            <p class="font-sans text-base md:text-lg text-[#554138] leading-relaxed text-center">
                Somos una plataforma de comercio electrónico dedicada exclusivamente a la venta y distribución de libros físicos. Nos enfocamos en ofrecer a nuestros usuarios una experiencia de navegación fluida, permitiéndoles explorar un catálogo organizado y realizar compras de manera segura desde cualquier lugar.
            </p>
        </div>

        <hr class="border-t border-[#6E7E80]/20 mb-12">

        {{-- SECCIÓN: MISIÓN Y VISIÓN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
            <div>
                <h2 class="font-serif text-2xl font-bold text-[#421605] mb-4 flex items-center gap-2">
                    <span class="text-[#B8500C] text-xl">▪</span> Nuestra Misión
                </h2>
                <p class="font-sans text-sm text-[#554138] leading-relaxed">
                    Facilitar el acceso a la lectura para usuarios de todas las edades a través de una tienda virtual intuitiva. Nos esforzamos por mantener un catálogo preciso con stock real, garantizando transacciones seguras y entregas eficientes de cada ejemplar.
                </p>
            </div>

            <div>
                <h2 class="font-serif text-2xl font-bold text-[#421605] mb-4 flex items-center gap-2">
                    <span class="text-[#B8500C] text-xl">▪</span> Nuestra Visión
                </h2>
                <p class="font-sans text-sm text-[#554138] leading-relaxed">
                    Consolidarnos como la plataforma web de confianza para la compra de libros en línea a nivel nacional, destacando por nuestra transparencia en los procesos de envío, la seguridad de nuestro sistema y la calidad del servicio al cliente.
                </p>
            </div>
        </div>

        <hr class="border-t border-[#6E7E80]/20 mb-12">

        {{-- BLOQUE 2 EN EL MEDIO (ENCERRADO EN TU CAPTURA) --}}
        <div class="space-y-10">
            <div class="text-center mb-8">
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#421605]">Nuestro Compromiso Comercial</h2>
                <div class="w-12 h-[2px] bg-[#B8500C] mt-3 mx-auto"></div> {{-- Línea decorativa centrada --}}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div>
                    <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Navegación Especializada</h3>
                    <p class="font-sans text-xs text-[#554138] leading-relaxed">
                        Clasificamos de forma estricta los títulos en secciones claras como Literatura, Ciencia Ficción, Misterio, Historia, Terror y Desarrollo Personal para agilizar tu búsqueda.
                    </p>
                </div>

                <div>
                    <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Transacciones Protegidas</h3>
                    <p class="font-sans text-xs text-[#554138] leading-relaxed">
                        Integramos soporte directo para métodos de pago reconocidos y seguros como Visa, BCP y Pago Efectivo, velando por la integridad de tus datos en cada compra.
                    </p>
                </div>

                <div>
                    <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Control de Logística</h3>
                    <p class="font-sans text-xs text-[#554138] leading-relaxed">
                        Supervisamos el embalaje y despacho de los libros físicos comprados en la plataforma, asegurando que lleguen a su destino en óptimas condiciones.
                    </p>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection