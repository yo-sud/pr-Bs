<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class ReposicionController extends Controller
{
    // Carga la interfaz del Paso 1
    public function primerpaso()
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

        // Abre tu archivo físico en admin/inventario/reposicioninteligente/primerpaso.blade.php
        return view('admin.inventario.reposicioninteligente.primerpaso', compact(
            'libros', 
            'totalLibros', 
            'totalSeleccionados', 
            'inversionEstimada'
        ));
    }

    // Recibe el formulario y guarda las elecciones en la Sesión (todo en minúsculas)
    public function procesarpaso1(Request $request)
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

        // Redirecciona al paso2
        return redirect()->route('admin.reposicion.paso2');
    }
    
    public function segundopaso()
    {
        // 1. Validar que existan datos en la sesión
        if (!session()->has('reposicion.libros')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosIds = session('reposicion.libros');
        $cantidades = session('reposicion.cantidades');

        // 2. Calcular los totales para la tarjeta informativa superior
        $totalTitulos = 0;
        $totalUnidades = 0;

        foreach ($librosIds as $id) {
            $cant = intval($cantidades[$id] ?? 0);
            if ($cant > 0) {
                $totalTitulos++;
                $totalUnidades += $cant;
            }
        }

        // Si no seleccionó cantidades válidas, regresar al paso 1
        if ($totalTitulos === 0) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $resumen = [
            'titulos' => $totalTitulos,
            'unidades' => $totalUnidades
        ];

        // 3. Traer todos los proveedores con sus datos logísticos
        $proveedores = \App\Models\Proveedor::all();

        // 4. Renderizar la vista con el diseño idéntico
        return view('admin.inventario.reposicioninteligente.segundopaso', compact('resumen', 'proveedores'));
    }
}