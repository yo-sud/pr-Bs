<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'telefono' => fake()->phoneNumber(),
            'correo' => fake()->unique()->companyEmail(),
        ];
    }
}
