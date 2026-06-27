<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use App\Services\CarritoService;
use App\Services\PedidoEstadoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(CarritoService $carrito): View|RedirectResponse
    {
        $resumen = $carrito->resumen();

        if ($resumen['items']->isEmpty()) {
            return redirect()->route('carrito.index')
                ->withErrors(['carrito' => 'Agrega al menos un libro antes de continuar.']);
        }

        // Establece el envío en S/ 0 para el resumen inicial de checkout.
        $resumen['envio'] = 0.00;
        $resumen['total'] = $resumen['subtotal'];

        return view('checkout.create', $resumen);
    }

    public function store(
        Request $request,
        CarritoService $carrito,
        PedidoEstadoService $estados,
    ): RedirectResponse {

        if ($request->user() === null) {
            abort(401, 'Debes iniciar sesión para confirmar un pedido.');
        }

        $reglas = [
            // Datos personales.
            'nombre'          => ['required', 'string', 'max:100'],
            'apellidos'       => ['required', 'string', 'max:100'],
            'tipo_documento'  => ['required', 'string', Rule::in(['DNI', 'CE', 'PASAPORTE'])],
            'documento'       => ['required', 'string', 'min:8', 'max:15'],
            'telefono'        => ['required', 'string', 'regex:/^9[0-9]{8}$/'],

            // Dirección de envío.
            'calle'           => ['required', 'string', 'max:255'],
            'numero'          => ['required', 'string', 'max:20'],
            'piso_dpto'       => ['nullable', 'string', 'max:50'],
            'entre_calles'    => ['nullable', 'string', 'max:255'],
            'pais'            => ['required', 'string', Rule::in(['PE'])],
            'provincia'       => ['required', 'string', 'max:100'],
            'ciudad'          => ['required', 'string', 'max:100'],
            'codigo_postal'   => ['nullable', 'string', 'max:20'],

            // Método de pago.
            'metodo_pago'     => ['required', 'string', Rule::in(['efectivo'])],
        ];

        $mensajes = [
            'nombre.required'          => 'El nombre es obligatorio.',
            'apellidos.required'       => 'Los apellidos son obligatorios.',
            'tipo_documento.required'  => 'Selecciona un tipo de documento válido.',
            'tipo_documento.in'        => 'El tipo de documento seleccionado no es válido.',
            'documento.required'       => 'El número de documento es obligatorio.',
            'documento.min'            => 'El documento debe tener al menos :min caracteres.',
            'telefono.required'        => 'El número de teléfono es obligatorio.',
            'telefono.regex'           => 'El teléfono debe ser un celular válido de 9 dígitos y empezar con 9 (ej: 987654321).',
            'calle.required'           => 'Ingresa el nombre de la calle.',
            'numero.required'          => 'Ingresa el número de la casa o departamento.',
            'provincia.required'       => 'Selecciona una provincia.',
            'ciudad.required'          => 'Ingresa la ciudad.',
            'metodo_pago.required'     => 'Debes seleccionar un método de pago.',
            'metodo_pago.in'           => 'El método de pago seleccionado no está disponible.',
        ];

        $datosValidados = $request->validate($reglas, $mensajes);

        $cantidades = $carrito->contenido();

        if ($cantidades === []) {
            return redirect()->route('carrito.index')
                ->withErrors(['carrito' => 'El carrito esta vacio.']);
        }

        $pedido = DB::transaction(function () use ($request, $datosValidados, $carrito, $cantidades, $estados) {
            $libros = Libro::query()
                ->whereIn('id', array_keys($cantidades))
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($libros->count() !== count($cantidades)) {
                throw ValidationException::withMessages([
                    'carrito' => 'Uno de los libros ya no esta disponible.',
                ]);
            }

            foreach ($libros as $libro) {
                $cantidad = (int) $cantidades[$libro->id];

                if ($libro->estado !== 'activo' || $cantidad < 1 || $libro->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'carrito' => "No hay stock suficiente de {$libro->titulo}.",
                    ]);
                }
            }

            $resumen = $carrito->resumenDesdeLibros($libros, $cantidades);

            $direccionCompleta = implode(', ', array_filter([
                $datosValidados['calle'] . ' ' . $datosValidados['numero'],
                $datosValidados['piso_dpto'] ?? null,
                ($datosValidados['entre_calles'] ?? null) ? 'Entre: ' . $datosValidados['entre_calles'] : null,
                $datosValidados['ciudad'],
                $datosValidados['provincia'],
                $datosValidados['codigo_postal'] ?? null
            ]));

            $envioGratis = 0.00;
            $totalReal = $resumen['subtotal'];

            $pedido = Pedido::query()->create([
                'user_id' => $request->user()->id,
                'direccion' => $direccionCompleta,
                'subtotal' => $resumen['subtotal'],
                'envio' => $envioGratis,
                'total' => $totalReal,
                'estado_pago' => 'pendiente',
                'estado_pedido' => 'pendiente',
            ]);

            foreach ($resumen['items'] as $item) {
                /** @var Libro $libro */
                $libro = $item['libro'];
                $cantidad = $item['cantidad'];
                $stockAnterior = $libro->stock;
                $stockNuevo = $stockAnterior - $cantidad;

                $pedido->detalles()->create([
                    'libro_id' => $libro->id,
                    'isbn' => $libro->isbn,
                    'titulo' => $libro->titulo,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $libro->precio,
                    'subtotal' => number_format($item['subtotal_centimos'] / 100, 2, '.', ''),
                ]);

                $libro->update([
                    'stock' => $stockNuevo,
                    'ventas' => $libro->ventas + $cantidad,
                ]);

                MovimientoInventario::query()->create([
                    'libro_id' => $libro->id,
                    'user_id' => $request->user()->id,
                    'tipo' => 'venta',
                    'cantidad' => -$cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'motivo' => "Venta del pedido #{$pedido->id}",
                ]);
            }

            $estados->registrarCreacion($pedido);

            return $pedido;
        }, 3);

        $carrito->vaciar();

        return redirect()->route('pedidos.show', $pedido)
            ->with('status', 'Tu pedido fue confirmado correctamente.');
    }
}
