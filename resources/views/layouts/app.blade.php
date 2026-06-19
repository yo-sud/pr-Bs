<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookShop')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght=0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFBF7] text-[#421605] font-sans antialiased min-h-screen flex flex-col">

    {{-- NAVBAR SUPERIOR --}}
    <nav class="bg-[#FDFBF7] border-b border-[#6E7E80]/10 px-[7%] py-4 flex items-center justify-between gap-4 sticky top-0 z-50">
        <div class="flex items-center gap-3">
            {{-- Botón Hamburguesa Móvil --}}
            <button id="menu-toggle" class="md:hidden text-[#421605] p-1.5 hover:bg-[#F3ECE0]/50 rounded-lg transition-colors" aria-label="Abrir menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            {{-- LOGO PRINCIPAL ACTUALIZADO CON ESTILO FINO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-medium font-serif text-[#963F0B] tracking-wide whitespace-nowrap no-underline">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                BookShop
            </a>
        </div>

        {{-- Enlaces de Navegación del Menú --}}
        <div class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium text-[#554138]">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#B8500C] font-semibold' : 'hover:text-[#B8500C]' }} transition-colors">Inicio</a>
            <a href="{{ route('libros.index') }}" class="{{ request()->routeIs('libros.index', 'libros.show') ? 'text-[#B8500C] font-semibold' : 'hover:text-[#B8500C]' }} transition-colors">Todos los Libros</a>
            <a href="{{ route('libros.novedades') }}" class="{{ request()->routeIs('libros.novedades') ? 'text-[#B8500C] font-semibold' : 'hover:text-[#B8500C]' }} transition-colors">Novedades</a>
            <a href="{{ route('libros.populares') }}" class="{{ request()->routeIs('libros.populares') ? 'text-[#B8500C] font-semibold' : 'hover:text-[#B8500C]' }} transition-colors">Populares</a>
        </div>

        {{-- Barra de Herramientas del Navbar (Buscar, Login y Carrito) --}}
        <div class="flex items-center gap-3 sm:gap-5">
            {{-- MODIFICADO: Se trasladaron las clases responsivas al contenedor del formulario para aplicar tu input al 100% --}}
            <form action="{{ route('libros.index') }}" method="GET" class="relative hidden sm:block w-40 lg:w-64">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar" class="w-full pl-10 pr-4 bg-gray-100 border-none outline-none text-sm transition-all focus:bg-white focus:ring-2 focus:ring-amber-500/20 py-2 rounded-full text-[#421605]">
            </form>

            {{-- GESTIÓN DINÁMICA DE AUTENTICACIÓN MEJORADA CON MENÚ FLOTANTE --}}
            @auth
                <div x-data="{ open: false }" class="relative inline-block text-left font-sans z-50">
                    <button @click="open = !open" @click.away="open = false" class="focus:outline-none flex items-center">
                        <div class="h-9 w-9 rounded-full bg-[#B8500C] text-white flex items-center justify-center font-bold text-sm shadow-sm hover:bg-[#963F07] transition-colors cursor-pointer">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-72 bg-white border border-[#421605]/10 rounded-2xl shadow-xl p-4"
                         style="display: none;">
                        
                        <div class="flex items-center gap-3 pb-3 mb-2 border-b border-gray-100">
                            <div class="h-10 w-10 rounded-full bg-[#F3ECE0]/50 text-[#421605] flex items-center justify-center font-bold text-base">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <span class="block text-[10px] font-bold tracking-wider text-[#8A7A71] uppercase">Cuenta</span>
                                <h2 class="text-sm font-bold text-[#421605] truncate">{{ Auth::user()->name }}</h2>
                                <p class="text-xs text-[#8A7A71] truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-0.5">
                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-[#B8500C] hover:bg-[#FFF9EE] rounded-xl transition-colors">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                                    Panel de Administración
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-[#421605] hover:bg-[#FFF9EE] rounded-xl transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                Mi Perfil
                            </a>

                            <a href="{{ route('pedidos.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-[#421605] hover:bg-[#FFF9EE] rounded-xl transition-colors">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                Mis Pedidos
                            </a>

                            <div class="border-t border-gray-100 my-1.5"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-colors text-left">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-[#421605] hover:text-[#B8500C] transition-colors" aria-label="Iniciar Sesión">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            @endauth

            {{-- Icono Carrito de Compras --}}
            <div class="relative hidden sm:block" 
                 x-data="{ open: false }" 
                 @mouseenter="open = true" 
                 @mouseleave="open = false">
                
                <a class="relative p-2 hover:bg-gray-100 rounded-full transition-colors block" href="/carrito" data-discover="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart w-6 h-6 text-gray-700">
                        <circle cx="8" cy="21" r="1"></circle>
                        <circle cx="19" cy="21" r="1"></circle>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                    </svg>
                    @if ($cantidadCarrito > 0)
                        <span class="absolute -top-1 -right-1 min-w-4 h-4 px-1 rounded-full bg-[#B8500C] text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $cantidadCarrito }}
                        </span>
                    @endif
                </a>

                {{-- VENTANITA FLOTANTE (MINI-CART ACTUALIZADA CON ESTILOS COMPLETOS) --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 font-sans"
                     style="display: none;">
                    
                    @if($cantidadCarrito == 0)
                        {{-- VISTA CUANDO EL CARRITO ESTÁ VACÍO --}}
                        <div class="p-6 text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart w-8 h-8 text-amber-600">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                                </svg>
                            </div>
                            <p class="text-gray-700 font-medium mb-1">Tu carrito está vacío</p>
                            <p class="text-gray-500 text-sm">Añade algunos libros para comenzar</p>
                        </div>
                    @else
                        {{-- VISTA CUANDO TIENE ELEMENTOS --}}
                        <div class="p-4">
                            {{-- ARREGLO DEL SUB-TOTAL: Calculamos el total reactivo directo en la vista desde la sesión --}}
                            @php
                                $subtotalCalculado = collect(session('carrito', []))->sum(function($item) {
                                    return ($item['precio'] ?? 0) * ($item['cantidad'] ?? 0);
                                });
                            @endphp

                            <div class="max-h-64 overflow-y-auto mb-4 space-y-3">
                                @foreach(session('carrito', []) as $id => $item)
                                    <div class="flex items-center gap-3 group">
                                        <img src="{{ $item['portada_url'] ?? $item['imagen'] ?? 'https://via.placeholder.com/40x60' }}" 
                                             alt="{{ $item['titulo'] }}" 
                                             class="w-12 h-16 object-cover rounded shadow-sm">
                                        
                                        <div class="flex-1 min-w-0 flex flex-col text-left">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item['titulo'] }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $item['autor'] ?? 'Autor desconocido' }}</p>
                                            <p class="text-sm font-bold text-amber-900 mt-0.5">
                                                {{ $item['cantidad'] }} × S/ {{ number_format($item['precio'], 2) }}
                                            </p>
                                        </div>

                                        {{-- Botón para Eliminar Item Individual --}}
                                        <form action="{{ route('carrito.destroy', $id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="opacity-0 group-hover:opacity-100 p-1 hover:bg-red-50 rounded-full transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 text-red-600">
                                                    <path d="M18 6 6 18"></path>
                                                    <path d="m6 6 12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Subtotal Calculado Corregido --}}
                            <div class="border-t border-gray-200 pt-4 mb-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-600">Subtotal ({{ $cantidadCarrito }} artículos)</span>
                                    <span class="text-lg font-bold text-amber-900">S/ {{ number_format($subtotalCalculado, 2) }}</span>
                                </div>
                            </div>

                            {{-- Acciones del Carrito --}}
                            <div class="space-y-2">
                                <a class="block w-full bg-amber-500 hover:bg-amber-600 text-white py-2.5 px-4 rounded-xl text-center font-medium transition-colors" href="/carrito" data-discover="true">
                                    Ver Carrito Completo
                                </a>
                                <a class="flex items-center justify-center gap-2 w-full border-2 border-amber-500 text-amber-700 hover:bg-amber-50 py-2.5 px-4 rounded-xl text-center font-medium transition-colors" href="/checkout" data-discover="true">
                                    Finalizar Compra
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- INTERFAZ DEL MENÚ MÓVIL DESPLEGABLE --}}
    <div id="mobile-menu" class="fixed inset-0 z-50 translate-x-full transition-transform duration-300 ease-in-out md:hidden" aria-hidden="true">
        <div id="menu-overlay" class="absolute inset-0 bg-[#421605]/40 backdrop-blur-sm"></div>
        
        <div class="absolute inset-y-0 right-0 w-4/5 max-w-sm bg-[#FDFBF7] shadow-xl p-6 flex flex-col gap-6">
            <div class="flex items-center justify-between border-b border-[#6E7E80]/10 pb-4">
                <span class="font-serif font-bold text-xl text-[#B8500C]">Menú</span>
                <button id="menu-close" class="text-[#421605] p-1.5 hover:bg-[#F3ECE0]/50 rounded-lg transition-colors" aria-label="Cerrar menú">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            {{-- MODIFICADO: Barra de búsqueda móvil adaptada al mismo diseño estilizado --}}
            <form action="{{ route('libros.index') }}" method="GET" class="relative w-full sm:hidden">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar" class="w-full pl-10 pr-10 bg-gray-100 border-none outline-none text-sm transition-all focus:bg-white focus:ring-2 focus:ring-amber-500/20 py-2 rounded-full text-[#421605]">
            </form>

            <div class="flex flex-col gap-4 text-base font-medium text-[#554138]">
                @auth
                    <div class="py-2 border-b border-[#6E7E80]/5">
                        <p class="text-xs text-[#8A7A71]">Hola,</p>
                        <a href="{{ route('profile.edit') }}" class="font-semibold text-[#B8500C]">{{ Auth::user()->name }}</a>
                    </div>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="py-2 border-b border-[#6E7E80]/5 text-[#B8500C] font-semibold">Panel administrativo</a>
                    @endif
                    <a href="{{ route('pedidos.index') }}" class="py-2 border-b border-[#6E7E80]/5">Mis pedidos</a>
                @else
                    <a href="{{ route('login') }}" class="py-2 flex items-center gap-2 text-[#B8500C] font-semibold border-b border-[#6E7E80]/5 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Mi Cuenta / Login
                    </a>
                @endauth

                <a href="{{ route('home') }}" class="py-2 border-b border-[#6E7E80]/5">Inicio</a>
                <a href="{{ route('libros.index') }}" class="hover:text-[#B8500C] py-2 border-b border-[#6E7E80]/5 transition-colors">Todos los Libros</a>
                <a href="{{ route('libros.novedades') }}" class="hover:text-[#B8500C] py-2 border-b border-[#6E7E80]/5 transition-colors">Novedades</a>
                <a href="{{ route('libros.populares') }}" class="hover:text-[#B8500C] py-2 transition-colors">Populares</a>

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-[#6E7E80]/10">
                        @csrf
                        <button type="submit" class="w-full text-left text-sm text-red-600 font-medium py-2">
                            Cerrar Sesión
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <div class="flex-grow flex flex-col">
        @yield('content')
    </div>

    <footer class="bg-gradient-to-b from-amber-900 to-amber-950 text-[#FDE68A] px-[7%] pt-16 pb-8 border-t border-white/5 font-['Lora',_serif]">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-8 pb-12 border-b border-white/10">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-2xl font-serif text-white mb-5 no-underline tracking-wide">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#E5A900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400">
                        <path d="M12 7v14"></path>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    <span class="font-medium text-white">BookShop</span>
                </a>
                <p class="text-sm leading-relaxed text-[oklch(0.91 0.12 95.75)]/70 max-w-[280px] font-sans mb-4">
                    Tu librería de confianza para descubrir historias maravillosas y conocimiento sin límites. Libros físicos al mejor precio.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-medium text-white mb-1 tracking-wide">Enlaces Rápidos</h3>
                <div class="flex flex-col gap-2.5 text-sm text-[oklch(0.91 0.12 95.75)]/70 font-sans">
                    <a href="{{ route('libros.index') }}" class="hover:text-white transition-colors">Todos los Libros</a>
                    <a href="{{ route('libros.novedades') }}" class="hover:text-white transition-colors">Novedades</a>
                    <a href="{{ route('libros.populares') }}" class="hover:text-white transition-colors">Populares</a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-medium text-white mb-1 tracking-wide">Sobre Nosotros</h3>
                <div class="flex flex-col gap-2.5 text-sm text-[oklch(0.91 0.12 95.75)]/70 font-sans">
                    <a href="{{ route('quienes-somos') }}" class="hover:text-white transition-colors">Quiénes somos</a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-medium text-white mb-1 tracking-wide">Contacto</h3>
                <div class="flex flex-col gap-3.5 text-sm text-[oklch(0.91 0.12 95.75)]/70 font-sans">
                    <div class="flex items-center gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>+51 907 210 407</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-[oklch(0.91_0.12_95.75)]/60 font-sans">
            <div class="flex flex-wrap items-center gap-2">
                <span>Aceptamos:</span>
                <span class="bg-white text-[#632E04] font-medium px-2.5 py-0.5 rounded text-xs border border-gray-100">Mercado Pago</span>
            </div>
            <p class="m-0 flex items-center leading-none">&copy; {{ date('Y') }} BookShop Perú. Todos los derechos reservados. Precios en Soles (S/).</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('menu-toggle');
            const menuClose = document.getElementById('menu-close');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuOverlay = document.getElementById('menu-overlay');

            function toggleMenu() {
                mobileMenu.classList.toggle('translate-x-full');
                mobileMenu.classList.toggle('translate-x-0');
            }

            if(menuToggle && menuClose && menuOverlay) {
                menuToggle.addEventListener('click', toggleMenu);
                menuClose.addEventListener('click', toggleMenu);
                menuOverlay.addEventListener('click', toggleMenu);
            }
        });
    </script>
</body>
</html>