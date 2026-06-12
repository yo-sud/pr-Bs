<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use Illuminate\Contracts\View\View;

class AdminInventarioController extends Controller
{
    public function index(): View
    {
        return view('admin.inventario.index', [
            'movimientos' => MovimientoInventario::query()
                ->with(['libro', 'usuario'])
                ->latest()
                ->paginate(20)
                ->withQueryString(),
        ]);
    }
}
