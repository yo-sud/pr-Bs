<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('USER_EMAIL', 'user@bookshop.test')],
            [
                'name' => 'Usuario BookShop',
                'password' => env('USER_PASSWORD', 'password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@bookshop.test')],
            [
                'name' => 'Administrador BookShop',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        $categorias = collect([
            'Literatura',
            'Infantil',
            'Desarrollo Personal',
            'Ciencia Ficcion',
            'Misterio',
            'Historia',
            'Romance',
            'Terror',
        ])->mapWithKeys(fn (string $nombre) => [
            $nombre => Categoria::query()->firstOrCreate(['nombre' => $nombre]),
        ]);

        $proveedor = Proveedor::query()->firstOrCreate(
            ['correo' => 'catalogo@distribuidorape.pe'],
            [
                'nombre' => 'Distribuidora Peruana de Libros',
                'telefono' => '+51 999 555 111',
            ],
        );

        // Se agregó un décimo parámetro al final de cada fila con la URL de la portada real
        $libros = [
            ['9780307474728', 'Cien aÑos de soledad', 'Gabriel Garcia Marquez', 'Literatura', 59.00, 24, 980, true, 'Editorial Sudamericana', '1967-06-05', '/images/portadas/Soledad.jpg'],
            ['9780156012195', 'El Principito', 'Antoine de Saint-Exupery', 'Infantil', 49.90, 30, 820, true, 'Salamandra', '1943-04-06', '/images/portadas/Principito.jpg'],
            ['9780735211292', 'Habitos atomicos', 'James Clear', 'Desarrollo Personal', 59.90, 18, 760, true, 'Paidos', '2018-10-16', '/images/portadas/HabitosAtomicos.jpg'],
            ['9780441172719', 'Dune', 'Frank Herbert', 'Ciencia Ficcion', 62.00, 15, 690, true, 'Debolsillo', '1965-08-01', '/images/portadas/Dune.png'],
            ['9788408172178', 'La sombra del viento', 'Carlos Ruiz Zafon', 'Misterio', 69.90, 12, 640, true, 'Planeta', '2001-04-12', '/images/portadas/Sombra.jpg'],
            ['9780062316097', 'Sapiens', 'Yuval Noah Harari', 'Historia', 69.90, 20, 610, true, 'Debate', '2014-09-04', '/images/portadas/Sapiens.png'],
            ['9780345534730', 'El psicoanalista', 'John Katzenbach', 'Misterio', 79.00, 10, 530, false, 'Ediciones B', '2002-01-29', '/images/portadas/Psicoanalista.jpg'],
            ['9780451524935', '1984', 'George Orwell', 'Ciencia Ficcion', 35.00, 22, 500, false, 'Signet Classics', '1949-06-08', '/images/portadas/1984.jpg'],
            ['9780061122415', 'El alquimista', 'Paulo Coelho', 'Literatura', 38.00, 28, 460, false, 'HarperOne', '1988-01-01', '/images/portadas/Alquimista.png'],
            ['9780141439518', 'Orgullo y prejuicio', 'Jane Austen', 'Romance', 41.90, 17, 420, false, 'Penguin Classics', '1813-01-28', '/images/portadas/OrgulloPrejuicio.jpg'],
            ['9780307474278', 'El codigo Da Vinci', 'Dan Brown', 'Misterio', 48.50, 14, 380, false, 'Anchor', '2003-03-18', '/images/portadas/CodigoDaVinci.jpg'],
            ['9780374533557', 'Pensar rapido, pensar despacio', 'Daniel Kahneman', 'Desarrollo Personal', 65.00, 11, 350, false, 'Farrar Straus Giroux', '2011-10-25', '/images/portadas/PensarRapido.png'],
            ['9780307743657', 'El resplandor', 'Stephen King', 'Terror', 47.90, 9, 310, false, 'Anchor', '1977-01-28', '/images/portadas/Resplandor.png'],
            ['9780553213690', 'La metamorfosis', 'Franz Kafka', 'Literatura', 25.00, 32, 270, false, 'Bantam Classics', '1915-10-01', '/images/portadas/Metamorfosis.jpg'],
            ['9780141439570', 'El retrato de Dorian Gray', 'Oscar Wilde', 'Literatura', 34.90, 16, 230, false, 'Penguin Classics', '1890-07-01', '/images/portadas/RetratoDorianGray.jpg'],
            ['9780547928227', 'El Hobbit', 'J. R. R. Tolkien', 'Ciencia Ficcion', 52.00, 19, 590, true, 'Mariner Books', '1937-09-21', '/images/portadas/Hobbit.jpg'],
            ['9786073193009', 'El infinito en un junco', 'Irene Vallejo', 'Historia', 75.00, 13, 320, true, 'Siruela', '2019-11-05', '/images/portadas/InfinitoJunco.jpg'],
            ['9788418008122', 'La biblioteca de la medianoche', 'Matt Haig', 'Literatura', 55.90, 21, 440, true, 'AdN', '2020-08-13', '/images/portadas/BibliotecaMedianoche.jpg'],
        ];

        foreach ($libros as [$isbn, $titulo, $autor, $categoria, $precio, $stock, $ventas, $destacado, $editorial, $fecha, $portadaUrl]) {
            Libro::query()->updateOrCreate(
                ['isbn' => $isbn],
                [
                    'titulo' => $titulo,
                    'autor' => $autor,
                    'descripcion' => "Una edicion seleccionada de {$titulo}, escrita por {$autor}. Disponible en BookShop con entrega en Peru.",
                    'editorial' => $editorial,
                    'fecha_publicacion' => $fecha,
                    'portada' => $portadaUrl, 
                    'precio' => $precio,
                    'stock' => $stock,
                    'estado' => 'activo',
                    'destacado' => $destacado,
                    'ventas' => $ventas,
                    'categoria_id' => $categorias[$categoria]->id,
                    'proveedor_id' => $proveedor->id,
                ],
            );
        }

        $this->call(PedidoSeeder::class);
    }
}