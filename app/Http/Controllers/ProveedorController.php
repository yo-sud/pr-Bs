<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    public function index(): View
    {
        return view('admin.proveedores.index', [
            'proveedores' => Proveedor::query()->withCount('libros')->orderBy('nombre')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Autorización integrada (Solo administradores)
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación integrada
        $datosValidados = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo'   => [
                'nullable',
                'email',
                'max:100',
                'unique:proveedores,correo',
            ],
        ]);

        Proveedor::query()->create($datosValidados);

        return back()->with('status', 'Proveedor creado correctamente.');
    }

    public function update(Request $request, Proveedor $proveedor): RedirectResponse
    {
        // Autorización integrada (Solo administradores)
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación integrada ignorando el ID del proveedor actual
        $datosValidados = $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo'   => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('proveedores', 'correo')->ignore($proveedor->id),
            ],
        ]);

        $proveedor->update($datosValidados);

        return back()->with('status', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        if ($proveedor->libros()->exists()) {
            return back()->withErrors(['proveedor' => 'No se puede eliminar un proveedor con libros asociados.']);
        }

        $proveedor->delete();

        return back()->with('status', 'Proveedor eliminado correctamente.');
    }
}