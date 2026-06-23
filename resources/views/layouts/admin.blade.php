<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookShop - Administración')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FBF8F3] font-sans antialiased min-h-screen text-gray-800">

    <div class="min-h-screen flex flex-col lg:flex-row">
        
        {{-- HEADER MÓVIL: Mantiene la barra superior en pantallas pequeñas --}}
        <div class="bg-[#1F130B] text-white h-16 flex items-center justify-between px-4 lg:hidden w-full fixed top-0 left-0 z-50 border-b border-[#3D281C]">
            <div class="flex items-center">
                {{-- Corrección 1: Icono del libro en color naranja/terracota cálido --}}
                <span class="material-symbols-outlined text-[#e07a16] mr-2 text-2xl">menu_book</span>
                <span class="font-bold tracking-wider text-sm">BookShop Admin</span>
            </div>
            
            <button id="btn-menu" class="text-white hover:text-[#e07a16] focus:outline-none">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
        </div>

        {{-- ASIDE / BARRA LATERAL: El menú izquierdo principal --}}
        <aside id="menu-lateral" class="bg-[#2C1B12] text-white w-64 fixed inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-[#1F130B] flex flex-col pt-16 lg:pt-0">

            {{-- LOGO DE ESCRITORIO --}}
            <div class="h-24 hidden lg:flex items-center px-6 border-b border-[#2A190E] bg-[#150B05]">
                <div class="w-10 h-10 bg-[#e07a16] rounded-xl flex items-center justify-center mr-3 shadow-md">
                    <span class="material-symbols-outlined text-white text-2xl">menu_book</span>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight text-white leading-tight">BookShop</h1>
                    <p class="text-xs text-[#e07a16] font-bold uppercase tracking-widest text-[10px]">Panel Admin</p>
                </div>
            </div>

            <div class="px-6 py-5 border-b border-[#2A190E] bg-[#1A0F08] flex items-center space-x-3">
                {{-- Foto circular con borde dorado --}}
                <div class="relative flex-shrink-0">
                        {{-- Ponemos un avatar genérico pulcro por si no tienes imagen real en la BD --}}
                        <div class="w-12 h-12 rounded-full bg-[#E8DED5] text-[#1A0F08] flex items-center justify-center border-2 border-[#e07a16] shadow-md">
                            <span class="material-symbols-outlined text-2xl font-bold">person</span>
                        </div>
                </div>
                {{-- Nombre y la etiqueta naranja de la imagen --}}
                <div class="overflow-hidden flex-1">
                    <p class="text-sm font-bold text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                    <div class="mt-1">
                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-white bg-[#e07a16] rounded-full shadow-sm">
                            Administrador
                        </span>
                    </div>
                </div>
            </div>

            {{-- BLOQUE DEL USUARIO AUTENTICADO --}}
            {{-- Corrección 3: Color de fondo adaptado para armonizar mejor con la barra lateral --}}
            <div class="px-6 py-4 border-b border-[#3D281C] bg-[#21140d] lg:bg-transparent">
                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-[#A89284] truncate">{{ auth()->user()->email }}</p>
            </div>

            {{-- CONFIGURACIÓN DE ESTILOS DEL MENÚ --}}
            @php
                // Corrección 4: Botón activo ahora es naranja/terracota con texto blanco y bordes más redondos (rounded-xl)
                $link = 'flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200';
                $active = 'bg-[#e07a16] text-white shadow-md'; 
                $inactive = 'text-[#e6d5c3] hover:bg-[#3D281C] hover:text-[#e3a857]';
            @endphp

            {{-- NAVEGACIÓN PRINCIPAL --}}
            <nav class="p-4 space-y-1 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="{{ $link }} {{ request()->routeIs('admin.dashboard') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.libros.index') }}" class="{{ $link }} {{ request()->routeIs('admin.libros.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">book_2</span> Libros
                </a>
                <a href="{{ route('admin.inventario.index') }}" class="{{ $link }} {{ request()->routeIs('admin.inventario.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">inventory_2</span> Inventario
                </a>
                <a href="{{ route('admin.categorias.index') }}" class="{{ $link }} {{ request()->routeIs('admin.categorias.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">category</span> Categorías
                </a>
                <a href="{{ route('admin.usuarios.index') }}" class="{{ $link }} {{ request()->routeIs('admin.usuarios.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">group</span> Usuarios
                </a>
                <a href="{{ route('admin.proveedores.index') }}" class="{{ $link }} {{ request()->routeIs('admin.proveedores.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">local_shipping</span> Proveedores
                </a>
                <a href="{{ route('admin.repartidores.index') }}" class="{{ $link }} {{ request()->routeIs('admin.repartidores.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">delivery_dining</span> Repartidores
                </a>
                <a href="{{ route('admin.pedidos.index') }}" class="{{ $link }} {{ request()->routeIs('admin.pedidos.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">receipt_long</span> Pedidos
                </a>
                
                {{-- BOTÓN CERRAR SESIÓN --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="{{ $link }} {{ $inactive }} w-full text-left">
                        <span class="material-symbols-outlined mr-2 text-xl">logout</span> Cerrar sesión
                    </button>
                </form>
            </nav>
        </aside>

        {{-- FONDO OSCURO PARA MENÚ MÓVIL --}}
        <div id="fondo-menu" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

        {{-- CONTENEDOR PRINCIPAL DE LAS VISTAS (@yield) --}}
        <main class="flex-1 lg:ml-64 p-4 md:p-8 pt-24 lg:pt-8 w-full">
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Aquí se inyecta el index, create, edit, etc. --}}
            @yield('contenido')
        </main>
    </div>

    {{-- SCRIPT INTERACTIVO DEL MENÚ RESPONSIVO --}}
    <script>
        const btnMenu = document.getElementById('btn-menu');
        const menuLateral = document.getElementById('menu-lateral');
        const fondoMenu = document.getElementById('fondo-menu');

        function toggleMenu() {
            menuLateral.classList.toggle('-translate-x-full');
            fondoMenu.classList.toggle('hidden');
        }

        btnMenu.addEventListener('click', toggleMenu);
        fondoMenu.addEventListener('click', toggleMenu);
    </script>
</body>
</html>