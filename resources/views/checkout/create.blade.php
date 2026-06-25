@extends('layouts.app')
@section('title', 'Finalizar Compra - BookShop')

@section('content')
<main class="px-[7%] py-12 flex-grow bg-[#F9F6F3]">
    <div class="max-w-6xl mx-auto">

        {{-- Enlace Volver al carrito y Título calificado con la imagen image_bcab47.png --}}
        <div class="mb-8">
            <a href="{{ route('carrito.index') }}" class="inline-flex items-center gap-2 text-sm text-[#9C4309] hover:underline mb-3 font-medium transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Volver al carrito
            </a>
            <h1 class="text-4xl font-serif text-amber-900">Finalizar Compra</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Formulario principal con Grid de 2 columnas en pantallas grandes (lg) --}}
        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
            

            {{-- COLUMNA IZQUIERDA: Agrupa los 3 bloques de datos --}}
            <div class="space-y-6">

                {{-- 1. Información personal --}}
                <section class="bg-white rounded-2xl border border-[#421605]/10 p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-[#FFF0E6] text-[#B8500C] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0v.443c0 .75-.617 1.387-1.364 1.387H5.864z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-2xl font-serif text-amber-900 mb-0.5">Información personal</h2>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-semibold text-[#554138] mb-1">
                                Nombre <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Juan" value="{{ old('nombre') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Apellidos --}}
                        <div>
                            <label for="apellidos" class="block text-sm font-semibold text-[#554138] mb-1">
                                Apellidos <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="apellidos" name="apellidos" required placeholder="García López" value="{{ old('apellidos') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Tipo de documento --}}
                        <div>
                            <label for="tipo_documento" class="block text-sm font-semibold text-[#554138] mb-1">
                                Tipo de documento <span class="text-[#B8500C]">*</span>
                            </label>
                            <div class="relative">
                                <select id="tipo_documento" name="tipo_documento" required class="w-full appearance-none rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] bg-white focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                                    <option value="" disabled {{ old('tipo_documento') ? '' : 'selected' }}>Seleccionar...</option>
                                    <option value="DNI" {{ old('tipo_documento') === 'DNI' ? 'selected' : '' }}>DNI</option>
                                    <option value="CE" {{ old('tipo_documento') === 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                                    <option value="PASAPORTE" {{ old('tipo_documento') === 'PASAPORTE' ? 'selected' : '' }}>Pasaporte</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#8A7A71]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Número de documento --}}
                        <div>
                            <label for="documento" class="block text-sm font-semibold text-[#554138] mb-1">
                                Documento <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="documento" name="documento" required placeholder="" value="{{ old('documento') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Número de Teléfono --}}
                        <div class="sm:col-span-2">
                            <label for="telefono" class="block text-sm font-semibold text-[#554138] mb-1">
                                Número de teléfono <span class="text-[#B8500C]">*</span>
                            </label>
                            <div class="flex bg-white rounded-xl border border-[#421605]/20 focus-within:border-[#B8500C] focus-within:ring-1 focus-within:ring-[#B8500C] transition-all overflow-hidden items-center w-full h-[42px]">
                                <span class="pl-4 pr-2 text-sm text-[#554138] font-medium select-none shrink-0 flex items-center h-full">
                                    +51
                                </span>
                                <input type="tel" id="telefono" name="telefono" required placeholder="987654321" maxlength="9" value="{{ old('telefono') }}" class="w-full h-full bg-transparent pl-1 pr-4 py-0 text-sm text-[#421605] placeholder-[#C4B5AD] !border-none !outline-none !ring-0 focus:!outline-none focus:!ring-0 focus:!border-none shadow-none">
                            </div>
                            <p class="text-xs text-[#8A7A71] mt-1">9 dígitos, sin espacios</p>
                        </div>
                    </div>
                </section>

                {{-- 2. Dirección de envío --}}
                <section class="bg-white rounded-2xl border border-[#421605]/10 p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-[#FFF0E6] text-[#B8500C] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-2xl font-serif text-amber-900 mb-0.5">Dirección de envío</h2>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Calle --}}
                        <div>
                            <label for="calle" class="block text-sm font-semibold text-[#554138] mb-1">
                                Calle <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="calle" name="calle" required placeholder="Ej. Costa Rica" value="{{ old('calle') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Número --}}
                        <div>
                            <label forbid="numero" class="block text-sm font-semibold text-[#554138] mb-1">
                                Número <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="numero" name="numero" required placeholder="Ej. 999" value="{{ old('numero') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Piso/Dpto --}}
                        <div>
                            <label for="piso_dpto" class="block text-sm font-semibold text-[#554138] mb-1">
                                Piso/Dpto. <span class="text-xs font-normal text-[#8A7A71]">(Opcional)</span>
                            </label>
                            <input type="text" id="piso_dpto" name="piso_dpto" placeholder="Ej. 7G" value="{{ old('piso_dpto') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Entre Calles --}}
                        <div>
                            <label for="entre_calles" class="block text-sm font-semibold text-[#554138] mb-1">
                                Entre Calles <span class="text-xs font-normal text-[#8A7A71]">(Opcional)</span>
                            </label>
                            <input type="text" id="entre_calles" name="entre_calles" placeholder="Ej. Lavender y Gurion" value="{{ old('entre_calles') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- País --}}
                        <div class="sm:col-span-2">
                            <label for="pais" class="block text-sm font-semibold text-[#554138] mb-1">País</label>
                            <div class="relative">
                                <select id="pais" name="pais" class="w-full appearance-none rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] bg-white focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                                    <option value="PE" selected>Perú</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#8A7A71]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Provincia --}}
                        <div>
                            <label for="provincia" class="block text-sm font-semibold text-[#554138] mb-1">
                                Provincia <span class="text-[#B8500C]">*</span>
                            </label>
                            <div class="relative">
                                <select id="provincia" name="provincia" required class="w-full appearance-none rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] bg-white focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                                    <option value="" disabled {{ old('provincia') ? '' : 'selected' }}>Seleccionar...</option>
                                    <option value="Lima" {{ old('provincia') === 'Lima' ? 'selected' : '' }}>Lima</option>
                                    <option value="Arequipa" {{ old('provincia') === 'Arequipa' ? 'selected' : '' }}>Arequipa</option>
                                    <option value="Cusco" {{ old('provincia') === 'Cusco' ? 'selected' : '' }}>Cusco</option>
                                    <option value="Trujillo" {{ old('provincia') === 'Trujillo' ? 'selected' : '' }}>Trujillo</option>
                                    <option value="Piura" {{ old('provincia') === 'Piura' ? 'selected' : '' }}>Piura</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#8A7A71]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Ciudad --}}
                        <div>
                            <label for="ciudad" class="block text-sm font-semibold text-[#554138] mb-1">
                                Ciudad <span class="text-[#B8500C]">*</span>
                            </label>
                            <input type="text" id="ciudad" name="ciudad" required placeholder="Ej. Capital Federal" value="{{ old('ciudad') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>

                        {{-- Código Postal --}}
                        <div>
                            <label for="codigo_postal" class="block text-sm font-semibold text-[#554138] mb-1">
                                Código Postal <span class="text-xs font-normal text-[#8A7A71]">(Opcional)</span>
                            </label>
                            <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Ej. 15001" value="{{ old('codigo_postal') }}" class="w-full rounded-xl border border-[#421605]/20 px-4 py-2.5 text-sm text-[#421605] placeholder-[#C4B5AD] focus:outline-none focus:border-[#B8500C] focus:ring-1 focus:ring-[#B8500C]">
                        </div>
                    </div>
                </section>

                {{-- 3. Método de Pago --}}
                <section class="bg-white rounded-2xl border border-[#421605]/10 p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full bg-[#FFF0E6] text-[#B8500C] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                            </svg>
                        </span>
                        <h2 class="text-2xl font-serif text-amber-900">Método de Pago</h2>
                    </div>
                    <div class="flex gap-3 flex-wrap">
                        <label class="flex items-center gap-2 border border-[#421605]/20 rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-[#B8500C] has-[:checked]:bg-[#FFF0E6]">
                            <input type="radio" name="metodo_pago" value="efectivo" class="accent-[#B8500C]" {{ old('metodo_pago') === 'efectivo' ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-[#421605]">Mercado Pago</span>
                        </label>
                    </div>
                </section>

            </div> {{-- FIN DE LA COLUMNA IZQUIERDA --}}

            {{-- COLUMNA DERECHA: Resumen del pedido --}}
            <aside class="bg-white rounded-2xl border border-[#421605]/10 p-6 lg:sticky lg:top-8 shadow-sm">
                <h2 class="text-2xl font-serif text-amber-900 mb-6">Resumen del Pedido</h2>
                <div class="space-y-4 mb-5">
                    @foreach ($items as $item)
                        <div class="flex gap-3 items-start">
                            
                            {{-- Lógica unificada para mostrar la portada --}}
                            @if ($item['libro']->portada_url ?? null)
                                <img src="{{ $item['libro']->portada_url }}" 
                                     alt="{{ $item['libro']->titulo }}" 
                                     class="w-12 h-16 object-cover rounded-lg border border-[#421605]/10 shrink-0"
                                     loading="lazy">
                            @endif

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[#421605] leading-snug line-clamp-2">{{ $item['libro']->titulo }}</p>
                                <p class="text-xs text-[#8A7A71] mt-0.5">{{ $item['cantidad'] }} × S/ {{ number_format((float) $item['libro']->precio, 2) }}</p>
                            </div>
                            <span class="text-sm font-semibold text-[#421605] shrink-0">
                                S/ {{ number_format($item['subtotal_centimos'] / 100, 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <dl class="border-t border-[#421605]/10 pt-4 space-y-3 text-sm">
                    {{-- Subtotal --}}
                    <div class="flex justify-between text-[#554138]">
                        <dt class="font-medium text-[#8A7A71]">Subtotal</dt>
                        <dd class="font-semibold text-[#421605]">S/ {{ number_format((float) $subtotal, 2) }}</dd>
                    </div>

                    {{-- Envío --}}
                    <div class="flex justify-between text-[#554138]">
                        <dt class="text-[#8A7A71]">Envío</dt>
                        <dd class="text-sm text-emerald-600 font-bold tracking-wide">
                            Gratis
                        </dd>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-baseline font-bold border-t border-[#421605]/10 pt-3 text-[#421605]">
                        <dt class="text-base font-bold">Total</dt>
                        <dd class="text-2xl text-[#9C4309] tracking-tight font-sans font-bold">
                            <span class="text-xl mr-0.5">S/</span>{{ number_format((float) $total, 2) }}
                        </dd>
                    </div>
                </dl>

                <button type="submit" class="w-full bg-[#B8500C] hover:bg-[#963F07] active:bg-[#7A3205] text-white rounded-full py-3.5 text-sm font-semibold mt-6 transition-colors duration-150 shadow-sm">
                    Confirmar Compra
                </button>
            </aside>

        </form>
    </div>
</main>
@endsection