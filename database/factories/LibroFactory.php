<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isbn' => fake()->unique()->isbn13(),
            'titulo' => fake()->sentence(3),
            'autor' => fake()->name(),
            'descripcion' => fake()->paragraphs(2, true),
            'editorial' => fake()->company(),
            'fecha_publicacion' => fake()->dateTimeBetween('-10 years', 'now'),
            'portada' => null,
            'precio' => fake()->randomFloat(2, 20, 120),
            'stock' => fake()->numberBetween(0, 50),
            'estado' => 'activo',
            'destacado' => fake()->boolean(30),
            'ventas' => fake()->numberBetween(0, 500),
            'categoria_id' => Categoria::factory(),
            'proveedor_id' => Proveedor::factory(),
        ];
    }
}
