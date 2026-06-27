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

        //  Datos informativos para las tarjetas superiores
        $totalLibros = $libros->count();
        $librosEnSesion = session('reposicion.libros', []);
        $totalSeleccionados = count($librosEnSesion);
        $inversionEstimada = 0;
        foreach ($libros as $libro) {
            if (in_array($libro->id, $librosEnSesion)) {
                $cant = session("reposicion.cantidades.{$libro->id}", 1);
                $inversionEstimada += ($libro->precio * $cant);
            }
        } 

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
            'libros'       => 'required|array|min:1',
            'cantidades'   => 'nullable|array',
            'cantidades.*' => 'nullable|integer|min:1|max:999',
        ], [
            'libros.required' => 'Debes seleccionar al menos un libro.',
            'libros.min'      => 'Debes seleccionar al menos un libro.',
            'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
            'cantidades.*.min'     => 'La cantidad mínima es 1.',
            'cantidades.*.max'     => 'La cantidad máxima es 999.',
        ]);

        $cantidades = $request->cantidades ?? [];
        $librosConCantidad = [];
        foreach ($request->libros as $id) {
            $librosConCantidad[(int)$id] = max(1, (int)($cantidades[$id] ?? 1));
        }

        session(['reposicion.libros' => $librosConCantidad]);

        return redirect()->route('admin.reposicion.paso2');
    }
    
    public function segundopaso()
    {
        // 1. Validar que existan datos en la sesión
        if (!session()->has('reposicion.libros')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosData = session('reposicion.libros'); // [id => cantidad]

        $resumen = [
            'titulos'  => count($librosData),
            'unidades' => array_sum($librosData),
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
        ], [
            'proveedor_id.required' => 'Debes seleccionar un proveedor.',
            'proveedor_id.exists'   => 'El proveedor seleccionado no es válido.',
        ]);

        session(['reposicion.proveedor_id' => $request->proveedor_id]);

        return redirect()->route('admin.reposicion.paso3');
    }

    public function tercerpaso()
    {
        if (!session()->has('reposicion.libros') || !session()->has('reposicion.proveedor_id')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosData = session('reposicion.libros'); // [id => cantidad]
        $librosIds  = array_keys($librosData);

        $librosBD         = Libro::whereIn('id', $librosIds)->get();
        $unidadesTotales  = array_sum($librosData);
        $costoLibrosTotal = $librosBD->sum(fn($libro) => floatval($libro->precio) * ($librosData[$libro->id] ?? 1));

        $proveedores = \App\Models\Proveedor::all();
        if ($proveedores->isEmpty()) {
            return redirect()->route('admin.reposicion.paso2');
        }

        $proveedorRapido    = $proveedores->sortBy('tiempo_entrega_dias')->first();
        $proveedorEconomico = $proveedores->sortBy('costo_envio')->first();

        $opcionRapida = [
            'proveedor_id'     => $proveedorRapido->id,
            'inversion_total'  => $costoLibrosTotal + floatval($proveedorRapido->costo_envio),
            'entrega_promedio' => intval($proveedorRapido->tiempo_entrega_dias),
            'costo_envio'      => floatval($proveedorRapido->costo_envio),
            'unidades_totales' => $unidadesTotales,
            'proveedor_nombre' => $proveedorRapido->nombre_empresa,
            'costo_libros'     => $costoLibrosTotal,
        ];

        $opcionEconomica = [
            'proveedor_id'     => $proveedorEconomico->id,
            'inversion_total'  => $costoLibrosTotal + floatval($proveedorEconomico->costo_envio),
            'entrega_promedio' => intval($proveedorEconomico->tiempo_entrega_dias),
            'costo_envio'      => floatval($proveedorEconomico->costo_envio),
            'unidades_totales' => $unidadesTotales,
            'proveedor_nombre' => $proveedorEconomico->nombre_empresa,
            'costo_libros'     => $costoLibrosTotal,
        ];

        return view('admin.inventario.reposicioninteligente.tercerpaso', compact('opcionRapida', 'opcionEconomica'));
    }

    public function procesarpaso3(Request $request)
    {
        $request->validate([
            'estrategia'             => 'required|in:rapida,economica',
            'proveedor_id_rapida'    => 'required|exists:proveedores,id',
            'proveedor_id_economica' => 'required|exists:proveedores,id',
        ], [
            'estrategia.required'              => 'Debes seleccionar una opción de entrega.',
            'estrategia.in'                    => 'La opción de entrega seleccionada no es válida.',
            'proveedor_id_rapida.required'     => 'No se pudo identificar el proveedor de la opción rápida.',
            'proveedor_id_rapida.exists'       => 'El proveedor de la opción rápida no es válido.',
            'proveedor_id_economica.required'  => 'No se pudo identificar el proveedor de la opción económica.',
            'proveedor_id_economica.exists'    => 'El proveedor de la opción económica no es válido.',
        ]);

        $proveedorId = $request->estrategia === 'rapida'
            ? $request->proveedor_id_rapida
            : $request->proveedor_id_economica;

        session([
            'reposicion.estrategia'   => $request->estrategia,
            'reposicion.proveedor_id' => $proveedorId,
        ]);

        return redirect()->route('admin.reposicion.paso4');
    }

    public function cuartopaso()
    {
        if (!session()->has('reposicion.libros') || !session()->has('reposicion.proveedor_id') || !session()->has('reposicion.estrategia')) {
            return redirect()->route('admin.reposicion.paso1');
        }

        $librosData  = session('reposicion.libros'); // [id => cantidad]
        $librosIds   = array_keys($librosData);
        $proveedorId = session('reposicion.proveedor_id');
        $estrategia  = session('reposicion.estrategia');

        $proveedor = \App\Models\Proveedor::findOrFail($proveedorId);
        $librosBD  = Libro::whereIn('id', $librosIds)->get();

        $itemsSeleccionados = $librosBD->map(fn($libro) => [
            'titulo'   => $libro->titulo,
            'autor'    => $libro->autor,
            'cantidad' => $librosData[$libro->id] ?? 1,
            'subtotal' => floatval($libro->precio) * ($librosData[$libro->id] ?? 1),
        ])->values()->all();

        $costoLibrosTotal = $librosBD->sum(fn($libro) => floatval($libro->precio) * ($librosData[$libro->id] ?? 1));
        $costoEnvio       = floatval($proveedor->costo_envio);
        $inversionTotal   = $costoLibrosTotal + $costoEnvio;
        $fechaEstimada    = Carbon::now()->addDays($proveedor->tiempo_entrega_dias);

        $resumenFinal = [
            'inversion_total'  => $inversionTotal,
            'costo_envio'      => $costoEnvio,
            'costo_libros'     => $costoLibrosTotal,
            'fecha_entrega'    => $fechaEstimada->translatedFormat('d \d\e M. Y'),
            'dias_habiles'     => $proveedor->tiempo_entrega_dias,
            'total_unidades'   => array_sum($librosData),
            'total_titulos'    => count($librosIds),
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
        ], [
            'numero_orden.required' => 'El número de orden es obligatorio.',
            'numero_orden.max'      => 'El número de orden no puede superar los 30 caracteres.',
            'metodo_pago.required'  => 'Debes seleccionar un método de pago.',
            'metodo_pago.in'        => 'El método de pago seleccionado no es válido.',
        ]);

        $metodoTexto = [
            'transferencia' => 'Transferencia bancaria',
            'credito_30'    => 'Crédito a 30 días',
            'credito_60'    => 'Crédito a 60 días',
            'efectivo'      => 'Efectivo',
        ][$request->metodo_pago];

        session()->flash('reposicion.orden_confirmada', [
            'numero_orden' => $request->numero_orden,
            'metodo_pago'  => $metodoTexto,
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

        return view('admin.inventario.reposicioninteligente.confirmado', compact('orden'));
    }
}