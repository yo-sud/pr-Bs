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

        return back()->with('status', 'Categoría creada correctamente.');
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

<<<<<<< HEAD
=======
    /**
     * Alterna el estado (Activo/Inactivo) de una categoría.
     */
>>>>>>> 4f17f5651f5369a6f1fcc6419532c80ffbb46648
    public function destroy(Categoria $categoria): RedirectResponse
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Cambia el estado al opuesto actual (si es 1 pasa a 0, si es 0 pasa a 1)
        $categoria->update([
            'activo' => !$categoria->activo
        ]);

        $mensaje = $categoria->activo ? 'Categoría activada correctamente.' : 'Categoría desactivada correctamente.';

        return back()->with('status', $mensaje);
    }
}