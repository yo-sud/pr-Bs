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


    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('USER_EMAIL', 'user@bookshop.test')],
            [
                'name' => 'Usuario BookShop',
                'password' => env('USER_PASSWORD', 'password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'phone' => '+51 987 654 321',
            ],
        );

        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@bookshop.test')],
            [
                'name' => 'Administrador BookShop',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '+51 999 888 777',
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
            'Fantasía',
        ])->mapWithKeys(fn (string $nombre) => [
            $nombre => Categoria::query()->firstOrCreate(['nombre' => $nombre]),
        ]);

        $proveedor = Proveedor::query()->firstOrCreate(
            ['correo' => 'catalogo@distribuidorape.pe'],
            [
                'nombre_empresa' => 'Distribuidora Peruana de Libros', 
                'telefono' => '+51 999 555 111',
            ],
        );

        $libros = [
            ['9780307474728', 'Cien aÑos de soledad', 'Gabriel Garcia Marquez', 'Literatura', 59.00, 24, 980, true, 'Editorial Sudamericana', '1967-06-05', '/images/portadas/Soledad.jpg'],
            ['9780156012195', 'El Principito', 'Antoine de Saint-Exupery', 'Infantil', 39.90, 30, 820, true, 'Salamandra', '1943-04-06', '/images/portadas/Principito.jpg'],
            ['9780735211292', 'Habitos atomicos', 'James Clear', 'Desarrollo Personal', 59.90, 18, 760, true, 'Paidos', '2018-10-16', '/images/portadas/HabitosAtomicos.jpg'],
            ['9780441172719', 'Dune', 'Frank Herbert', 'Ciencia Ficcion', 79.00, 15, 690, true, 'Debolsillo', '1965-08-01', '/images/portadas/Dune.png'],
            ['9788408172178', 'La sombra del viento', 'Carlos Ruiz Zafon', 'Misterio', 69.90, 12, 640, true, 'Planeta', '2001-04-12', '/images/portadas/Sombra.jpg'],
            ['9780062316097', 'Sapiens', 'Yuval Noah Harari', 'Historia', 69.00, 20, 610, true, 'Debate', '2014-09-04', '/images/portadas/Sapiens.png'],
            ['9780345534730', 'El psicoanalista', 'John Katzenbach', 'Misterio', 99.00, 10, 530, false, 'Ediciones B', '2002-01-29', '/images/portadas/Psicoanalista.jpg'],
            ['9780451524935', '1984', 'George Orwell', 'Ciencia Ficcion', 49.00, 22, 500, false, 'Signet Classics', '1949-06-08', '/images/portadas/1984.jpg'],
            ['9780061122415', 'El alquimista', 'Paulo Coelho', 'Literatura', 49.90, 28, 460, false, 'HarperOne', '1988-01-01', '/images/portadas/Alquimista.png'],
            ['9780141439518', 'Orgullo y prejuicio', 'Jane Austen', 'Romance', 24.90, 17, 420, false, 'Penguin Classics', '1813-01-28', '/images/portadas/OrgulloPrejuicio.jpg'],
            ['9780307474278', 'El codigo Da Vinci', 'Dan Brown', 'Misterio', 99.90, 14, 380, false, 'Anchor', '2003-03-18', '/images/portadas/CodigoDaVinci.jpg'],
            ['9780374533557', 'Pensar rapido, pensar despacio', 'Daniel Kahneman', 'Desarrollo Personal', 59.00, 11, 350, false, 'Farrar Straus Giroux', '2011-10-25', '/images/portadas/PensarRapido.png'],
            ['9780307743657', 'El resplandor', 'Stephen King', 'Terror', 69.00, 9, 310, false, 'Anchor', '1977-01-28', '/images/portadas/Resplandor.png'],
            ['9780553213690', 'La metamorfosis', 'Franz Kafka', 'Literatura', 30.00, 32, 270, false, 'Bantam Classics', '1915-10-01', '/images/portadas/Metamorfosis.jpg'],
            ['9780141439570', 'El retrato de Dorian Gray', 'Oscar Wilde', 'Literatura', 24.90, 16, 230, false, 'Penguin Classics', '1890-07-01', '/images/portadas/RetratoDorianGray.jpg'],
            ['9780547928227', 'El Hobbit', 'J. R. R. Tolkien', 'Ciencia Ficcion', 39.00, 19, 590, true, 'Mariner Books', '1937-09-21', '/images/portadas/Hobbit.jpg'],
            ['9786073193009', 'El infinito en un junco', 'Irene Vallejo', 'Historia', 69.00, 13, 320, true, 'Siruela', '2019-11-05', '/images/portadas/InfinitoJunco.jpg'],
            ['9788418008122', 'La biblioteca de la medianoche', 'Matt Haig', 'Literatura', 109.00, 21, 440, true, 'AdN', '2020-08-13', '/images/portadas/BibliotecaMedianoche.jpg'],
          
            ['9786073836241', 'Alas de sangre', 'Rebecca Yarros', 'Romance', 109.00, 25, 736, true, 'Planeta', '2023-11-15', '/images/portadas/AlasSangre.jpg'],
            ['9788416517237', 'Ikigai', 'Héctor García', 'Desarrollo Personal', 69.00, 30, 160, true, 'Urano', '2016-04-14', '/images/portadas/Ikigai.png'],
            ['9788445016558', 'Silmarillion', 'J. R. R. Tolkien', 'Ciencia Ficcion', 199.00, 14, 448, true, 'Minotauro', '1977-09-15', '/images/portadas/Silmarillion.jpg'],
            ['9788419275134', 'La hipótesis del amor', 'Ali Hazelwood', 'Romance', 99.00, 22, 384, false, 'Contraluz', '2021-09-14', '/images/portadas/HipotesisAmor.jpg'],
            ['9788418053184', 'La paciente silenciosa', 'Alex Michaelides', 'Misterio', 89.00, 16, 384, true, 'Alfaguara', '2019-02-05', '/images/portadas/PacienteSilenciosa.jpg'],
            ['9788423432745', 'El club de las 5 de la mañana', 'Robin Sharma', 'Desarrollo Personal', 69.00, 40, 336, false, 'Grijalbo', '2018-12-04', '/images/portadas/Club5Manana.png'],
            ['9788416588435', 'Los siete maridos de Evelyn Hugo', 'Taylor Jenkins Reid', 'Romance', 115.00, 19, 384, true, 'Umbriel', '2017-06-13', '/images/portadas/EvelynHugo.jpg'],
            ['9788408275923', 'El problema de los tres cuerpos', 'Cixin Liu', 'Ciencia Ficcion', 29.90, 12, 408, true, 'Nova', '2006-05-10', '/images/portadas/TresCuerpos.jpg'],
            ['9788433999054', 'Las cosas que perdimos en el fuego', 'Mariana Enriquez', 'Terror', 49.00, 15, 200, false, 'Anagrama', '2016-02-18', '/images/portadas/CosasPerdimos.jpg'],
            ['9788498387087', 'Harry Potter y la piedra filosofal', 'J.K. Rowling', 'Fantasía', 199.00, 25, 256, false, 'Salamandra', '1997-06-26', '/images/portadas/HarryPotter1.jpg'],            
            ['9788411481137', 'Romper el círculo', 'Colleen Hoover', 'Romance', 99.90, 28, 400, true, 'Planeta', '2016-08-02', '/images/portadas/RomperCirculo.jpg'],
            ['9788408234562', 'El fuego invisible', 'Javier Sierra', 'Misterio', 69.90, 10, 480, false, 'Planeta', '2017-10-24', '/images/portadas/FuegoInvisible.png'],
            ['9788466361224', 'El resplandor de las luciérnagas', 'Paul Pen', 'Terror', 34.90, 13, 368, false, 'Plaza & Janés', '2017-05-11', '/images/portadas/Luciernagas.jpg'],
            ['9788496200920', 'La verdad sobre el caso Harry Quebert', 'Joël Dicker', 'Misterio', 69.00, 17, 672, true, 'Alfaguara', '2012-09-19', '/images/portadas/HarryQuebert.jpg'],
            ['9788466358118', 'Gente normal', 'Sally Rooney', 'Literatura', 59.00, 21, 256, false, 'Literatura Random House', '2018-08-28', '/images/portadas/GenteNormal.jpg'],
            ['9788417961312', 'Estudio en escarlata', 'Arthur Conan Doyle', 'Misterio', 49.00, 25, 192, false, 'Alma Europa', '1887-11-01', '/images/portadas/EstudioEscarlata.png'],
            ['9788417605735', 'Hábitos de ricos', 'Juan Diego Gómez', 'Desarrollo Personal', 41.30, 20, 240, false, 'Paisas', '2016-05-15', '/images/portadas/HabitosRicos.webp'],
            ['9788433980854', 'Te receto un gato', 'Syou Ishida', 'Desarrollo Personal', 69.90, 11, 680, true, 'Planeta', '2019-11-05', '/images/portadas/RecetoGato.png'],
            ['9788499081373', 'Crónicas marcianas', 'Ray Bradbury', 'Ciencia Ficcion', 55.00, 14, 352, false, 'Minotauro', '1950-05-04', '/images/portadas/CronicasMarcianas.png'],
            ['9788408229049', 'La ciudad de vapor', 'Carlos Ruiz Zafón', 'Literatura', 98.79, 30, 224, false, 'Planeta', '2020-11-17', '/images/portadas/CiudadVapor.jpg'],
            
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