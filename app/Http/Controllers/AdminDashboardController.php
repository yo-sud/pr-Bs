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
use Illuminate\Support\Facades\DB;

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
        $pedidosAnioActual = Pedido::where('estado_pedido', 'pagado')->whereYear('created_at', date('Y'))
            ->select(DB::raw('MONTH(created_at) as numero_mes'), DB::raw('SUM(total) as suma_dinero'))
            ->groupBy('numero_mes')->get();
        $ventasmensuales = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $busquedaMes = $pedidosAnioActual->firstWhere('numero_mes', $mes);
            if ($busquedaMes) {
                $ventasmensuales[] = $busquedaMes->suma_dinero;
            } else {
                $ventasmensuales[] = 0;
            }
        }

        $LibrosStockBajo = Libro::with('categoria')
            ->where('estado', 'activo')->where('stock', '<=', 5)
            ->orderBy('stock')->limit(8)->get();
        
        return view('admin.dashboard', compact(
            'totalventas', 'totalLibros', 'stockTotal',
            'categorias', 'proveedores', 'repartidores', 'usuarios', 'LibrosStockBajo',
            'ventasmensuales'
        ));
    }
}
