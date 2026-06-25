<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminInventarioController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $estado = $request->input('estado');

        $query = Libro::query()->with('categoria');

        $totales = [
            'disponibles' => (clone $query)->where('stock', '>=', 10)->count(),
            'bajo'        => (clone $query)->where('stock', '>', 0)->where('stock', '<', 10)->count(),
            'agotados'    => (clone $query)->where('stock', 0)->count(),
        ];

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhereHas('categoria', fn ($c) => $c->where('nombre', 'like', "%{$search}%"));
            });
        }

        match ($estado) {
            'disponibles' => $query->where('stock', '>=', 10),
            'bajo'        => $query->where('stock', '>', 0)->where('stock', '<', 10),
            'agotados'    => $query->where('stock', 0),
            default       => null,
        };

        $libros = $query->orderBy('titulo')->paginate(15)->withQueryString();

        return view('admin.inventario.index', compact('libros', 'totales', 'search', 'estado'));
    }
}
