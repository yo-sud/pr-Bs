<?php

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Proveedor;

beforeEach(function () {
    $this->categoria = Categoria::factory()->create(['nombre' => 'Ciencia Ficcion']);
    $this->otraCategoria = Categoria::factory()->create(['nombre' => 'Historia']);
    $this->proveedor = Proveedor::factory()->create();
});

it('muestra libros activos en el catalogo', function () {
    $visible = Libro::factory()->create([
        'titulo' => 'Dune',
        'estado' => 'activo',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);
    Libro::factory()->create([
        'titulo' => 'Libro oculto',
        'estado' => 'inactivo',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.index'))
        ->assertOk()
        ->assertSee($visible->titulo)
        ->assertDontSee('Libro oculto');
});

it('busca libros por titulo autor e isbn', function (string $busqueda) {
    Libro::factory()->create([
        'titulo' => 'Fundacion',
        'autor' => 'Isaac Asimov',
        'isbn' => '9780553293357',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);
    Libro::factory()->create([
        'titulo' => 'Historia del Peru',
        'categoria_id' => $this->otraCategoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.index', ['search' => $busqueda]))
        ->assertOk()
        ->assertSee('Fundacion')
        ->assertDontSee('Historia del Peru');
})->with([
    'titulo' => ['Fundacion'],
    'autor' => ['Asimov'],
    'isbn' => ['9780553293357'],
]);

it('filtra libros por categoria', function () {
    Libro::factory()->create([
        'titulo' => 'Dune',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);
    Libro::factory()->create([
        'titulo' => 'Sapiens',
        'categoria_id' => $this->otraCategoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.index', ['categoria' => $this->categoria->id]))
        ->assertOk()
        ->assertSee('Dune')
        ->assertDontSee('Sapiens');
});

it('ordena libros por popularidad', function () {
    Libro::factory()->create([
        'titulo' => 'Menos popular',
        'ventas' => 10,
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);
    Libro::factory()->create([
        'titulo' => 'Mas popular',
        'ventas' => 100,
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.index', ['orden' => 'populares']))
        ->assertOk()
        ->assertSeeInOrder(['Mas popular', 'Menos popular']);
});

it('muestra el detalle y libros relacionados', function () {
    $libro = Libro::factory()->create([
        'titulo' => 'Dune',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);
    Libro::factory()->create([
        'titulo' => 'Fundacion',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.show', $libro))
        ->assertOk()
        ->assertSee('Dune')
        ->assertSee('Fundacion')
        ->assertSee($this->proveedor->nombre);
});

it('no muestra el detalle de un libro inactivo', function () {
    $libro = Libro::factory()->create([
        'estado' => 'inactivo',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->get(route('libros.show', $libro))->assertNotFound();
});
