<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            
            <a class="inline-flex items-center gap-2 text-amber-800 hover:text-amber-900 mb-6 transition-colors" href="/" data-discover="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-5 h-5">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                <span>Volver al inicio</span>
            </a>

            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-amber-100">
                
                <div class="flex items-center justify-center gap-2 mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-10 h-10 text-amber-700">
                        <path d="M12 7v14"></path>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    <span class="font-serif text-3xl font-bold text-amber-900">BookShop</span>
                </div>

                <div class="text-center mb-8">
                    <h1 class="font-serif text-3xl font-bold text-amber-900 mb-2">Crear Cuenta</h1>
                    <p class="text-stone-500">Únete a nuestra comunidad de lectores</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Nombre Completo</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Tu nombre" class="w-full pl-12 pr-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-amber-50/50">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="tu@email.com" class="w-full pl-12 pr-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-amber-50/50">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Contraseña</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" class="w-full pl-12 pr-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-amber-50/50">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-2">Confirmar Contraseña</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-stone-400"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña" class="w-full pl-12 pr-4 py-3 border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all bg-amber-50/50">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-600" />
                    </div>

                    <button type="submit" class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-lg transition-all hover:shadow-lg flex items-center justify-center gap-2 mt-6">
                        <span>Crear Cuenta</span>
                    </button>
                </form>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-amber-100"></div></div>
                    <div class="relative flex justify-center text-sm"><span class="px-4 bg-white text-stone-400">o</span></div>
                </div>

                <div class="text-center">
                    <p class="text-stone-600">¿Ya tienes una cuenta? 
                        <a class="text-amber-700 hover:text-amber-800 font-medium transition-colors" href="{{ route('login') }}">Inicia sesión aquí</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>