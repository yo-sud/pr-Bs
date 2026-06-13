<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function inicio(): View
    {
        return view('welcome', [
            'categorias' => Categoria::query()
                ->withCount(['libros' => fn ($query) => $query->activos()])
                ->orderByDesc('libros_count')
                ->limit(6)
                ->get(),
            'destacados' => Libro::query()
                ->activos()
                ->with('categoria')
                ->where('destacado', true)
                ->orderByDesc('ventas')
                ->limit(6)
                ->get(),
            'totalLibros' => Libro::query()->activos()->count(),
            'totalCategorias' => Categoria::query()->has('libros')->count(),
        ]);
    }

    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'integer', 'exists:categorias,id'],
            'orden' => ['nullable', 'in:recientes,precio_asc,precio_desc,populares,titulo'],
        ]);

        $libros = Libro::query()
            ->activos()
            ->with('categoria')
            ->when($filtros['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('titulo', 'like', "%{$search}%")
                        ->orWhere('autor', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when($filtros['categoria'] ?? null, fn ($query, int $categoria) => $query->where('categoria_id', $categoria));

        $this->aplicarOrden($libros, $filtros['orden'] ?? 'recientes');

        return view('libros.index', [
            'libros' => $libros->paginate(12)->withQueryString(),
            'categorias' => Categoria::query()->orderBy('nombre')->get(),
        ]);
    }

    public function novedades(): View
    {
        return view('libros.novedades', [
            'libros' => Libro::query()
                ->activos()
                ->with('categoria')
                ->orderByDesc('fecha_publicacion')
                ->orderByDesc('id')
                ->paginate(12),
        ]);
    }

    public function populares(): View
    {
        return view('libros.populares', [
            'libros' => Libro::query()
                ->activos()
                ->with('categoria')
                ->orderByDesc('ventas')
                ->orderBy('titulo')
                ->paginate(12),
        ]);
    }

    public function show(Libro $libro): View
    {
        abort_unless($libro->estado === 'activo', 404);

        $libro->load(['categoria', 'proveedor']);

        return view('libros.show', [
            'libro' => $libro,
            'relacionados' => Libro::query()
                ->activos()
                ->with('categoria')
                ->where('categoria_id', $libro->categoria_id)
                ->whereKeyNot($libro->id)
                ->orderByDesc('ventas')
                ->limit(4)
                ->get(),
        ]);
    }

    public function quienesSomos(): View
    {
        return view('quienes-somos');
    }

    private function aplicarOrden($query, string $orden): void
    {
        match ($orden) {
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            'populares' => $query->orderByDesc('ventas'),
            'titulo' => $query->orderBy('titulo'),
            default => $query->orderByDesc('fecha_publicacion')->orderByDesc('id'),
        };
    }
}