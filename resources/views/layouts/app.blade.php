<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookShop')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFBF7] text-[#421605] font-sans antialiased min-h-screen flex flex-col">

    {{-- NAVBAR SUPERIOR --}}
    <nav class="bg-[#FDFBF7] border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a class="flex items-center gap-2 flex-shrink-0" href="/" data-discover="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-7 h-7 lg:w-8 lg:h-8 text-amber-700">
                        <path d="M12 7v14"></path>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    <span class="text-xl lg:text-2xl font-serif text-amber-900 hidden sm:block">BookShop</span>
                    <span class="text-xl font-serif text-amber-900 sm:hidden">BS</span>
                </a>
                
                <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                    <a class="text-gray-700 hover:text-amber-700 transition-colors font-medium text-sm xl:text-base whitespace-nowrap {{ request()->routeIs('home') ? 'text-amber-700' : '' }}" href="{{ route('home') }}" data-discover="true">Inicio</a>
                    <a class="text-gray-700 hover:text-amber-700 transition-colors font-medium text-sm xl:text-base whitespace-nowrap {{ request()->routeIs('libros.index') ? 'text-amber-700' : '' }}" href="{{ route('libros.index') }}" data-discover="true">Todos los Libros</a>
                    <a class="text-gray-700 hover:text-amber-700 transition-colors font-medium text-sm xl:text-base whitespace-nowrap {{ request()->routeIs('libros.novedades') ? 'text-amber-700' : '' }}" href="{{ route('libros.novedades') }}" data-discover="true">Novedades</a>
                    <a class="text-gray-700 hover:text-amber-700 transition-colors font-medium text-sm xl:text-base whitespace-nowrap {{ request()->routeIs('libros.populares') ? 'text-amber-700' : '' }}" href="{{ route('libros.populares') }}" data-discover="true">Populares</a>
                </nav>

                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
                    <div class="hidden xl:block w-72 2xl:w-80">
                        <form action="{{ route('libros.index') }}" method="GET" class="relative w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <input type="text" name="search" placeholder="Buscar" class="w-full pl-10 pr-10 bg-gray-100 border-none outline-none text-sm transition-all focus:bg-white focus:ring-2 focus:ring-amber-500/20 py-2 rounded-full" value="{{ request('search') }}">
                        </form>
                    </div>

                    @auth
                        <div class="relative py-1">
                            <button id="user-dropdown-btn" class="flex items-center focus:outline-none group focus:ring-2 focus:ring-[#B8500C]/20 rounded-full p-0.5 transition-all" aria-label="Menú de usuario">
                                @if(Auth::user()->profile_photo_path)
                                    <img class="h-8 w-8 rounded-full object-cover border border-[#6E7E80]/20" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-[#B8500C] text-white flex items-center justify-center font-bold text-sm shadow-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                @endif
                            </button>
                            <div id="user-dropdown-menu" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-bold text-[#421605] truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#F3ECE0]/30">Mi Perfil</a>
                                <a href="{{ route('pedidos.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#F3ECE0]/30">Mis Pedidos</a>
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-[#B8500C] font-semibold hover:bg-[#F3ECE0]/30">Administrar</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">Cerrar sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a class="p-2 hover:bg-gray-100 rounded-full transition-colors hidden sm:block" href="{{ route('login') }}" data-discover="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-6 h-6 text-gray-700">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    @endauth

                    <a class="relative p-2 hover:bg-gray-100 rounded-full transition-colors block" href="{{ route('carrito.index') }}" data-discover="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart w-6 h-6 text-gray-700">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                        @if ($cantidadCarrito > 0)
                            <span class="absolute top-0 right-0 min-w-4 h-4 px-1 rounded-full bg-[#B8500C] text-white text-[10px] font-bold flex items-center justify-center">{{ $cantidadCarrito }}</span>
                        @endif
                    </a>
                    <button id="menu-toggle" class="lg:hidden p-2 hover:bg-amber-50 rounded-lg transition-colors" aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu w-6 h-6 text-gray-700">
                            <line x1="4" x2="20" y1="12" y2="12"></line>
                            <line x1="4" x2="20" y1="6" y2="6"></line>
                            <line x1="4" x2="20" y1="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="hidden lg:block xl:hidden pb-3">
                <form action="{{ route('libros.index') }}" method="GET" class="relative w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <input type="text" name="search" placeholder="Buscar" class="w-full pl-10 pr-10 bg-gray-100 border-none outline-none text-sm transition-all focus:bg-white focus:ring-2 focus:ring-amber-500/20 py-2 rounded-full" value="{{ request('search') }}">
                </form>
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
            
            <form action="{{ route('libros.index') }}" method="GET" class="relative w-full sm:hidden">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar libro..." class="w-full bg-[#F3ECE0]/60 text-sm pl-9 pr-4 py-2.5 rounded-full border border-transparent text-[#421605]">
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

    {{-- CONTENIDO INYECTADO --}}
    <div class="flex-grow flex flex-col">
        @yield('content')
    </div>

    {{-- FOOTER HORIZONTAL CORPORATIVO --}}
    <footer class="bg-[#421605] text-[#FDFBF7] px-[7%] pt-16 pb-8 border-t border-white/5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-10 gap-x-8 pb-12 border-b border-white/10">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold font-serif text-white mb-5">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    BookShop
                </a>
                <p class="text-sm leading-relaxed text-[#FDFBF7]/70 max-w-[280px]">
                    Tu librería de confianza para descubrir historias maravillosas y conocimiento sin límites. Libros físicos al mejor precio.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-bold text-white">Enlaces Rápidos</h3>
                <div class="flex flex-col gap-2.5 text-sm text-[#FDFBF7]/70">
                    <a href="{{ route('libros.index') }}" class="hover:text-white transition-colors">Todos los Libros</a>
                    <a href="{{ route('libros.novedades') }}" class="hover:text-white transition-colors">Novedades</a>
                    <a href="{{ route('libros.populares') }}" class="hover:text-white transition-colors">Populares</a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-bold text-white">Sobre Nosotros</h3>
                <div class="flex flex-col gap-2.5 text-sm text-[#FDFBF7]/70">
                    <a href="#" class="hover:text-white transition-colors">Quiénes somos</a>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="font-serif text-lg font-bold text-white">Contacto</h3>
                <div class="flex flex-col gap-3.5 text-sm text-[#FDFBF7]/70">
                    <div class="flex items-center gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span>+51 967 750 523</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <a href="mailto:contacto@bookshop.pe" class="hover:text-white transition-colors">contacto@bookshop.pe</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 flex flex-col gap-4 text-xs text-[#FDFBF7]/60">
            <div class="flex flex-wrap items-center gap-2">
                <span>Aceptamos:</span>
                <span class="bg-white/10 text-white font-bold px-2.5 py-1 rounded">VISA</span>
                <span class="bg-white/10 text-white font-bold px-2.5 py-1 rounded">BCP</span>
                <span class="bg-white/10 text-white font-bold px-2.5 py-1 rounded">PAGO EFECTIVO</span>
            </div>
            <p>&copy; {{ date('Y') }} BookShop Perú. Todos los derechos reservados. Precios en Soles (S/).</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // MENÚ MÓVIL HAMBURGUESA
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

            // DROPDOWN DE USUARIO (CLICK INTERACTIVO)
            const dropdownBtn = document.getElementById('user-dropdown-btn');
            const dropdownMenu = document.getElementById('user-dropdown-menu');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>