@extends('layouts.app')

@section('title', 'BookShop - Mi Perfil')

@section('content')
<main class="w-full flex-grow bg-[#FDFBF7] pb-12 font-sans">
    
    {{-- 1. CABECERA SUPERIOR CÁLIDA --}}
    <div class="bg-[#FFF9EE] border-b border-[#B8500C]/10 px-[7%] py-10 mb-8">
        <div class="max-w-6xl mx-auto flex items-center gap-4">
            {{-- Avatar circular con inicial del usuario --}}
            <div class="h-16 w-16 rounded-full bg-[#B8500C] text-white flex items-center justify-center font-bold text-2xl shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-[#421605] leading-tight">{{ Auth::user()->name }}</h1>
                <p class="text-sm text-[#8A7A71] mt-0.5">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    {{-- 2. CUERPO EN DOS COLUMNAS CON ESTILOS CÁLIDOS INYECTADOS --}}
    <div class="px-[7%] max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6
                [&_label]:text-[#554138] [&_label]:font-semibold [&_label]:text-xs [&_label]:mb-1.5 [&_label]:block
                [&_input]:rounded-xl [&_input]:border-[#421605]/15 [&_input]:text-sm [&_input]:bg-[#FDFBF7]/50 
                [&_input:focus]:border-[#B8500C] [&_input:focus]:ring-[#B8500C]
                [&_button[type=submit]]:bg-[#B8500C] [&_button[type=submit]]:hover:bg-[#963F07] [&_button[type=submit]]:text-white 
                [&_button[type=submit]]:text-xs [&_button[type=submit]]:font-semibold [&_button[type=submit]]:px-5 
                [&_button[type=submit]]:py-2.5 [&_button[type=submit]]:rounded-full [&_button[type=submit]]:transition-colors 
                [&_button[type=submit]]:shadow-sm [&_button[type=submit]]:uppercase [&_button[type=submit]]:tracking-wider">
        
        {{-- COLUMNA IZQUIERDA: MENÚ LATERAL COMPACTO --}}
        <aside class="md:col-span-1 bg-white border border-[#B8500C]/10 rounded-2xl p-4 shadow-sm h-fit flex flex-col gap-2">
            <div class="space-y-1">
                {{-- Botón Activo: Mi Perfil --}}
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-3 text-sm font-semibold text-[#421605] bg-[#FFF9EE] border border-[#B8500C]/10 rounded-xl transition-all">
                    <svg class="w-4 h-4 text-[#B8500C]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Mi Perfil
                </a>

                {{-- Botón: Historial de Pedidos --}}
                <a href="{{ route('pedidos.index') }}" class="flex items-center gap-2.5 px-4 py-3 text-sm font-medium text-[#8A7A71] hover:text-[#B8500C] hover:bg-[#FFF9EE]/50 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Historial de Pedidos
                </a>
            </div>

            {{-- Botón: Cerrar Sesión --}}
            <form method="POST" action="{{ route('logout') }}" class="block w-full border-t border-[#421605]/5 pt-2 mt-1">
                
                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-all text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </aside>

        {{-- COLUMNA DERECHA: FORMULARIOS DE EDICIÓN --}}
        <section class="md:col-span-3 space-y-6">
            
            {{-- Bloque 1: Información Personal --}}
            <div class="bg-white border border-[#B8500C]/10 rounded-2xl p-6 md:p-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-[#B8500C]"></div>
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-[#421605]">Información Personal</h2>
                    <p class="text-sm text-[#8A7A71] mt-0.5">Gestiona los datos básicos de tu cuenta, seguridad y preferencias en BookShop.</p>
                </div>
                <div class="pt-2">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Bloque 2: Actualizar Contraseña --}}
            <div class="bg-white border border-[#B8500C]/10 rounded-2xl p-6 md:p-8 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-[#FFC107]"></div>
                <div class="mb-4">
                    <h2 class="text-lg font-bold text-[#421605]">Cambiar Contraseña</h2>
                    <p class="text-xs text-[#8A7A71] mt-0.5">Asegúrate de que tu cuenta utilice una contraseña larga y segura para proteger tu información.</p>
                </div>
                <div class="pt-2">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </section>

    </div>
</main>
@endsection