<?php

namespace Database\Seeders;

use App\Models\Repartidor;
use Illuminate\Database\Seeder;

class RepartidorSeeder extends Seeder
{
    public function run(): void
    {
        $empresas = [
            [
                'nombre_empresa'          => 'Olva Courier S.A.C.',
                'contacto_ejecutivo'      => 'Gerardo Quispe Ramos',
                'ruc'                     => '20512528458',
                'telefono'                => '(01) 611-8000',
                'correo'                  => 'contacto@olvacourier.com',
                'tiempo_entrega_estimado' => 'Lima – Miraflores, San Isidro, Surco',
                'observaciones'           => 'Cobertura nacional con sede principal en Lima.',
                'activo'                  => true,
            ],
            [
                'nombre_empresa'          => 'Shalom Empresarial S.A.C.',
                'contacto_ejecutivo'      => 'Patricia Vega Torres',
                'ruc'                     => '20481454933',
                'telefono'                => '(044) 60-8800',
                'correo'                  => 'operaciones@shalom.com.pe',
                'tiempo_entrega_estimado' => 'Norte – Trujillo, Chiclayo, Piura',
                'observaciones'           => 'Líder en entregas al norte del país.',
                'activo'                  => true,
            ],
            [
                'nombre_empresa'          => 'Chasqui Motors S.A.',
                'contacto_ejecutivo'      => 'Julio Medina Flores',
                'ruc'                     => '20601234567',
                'telefono'                => '(054) 22-3456',
                'correo'                  => 'despachos@chasquimotors.pe',
                'tiempo_entrega_estimado' => 'Sur – Arequipa, Cusco, Puno',
                'observaciones'           => 'Especialistas en entregas a la sierra sur.',
                'activo'                  => true,
            ],
            [
                'nombre_empresa'          => 'Urbano Express Perú S.A.C.',
                'contacto_ejecutivo'      => 'Rosa Sánchez Díaz',
                'ruc'                     => '20392473755',
                'telefono'                => '(01) 700-2020',
                'correo'                  => 'soporte@urbano.com.pe',
                'tiempo_entrega_estimado' => 'Centro – Huancayo, Ayacucho, Ica',
                'observaciones'           => 'Servicio express en la sierra central.',
                'activo'                  => false,
            ],
        ];

        foreach ($empresas as $empresa) {
            Repartidor::query()->updateOrCreate(
                ['correo' => $empresa['correo']],
                $empresa,
            );
        }
    }
}
