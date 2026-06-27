<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $subtotal = 0;

        foreach ($carrito as $id => $item) {
            if (!is_array($item) || !isset($item['precio'], $item['cantidad'])) {
                unset($carrito[$id]);
                session()->put('carrito', $carrito);
                continue;
            }

            $subtotal += $item['precio'] * $item['cantidad'];
        }

        return view('carrito', [
            'items' => collect($carrito),
            'subtotal' => $subtotal,
            'total' => $subtotal
        ]);
    }

    public function store(Request $request, $id)
    {
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


        return redirect()->route('libros.index')->with('status', "Se añadió '{$libro->titulo}' al carrito.");
    }

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.index')->with('success', 'El carrito se ha vaciado.');
    }
}