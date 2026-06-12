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
        
        <div class="bg-[#1F130B] text-white h-16 flex items-center justify-between px-4 lg:hidden w-full fixed top-0 left-0 z-50 border-b border-[#3D281C]">
            <div class="flex items-center">
                <span class="material-symbols-outlined text-[#D4A373] mr-2 text-2xl">menu_book</span>
                <span class="font-bold tracking-wider text-sm">BookShop Admin</span>
            </div>
            
            <button id="btn-menu" class="text-white hover:text-[#D4A373] focus:outline-none">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
        </div>

        <aside id="menu-lateral" class="bg-[#2C1B12] text-white w-64 fixed inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-[#1F130B] flex flex-col pt-16 lg:pt-0">

            <div class="h-20 hidden lg:flex items-center px-6 bg-[#1F130B] border-b border-[#3D281C]">
                <span class="material-symbols-outlined text-[#D4A373] mr-3 text-3xl">menu_book</span>
                <div>
                    <h1 class="text-base font-bold tracking-wider">BookShop</h1>
                    <p class="text-xs text-[#A89284] uppercase tracking-widest">Panel Admin</p>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-[#3D281C] bg-[#24160E] lg:bg-transparent">
                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-[#A89284] truncate">{{ auth()->user()->email }}</p>
            </div>

            @php
                $link = 'flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors';
                $active = 'bg-[#D4A373] text-[#2C1B12]';
                $inactive = 'text-[#C8B8AE] hover:bg-[#3D281C] hover:text-white';
            @endphp

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
                <a href="{{ route('admin.pedidos.index') }}" class="{{ $link }} {{ request()->routeIs('admin.pedidos.*') ? $active : $inactive }}">
                    <span class="material-symbols-outlined mr-2 text-xl">receipt_long</span> Pedidos
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="{{ $link }} {{ $inactive }} w-full text-left">
                        <span class="material-symbols-outlined mr-2 text-xl">logout</span> Cerrar sesión
                    </button>
                </form>
            </nav>
        </aside>

        <div id="fondo-menu" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

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

            @yield('contenido')
        </main>
    </div>

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