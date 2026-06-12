<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
/**
     * Muestra la vista del carrito con una Colección compatible.
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $subtotal = 0;

        foreach ($carrito as $id => $item) {
            // Protección: Si por residuos de pruebas viejas algún elemento no es un array válido, 
            // lo eliminamos automáticamente para que no rompa la app.
            if (!is_array($item) || !isset($item['precio'], $item['cantidad'])) {
                unset($carrito[$id]);
                session()->put('carrito', $carrito);
                continue;
            }

            $subtotal += $item['precio'] * $item['cantidad'];
        }

        // SOLUCIÓN: Envolvemos $carrito en collect() para que tu Blade pueda usar ->count() y ->isNotEmpty()
        return view('carrito', [
            'items' => collect($carrito),
            'subtotal' => $subtotal,
            'total' => $subtotal
        ]);
    }

    /**
     * Reemplaza a: AgregarCarritoRequest.php
     */
    public function store(Request $request, $id)
    {
        // Validación integrada de AgregarCarritoRequest
        $datosValidados = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cantidad = $datosValidados['cantidad'];
        $libro = Libro::findOrFail($id);
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id]) && is_array($carrito[$id])) {
            $carrito[$id]['cantidad'] += $cantidad;
            if ($carrito[$id]['cantidad'] > 99) {
                $carrito[$id]['cantidad'] = 99;
            }
            // Recalculamos el subtotal en céntimos también al incrementar
            $carrito[$id]['subtotal_centimos'] = ($carrito[$id]['precio'] * 100) * $carrito[$id]['cantidad'];
        } else {
            $carrito[$id] = [
                'libro' => $libro,
                'titulo' => $libro->titulo,
                'precio' => (float) $libro->precio,
                'cantidad' => $cantidad,
                'portada_url' => $libro->portada_url,
                'subtotal_centimos' => ($libro->precio * 100) * $cantidad
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->route('carrito.index')->with('success', "¡Se añadió '{$libro->titulo}' al carrito!");
    }

    /**
     * Reemplaza a: ActualizarCarritoRequest.php
     * Se ejecuta cuando el usuario cambia la cantidad directamente en la página del carrito.
     */
    public function actualizar(Request $request, $id)
    {
        // Validación integrada de ActualizarCarritoRequest
        $datosValidados = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id]) && is_array($carrito[$id])) {
            // Actualizamos la cantidad por el nuevo valor ingresado
            $carrito[$id]['cantidad'] = $datosValidados['cantidad'];
            // Actualizamos también el subtotal en céntimos
            $carrito[$id]['subtotal_centimos'] = ($carrito[$id]['precio'] * 100) * $datosValidados['cantidad'];
            
            session()->put('carrito', $carrito);
            return redirect()->route('carrito.index')->with('success', 'Carrito actualizado con éxito.');
        }

        return redirect()->route('carrito.index')->with('error', 'El producto no se encuentra en el carrito.');
    }

    /**
     * Elimina un producto.
     */
    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Vacía el carrito.
     */
    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.index')->with('success', 'El carrito se ha vaciado.');
    }
}