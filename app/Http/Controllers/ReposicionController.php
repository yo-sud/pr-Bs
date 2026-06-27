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
        ]);

        session(['reposicion.libros' => $request->libros]);

        return redirect()->route('admin.reposicion.paso2');
    }
    
    public function segundopaso()
    {
        // 1. Validar que existan datos en la sesión
        if (!session()->has('reposicion.libros')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosIds = session('reposicion.libros');

        $resumen = [
            'titulos'   => count($librosIds),
            'unidades'  => count($librosIds),
        ];

        // 3. Traer todos los proveedores con sus datos logísticos
        $proveedores = \App\Models\Proveedor::all();

        // 4. Renderizar la vista con el diseño idéntico
        return view('admin.inventario.reposicioninteligente.segundopaso', compact('resumen', 'proveedores'));
    }

    public function procesarpaso2(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
        ]);

        session(['reposicion.proveedor_id' => $request->proveedor_id]);

        return redirect()->route('admin.reposicion.paso3');
    }

    public function tercerpaso()
    {
        if (!session()->has('reposicion.libros') || !session()->has('reposicion.proveedor_id')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosIds    = session('reposicion.libros');
        $proveedorId  = session('reposicion.proveedor_id');

        $proveedor = \App\Models\Proveedor::findOrFail($proveedorId);
        $librosBD  = Libro::whereIn('id', $librosIds)->get();

        $unidadesTotales  = $librosBD->count();
        $costoLibrosTotal = $librosBD->sum(fn($libro) => floatval($libro->precio));

        $costoEnvioReal          = floatval($proveedor->costo_envio);
        $diasEntregaReal         = intval($proveedor->tiempo_entrega_dias);
        $inversionFinalCalculada = $costoLibrosTotal + $costoEnvioReal;

        $opcionRapida = [
            'inversion_total'  => $inversionFinalCalculada,
            'entrega_promedio' => $diasEntregaReal,
            'costo_envio'      => $costoEnvioReal,
            'unidades_totales' => $unidadesTotales,
            'proveedor_nombre' => $proveedor->nombre_empresa,
            'costo_libros'     => $costoLibrosTotal,
        ];

        $opcionEconomica = [
            'inversion_total'  => $inversionFinalCalculada,
            'entrega_promedio' => $diasEntregaReal,
            'costo_envio'      => $costoEnvioReal,
            'unidades_totales' => $unidadesTotales,
            'proveedor_nombre' => $proveedor->nombre_empresa,
            'costo_libros'     => $costoLibrosTotal,
        ];

        return view('admin.inventario.reposicioninteligente.tercerpaso', compact('opcionRapida', 'opcionEconomica'));
    }

    public function procesarpaso3(Request $request)
    {
        $request->validate([
            'estrategia' => 'required|in:rapida,economica',
        ]);

        session(['reposicion.estrategia' => $request->estrategia]);

        return redirect()->route('admin.reposicion.paso4');
    }

    public function cuartopaso()
    {
        if (!session()->has('reposicion.libros') || !session()->has('reposicion.proveedor_id') || !session()->has('reposicion.estrategia')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosIds   = session('reposicion.libros');
        $proveedorId = session('reposicion.proveedor_id');
        $estrategia  = session('reposicion.estrategia');

        $proveedor = \App\Models\Proveedor::findOrFail($proveedorId);
        $librosBD  = Libro::whereIn('id', $librosIds)->get();

        $itemsSeleccionados = $librosBD->map(fn($libro) => [
            'titulo'   => $libro->titulo,
            'autor'    => $libro->autor,
            'cantidad' => 1,
            'subtotal' => floatval($libro->precio),
        ])->values()->all();

        $costoLibrosTotal = $librosBD->sum(fn($libro) => floatval($libro->precio));
        $costoEnvio       = floatval($proveedor->costo_envio);
        $inversionTotal   = $costoLibrosTotal + $costoEnvio;
        $fechaEstimada    = Carbon::now()->addDays($proveedor->tiempo_entrega_dias);

        $resumenFinal = [
            'inversion_total'  => $inversionTotal,
            'costo_envio'      => $costoEnvio,
            'costo_libros'     => $costoLibrosTotal,
            'fecha_entrega'    => $fechaEstimada->translatedFormat('d \d\e M. Y'),
            'dias_habiles'     => $proveedor->tiempo_entrega_dias,
            'total_unidades'   => $librosBD->count(),
            'total_titulos'    => $librosBD->count(),
            'proveedor_nombre' => $proveedor->nombre_empresa,
            'estrategia_texto' => $estrategia === 'rapida' ? 'Opción Más Rápida' : 'Opción Más Económica',
            'libros'           => $itemsSeleccionados,
        ];

        return view('admin.inventario.reposicioninteligente.cuartopaso', compact('resumenFinal'));
    }

    // Acción final: Guarda las órdenes de compra reales y limpia la sesión
    public function confirmarOrdenes(Request $request)
    {
        $request->validate([
            'numero_orden' => 'required|string|max:30',
            'metodo_pago'  => 'required|in:transferencia,credito_30,credito_60,efectivo',
        ]);

        $metodoTexto = [
            'transferencia' => 'Transferencia bancaria',
            'credito_30'    => 'Crédito a 30 días',
            'credito_60'    => 'Crédito a 60 días',
            'efectivo'      => 'Efectivo',
        ][$request->metodo_pago];

        session([
            'reposicion.orden_confirmada' => [
                'numero_orden' => $request->numero_orden,
                'metodo_pago'  => $metodoTexto,
            ],
        ]);

        session()->forget(['reposicion.libros', 'reposicion.proveedor_id', 'reposicion.estrategia']);

        return redirect()->route('admin.reposicion.confirmado');
    }

    public function confirmado()
    {
        $orden = session('reposicion.orden_confirmada', [
            'numero_orden' => '—',
            'metodo_pago'  => '—',
        ]);

        session()->forget('reposicion.orden_confirmada');

        return view('admin.inventario.reposicioninteligente.confirmado', compact('orden'));
    }
}