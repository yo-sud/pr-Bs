<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\MovimientoInventario;
use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminLibroController extends Controller
{
    public function index(): View
    {
        return view('admin.libros.index', [
            'libros' => Libro::query()
                ->with(['categoria', 'proveedor'])
                ->when(request('search'), function ($query, string $search) {
                    $query->where(fn ($query) => $query
                        ->where('titulo', 'like', "%{$search}%")
                        ->orWhere('autor', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%"));
                })
                ->when(request('stock') === 'bajo', fn ($query) => $query->where('stock', '<=', 5))
                ->orderBy('titulo')
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.libros.create', $this->catalogos());
    }

    public function store(Request $request): RedirectResponse
    {
        // Autorización integrada
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación integrada
        $datos = $request->validate([
            'isbn' => ['nullable', 'string', 'max:20', 'unique:libros,isbn'],
            'titulo' => ['required', 'string', 'max:150'],
            'autor' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'editorial' => ['nullable', 'string', 'max:100'],
            'fecha_publicacion' => ['nullable', 'date'],
            'portada' => ['nullable', 'image', 'max:2048'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'estado' => ['required', 'in:activo,inactivo'],
            'destacado' => ['nullable', 'boolean'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
        ]);

        $datos['destacado'] = $request->boolean('destacado');
        $datos['portada'] = $request->file('portada')?->store('portadas', 'public');

        $libro = DB::transaction(function () use ($datos, $request) {
            $libro = Libro::query()->create($datos);

            if ($libro->stock > 0) {
                MovimientoInventario::query()->create([
                    'libro_id' => $libro->id,
                    'user_id' => $request->user()->id,
                    'tipo' => 'entrada',
                    'cantidad' => $libro->stock,
                    'stock_anterior' => 0,
                    'stock_nuevo' => $libro->stock,
                    'motivo' => 'Stock inicial del libro',
                ]);
            }

            return $libro;
        });

        return redirect()->route('admin.libros.edit', $libro)
            ->with('status', 'Libro creado correctamente.');
    }

    public function edit(Libro $libro): View
    {
        return view('admin.libros.edit', [
            ...$this->catalogos(),
            'libro' => $libro,
            'movimientos' => $libro->movimientosInventario()
                ->with('usuario')
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }


    public function update(Request $request, Libro $libro): RedirectResponse
    {
        // 1. Autorización integrada
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validación integrada
        $datos = $request->validate([
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('libros', 'isbn')->ignore($libro->id),
            ],
            'titulo' => ['required', 'string', 'max:150'],
            'autor' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'editorial' => ['nullable', 'string', 'max:100'],
            'fecha_publicacion' => ['nullable', 'date'],
            'portada' => ['nullable', 'image', 'max:2048'],
            'precio' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'estado' => ['required', 'in:activo,inactivo'],
            'destacado' => ['nullable', 'boolean'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
        ]);

        $datos['destacado'] = $request->boolean('destacado');

        if ($request->hasFile('portada')) {
            if ($libro->portada) {
                Storage::disk('public')->delete($libro->portada);
            }

            $datos['portada'] = $request->file('portada')->store('portadas', 'public');
        }

        $libro->update($datos);

        return back()->with('status', 'Libro actualizado correctamente.');
    }

    public function destroy(Libro $libro): RedirectResponse
    {
        if (!auth()->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        $libro->update(['estado' => 'inactivo']);

        return redirect()->route('admin.libros.index')
            ->with('status', 'Libro desactivado correctamente.');
    }

    public function ajustarStock(Request $request, Libro $libro): RedirectResponse
    {
        // 1. Autorización integrada
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Validación integrada
        $datosValidados = $request->validate([
            'cantidad' => ['required', 'integer', 'not_in:0', 'between:-1000000,1000000'],
            'motivo'   => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $datosValidados, $libro) {
            $libro = Libro::query()->lockForUpdate()->findOrFail($libro->id);
            $anterior = $libro->stock;
            $nuevo = $anterior + (int) $datosValidados['cantidad'];

            if ($nuevo < 0) {
                abort(422, 'El ajuste dejaría el stock en un valor negativo.');
            }

            $libro->update(['stock' => $nuevo]);

            MovimientoInventario::query()->create([
                'libro_id' => $libro->id,
                'user_id' => $request->user()->id,
                'tipo' => (int) $datosValidados['cantidad'] > 0 ? 'entrada' : 'correccion',
                'cantidad' => (int) $datosValidados['cantidad'],
                'stock_anterior' => $anterior,
                'stock_nuevo' => $nuevo,
                'motivo' => $datosValidados['motivo'],
            ]);
        });

        return back()->with('status', 'Stock actualizado correctamente.');
    }

    private function catalogos(): array
    {
        return [
            'categorias' => Categoria::query()->orderBy('nombre')->get(),
            'proveedores' => Proveedor::query()->orderBy('nombre')->get(),
        ];
    }
}