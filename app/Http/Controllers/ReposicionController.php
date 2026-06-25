<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class ReposicionController extends Controller
{
    // Carga la interfaz del Paso 1
    public function paso1()
    {
        // 1. Rango de 30 días para promediar las ventas del semáforo
        $hace30Dias = Carbon::now()->subDays(30);

        // 2. Jalamos los libros sumando las cantidades vendidas en pedidos concretados
        $libros = Libro::withSum(['pedidoDetalles as unidades_vendidas' => function ($query) use ($hace30Dias) {
            $query->whereHas('pedido', function ($q) {
                $q->whereIn('estado', ['completado', 'pagado']); 
            })->where('created_at', '>=', $hace30Dias);
        }], 'cantidad')->get();

        // 3. Calculamos las ventas diarias promedio
        foreach ($libros as $libro) {
            $totalUnidades = $libro->unidades_vendidas ?? 0;
            $libro->ventas_diarias = round($totalUnidades / 30, 2);
        }

        // 📊 Datos informativos para las tarjetas superiores
        $totalLibros = $libros->count();
        $librosEnSesion = session('reposicion.libros', []);
        $totalSeleccionados = count($librosEnSesion);
        $inversionEstimada = 0; 

        return view('admin.inventario.reposicion.paso1', compact(
            'libros', 
            'totalLibros', 
            'totalSeleccionados', 
            'inversionEstimada'
        ));
    }

    // Recibe el formulario y guarda las elecciones en la Sesión
    public function procesarPaso1(Request $request)
    {
        $request->validate([
            'libros' => 'required|array|min:1',
            'cantidades' => 'required|array'
        ]);

        // Guardamos de forma invisible en la sesión del servidor
        session([
            'reposicion.libros' => $request->libros,       // Array de IDs seleccionados
            'reposicion.cantidades' => $request->cantidades // Array asociativo [ID => Cantidad]
        ]);

        return redirect()->route('admin.reposicion.paso2');
    }

    // Pantalla temporal del Paso 2
    public function paso2()
    {
        if (!session()->has('reposicion.libros')) {
            return redirect()->route('admin.reposicion.paso1');
        }
        return "¡Éxito total! Los datos del Paso 1 se guardaron correctamente en la sesión. Listos para programar la pantalla de Proveedores.";
    }
}