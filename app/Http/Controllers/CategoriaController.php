<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index(): View
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::query()->withCount('libros')->orderBy('nombre')->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        $datosValidados = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre'),
            ],
        ]);

        Categoria::query()->create($datosValidados);

        return redirect()->route('admin.categorias')->with('status', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        $datosValidados = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ]);

        $categoria->update($datosValidados);

        return back()->with('status', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        $categoria->update([
            'activo' => !$categoria->activo
        ]);

        $mensaje = $categoria->activo ? 'Categoría activada correctamente.' : 'Categoría desactivada correctamente.';

        return back()->with('status', $mensaje);
    }
}