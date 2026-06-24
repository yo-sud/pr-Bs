<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Pedido;
use App\Models\MovimientoInventario;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Repartidor;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalventas = Pedido::where('estado_pedido', 'pagado') ->sum('total');
        $totalLibros = Libro::count();
        $stockTotal = Libro::sum('stock');
        $categorias = Categoria::count();
        $proveedores = Proveedor::count();
        $repartidores = Repartidor::count();
        $usuarios = User::count();

        $LibrosStockBajo = Libro::with('categoria')
            ->where('estado', 'activo')->where('stock', '<=', 5)
            ->orderBy('stock')->limit(8)->get();
        
        return view('admin.dashboard', compact(
            'totalventas', 'totalLibros', 'stockTotal',
            'categorias', 'proveedores', 'repartidores', 'usuarios', 'LibrosStockBajo'
        ));
    }
}
