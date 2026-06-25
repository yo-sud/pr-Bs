<section>
    {{-- Se eliminó el header duplicado en inglés para mantener la consistencia visual --}}

    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
        
        @method('put')

        {{-- Campo: Contraseña Actual --}}
        <div>
            <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Campo: Nueva Contraseña --}}
        <div>
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Campo: Confirmar Contraseña --}}
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar nueva contraseña')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" placeholder="Repite la contraseña" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Botón de Guardar y Estado de éxito --}}
        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Actualizar Contraseña</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 font-medium"
                >✓ Contraseña actualizada.</p>
            @endif
        </div>
    </form>
</section>