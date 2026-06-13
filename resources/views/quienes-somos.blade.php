@extends('layouts.app')

@section('title', 'Quiénes Somos - BookShop')

@section('content')
{{-- CAMBIO AQUÍ: Agregamos la imagen de la biblioteca de fondo, centrada y fija con Tailwind --}}
<main class="flex-grow bg-cover bg-center bg-no-repeat bg-fixed antialiased py-16 px-[7%]" 
      style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1920&auto=format&fit=crop');">
    
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white/85 backdrop-blur-md border border-white/40 rounded-[32px] p-8 md:p-12 shadow-[0_30px_60px_-15px_rgba(42,22,5,0.25)]">

            {{-- BLOQUE 1 EN EL MEDIO --}}
            <div class="mb-12 text-center">
                <span class="text-[#B8500C] uppercase tracking-widest text-xs font-bold font-sans block mb-2">Conócenos</span>
                <h1 class="font-serif text-4xl md:text-5xl font-bold text-[#421605] leading-tight">
                    Sobre BookShop
                </h1>
            </div>

            {{-- TEXTO DE INTRODUCCIÓN --}}
            <div class="prose max-w-none mb-12">
                <p class="font-sans text-base md:text-lg text-[#554138] leading-relaxed text-center font-medium">
                    Somos una plataforma de comercio electrónico dedicada exclusivamente a la venta y distribución de libros físicos. Nos enfocamos en ofrecer a nuestros usuarios una experiencia de navegación fluida, permitiéndoles explorar un catálogo organizado y realizar compras de manera segura desde cualquier lugar.
                </p>
            </div>

            <hr class="border-t border-[#421605]/15 mb-12">

            {{-- SECCIÓN: MISIÓN Y VISIÓN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#421605] mb-4 flex items-center gap-2">
                        <span class="text-[#B8500C] text-xl">▪</span> Nuestra Misión
                    </h2>
                    <p class="font-sans text-sm text-[#554138] leading-relaxed font-medium">
                        Facilitar el acceso a la lectura para usuarios de todas las edades a través de una tienda virtual intuitiva. Nos esmeramos por mantener un catálogo preciso con stock real, garantizando transacciones seguras y entregas eficientes de cada ejemplar.
                    </p>
                </div>

                <div>
                    <h2 class="font-serif text-2xl font-bold text-[#421605] mb-4 flex items-center gap-2">
                        <span class="text-[#B8500C] text-xl">▪</span> Nuestra Visión
                    </h2>
                    <p class="font-sans text-sm text-[#554138] leading-relaxed font-medium">
                        Consolidarnos como la plataforma web de confianza para la compra de libros en línea a nivel nacional, destacando por nuestra transparencia en los procesos de envío, la seguridad de nuestro sistema y la calidad del servicio al cliente.
                    </p>
                </div>
            </div>

            <hr class="border-t border-[#421605]/15 mb-12">

            {{-- BLOQUE 2 EN EL MEDIO --}}
            <div class="space-y-10">
                <div class="text-center mb-8">
                    <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#421605]">Nuestro Compromiso Comercial</h2>
                    <div class="w-12 h-[2px] bg-[#B8500C] mt-3 mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    <div>
                        <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Navegación Especializada</h3>
                        <p class="font-sans text-xs text-[#554138] leading-relaxed font-medium">
                            Clasificamos de forma estricta los títulos en secciones claras como Literatura, Ciencia Ficción, Misterio, Historia, Terror y Desarrollo Personal para agilizar tu búsqueda.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Transacciones Protegidas</h3>
                        <p class="font-sans text-xs text-[#554138] leading-relaxed font-medium">Integramos soporte directo con Mercado Pago para procesar tus compras de forma rápida, confiable y 100% segura, velando por la integridad de tus datos en todo momento.</p>
                    </div>

                    <div>
                        <h3 class="font-serif text-lg font-bold text-[#421605] mb-2">Control de Logística</h3>
                        <p class="font-sans text-xs text-[#554138] leading-relaxed font-medium">
                            Supervisamos el embalaje y despacho de los libros físicos comprados en la plataforma, asegurando que lleguen a su destino en óptimas condiciones.
                        </p>
                    </div>
                </div>
            </div>

        </div> {{-- FIN DE LA TARJETA TRANSLÚCIDA --}}

    </div>
</main>
@endsection