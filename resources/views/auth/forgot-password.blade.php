<x-guest-layout>
    <div class="min-h-screen bg-[#FFFBF6] px-4 py-8">
        
        <a href="{{ route('login') }}" class="flex items-center text-[#9A5214] mb-12 max-w-sm mx-auto transition hover:opacity-80">
            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Volver al inicio de sesión') }}
        </a>

        <div class="max-w-sm mx-auto bg-white p-8 rounded-3xl shadow-xl shadow-[#A8643220]">
            
            <div class="w-full flex items-center justify-center gap-2 mb-10">
                <svg class="w-7 h-7 text-[#9A5214] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                   <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                   <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="text-xl font-semibold text-[#9A5214]" style="font-family: serif; letter-spacing: -0.3px;">BookShop</span>
            </div>

            <h1 class="w-full text-3xl font-bold text-center text-[#9A5214] mb-4" style="font-family: serif;">
                {{ __('¿Olvidaste tu contraseña?') }}
            </h1>
            
            <p class="text-center text-sm text-gray-600 mb-8 leading-relaxed">
                {{ __('No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y elegir una nueva.') }}
            </p>

            @if (session('status'))
                <div class="mb-6 text-sm text-green-600 text-center font-medium bg-green-50 p-3 rounded-xl border border-green-100">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-8">
                    <label for="email" class="block text-sm font-medium text-[#202124] mb-2">
                        {{ __('Correo Electrónico') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            placeholder="tu@email.com"
                            class="bg-[#FBFBFF] border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-[#C85002] focus:border-[#C85002] block w-full ps-11 p-3">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
                </div>

                <button type="submit" class="w-full text-white bg-[#C85002] hover:bg-[#A84201] focus:ring-4 focus:ring-orange-200 font-bold rounded-2xl text-base px-5 py-3.5 text-center transition">
                    {{ __('Enviar enlace de recuperación') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout> 