<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
  
    public function create(): View
    {
        return view('auth.login');
    }


    public function store(Request $request): RedirectResponse
    {
        // Validamos que el email y la contraseña no estén vacíos
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Intentamos el login con el email, la contraseña y el "recuérdame"
        $credenciales = $request->only('email', 'password');
        $recordar = $request->boolean('remember');

        if (! Auth::attempt($credenciales, $recordar)) {
            // Si las credenciales fallan, lanzamos un error en el campo email
            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
        }

        // Si el login es correcto, regeneramos la sesión por seguridad
        $request->session()->regenerate();

        // Tu filtro de roles (Admin / Cliente)
        if (Auth::user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('libros.index', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate(); // Borra los datos de la sesión actual
        $request->session()->regenerateToken(); // Cambia el token de seguridad de la página

        return redirect('/');
    }
}