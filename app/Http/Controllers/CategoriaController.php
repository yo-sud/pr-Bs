<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    /**
     * Muestra el listado de categorías.
     */
    public function index(): View
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::query()->withCount('libros')->orderBy('nombre')->paginate(15),
        ]);
    }

    /**
     * Reemplaza la lógica de creación de CategoriaRequest
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validar autorización (En reemplazo del método authorize())
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validar los datos internamente
        $datosValidados = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre'),
            ],
        ]);

        // 3. Crear el registro
        Categoria::query()->create($datosValidados);

        return back()->with('status', 'Categoría creada correctamente.');
    }

    /**
     * Reemplaza la lógica de edición de CategoriaRequest
     */
    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        // 1. Validar autorización
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validar ignorando el ID de la categoría actual
        $datosValidados = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ]);

        // 3. Actualizar el registro
        $categoria->update($datosValidados);

        return back()->with('status', 'Categoría actualizada correctamente.');
    }

    /**
     * Alterna el estado (Activo/Inactivo) de una categoría.
     */
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