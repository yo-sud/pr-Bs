<?php

namespace App\Http\Controllers;

namespace App\Services;

use App\Models\Libro;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CarritoService
{
    private const SESSION_KEY = 'carrito';

    private const ENVIO_CENTIMOS = 1200;

    private const ENVIO_GRATIS_DESDE_CENTIMOS = 15000;

    public function contenido(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    /**
     * Corregido: Suma las cantidades internas de cada item array.
     */
    public function cantidadTotal(): int
    {
        $total = 0;
        foreach ($this->contenido() as $item) {
            $total += is_array($item) ? ($item['cantidad'] ?? 0) : (int)$item;
        }
        return $total;
    }

    /**
     * Corregido: Agrega un libro adaptándose a la nueva estructura estructurada.
     */
    public function agregar(Libro $libro, int $cantidad): void
    {
        $this->validarDisponible($libro, $cantidad);

        $carrito = $this->contenido();
        
        // Obtenemos la cantidad actual manejando si ya es array o entero antiguo
        $cantidadActual = 0;
        if (isset($carrito[$libro->id])) {
            $cantidadActual = is_array($carrito[$libro->id]) 
                ? ($carrito[$libro->id]['cantidad'] ?? 0) 
                : (int)$carrito[$libro->id];
        }

        $nuevaCantidad = $cantidadActual + $cantidad;
        if ($nuevaCantidad > 99) {
            $nuevaCantidad = 99;
        }

        $this->validarDisponible($libro, $nuevaCantidad);

        // Guardamos la nueva estructura idéntica a la del controlador
        $carrito[$libro->id] = [
            'libro' => $libro,
            'titulo' => $libro->titulo,
            'precio' => (float) $libro->precio,
            'cantidad' => $nuevaCantidad,
            'portada_url' => $libro->portada_url,
            'subtotal_centimos' => ($libro->precio * 100) * $nuevaCantidad
        ];

        session()->put(self::SESSION_KEY, $carrito);
    }

    /**
     * Corregido: Actualiza la estructura completa del item al cambiar la cantidad.
     */
    public function actualizar(Libro $libro, int $cantidad): void
    {
        $this->validarDisponible($libro, $cantidad);

        $carrito = $this->contenido();
        
        $carrito[$libro->id] = [
            'libro' => $libro,
            'titulo' => $libro->titulo,
            'precio' => (float) $libro->precio,
            'cantidad' => $cantidad,
            'portada_url' => $libro->portada_url,
            'subtotal_centimos' => ($libro->precio * 100) * $cantidad
        ];

        session()->put(self::SESSION_KEY, $carrito);
    }

    public function eliminar(Libro $libro): void
    {
        $carrito = $this->contenido();
        unset($carrito[$libro->id]);
        session()->put(self::SESSION_KEY, $carrito);
    }

    public function vaciar(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Corregido: Remueve el tipado estricto "int $cantidad" que causaba el TypeError
     */
    public function resumen(): array
    {
        $cantidades = $this->contenido();
        $libros = Libro::query()
            ->with('categoria')
            ->whereIn('id', array_keys($cantidades))
            ->get()
            ->keyBy('id');

        $items = collect($cantidades)
            ->map(function (mixed $item, int|string $libroId) use ($libros) {
                $libro = $libros->get((int) $libroId);

                if (! $libro) {
                    return null;
                }

                // Extrae la cantidad si viene como array estructurado
                $cantidad = is_array($item) ? ($item['cantidad'] ?? 1) : (int)$item;
                $precioCentimos = $this->aCentimos($libro->precio);

                return [
                    'libro' => $libro,
                    'cantidad' => $cantidad,
                    'subtotal_centimos' => $precioCentimos * $cantidad,
                ];
            })
            ->filter()
            ->values();

        $subtotalCentimos = $items->sum('subtotal_centimos');
        $envioCentimos = $subtotalCentimos === 0 || $subtotalCentimos >= self::ENVIO_GRATIS_DESDE_CENTIMOS
            ? 0
            : self::ENVIO_CENTIMOS;

        return [
            'items' => $items,
            'subtotal' => $this->desdeCentimos($subtotalCentimos),
            'envio' => $this->desdeCentimos($envioCentimos),
            'total' => $this->desdeCentimos($subtotalCentimos + $envioCentimos),
        ];
    }

    /**
     * Corregido: Resguarda que si el array $cantidades viene del nuevo carrito estructurado,
     * extraiga correctamente el entero de la celda 'cantidad'.
     */
    public function resumenDesdeLibros(Collection $libros, array $cantidades): array
    {
        $items = $libros->map(function (Libro $libro) use ($cantidades) {
            $itemRaw = $cantidades[$libro->id] ?? 1;
            $cantidad = is_array($itemRaw) ? ($itemRaw['cantidad'] ?? 1) : (int)$itemRaw;
            
            $precioCentimos = $this->aCentimos($libro->precio);

            return [
                'libro' => $libro,
                'cantidad' => $cantidad,
                'subtotal_centimos' => $precioCentimos * $cantidad,
            ];
        });

        $subtotalCentimos = $items->sum('subtotal_centimos');
        $envioCentimos = $subtotalCentimos >= self::ENVIO_GRATIS_DESDE_CENTIMOS
            ? 0
            : self::ENVIO_CENTIMOS;

        return [
            'items' => $items,
            'subtotal' => $this->desdeCentimos($subtotalCentimos),
            'envio' => $this->desdeCentimos($envioCentimos),
            'total' => $this->desdeCentimos($subtotalCentimos + $envioCentimos),
        ];
    }

    private function validarDisponible(Libro $libro, int $cantidad): void
    {
        if ($libro->estado !== 'activo' || $libro->stock < $cantidad) {
            throw ValidationException::withMessages([
                'cantidad' => "Solo hay {$libro->stock} unidades disponibles de {$libro->titulo}.",
            ]);
        }
    }

    private function aCentimos(string|float|int $importe): int
    {
        return (int) round((float) $importe * 100);
    }

    private function desdeCentimos(int $centimos): string
    {
        return number_format($centimos / 100, 2, '.', '');
    }
}