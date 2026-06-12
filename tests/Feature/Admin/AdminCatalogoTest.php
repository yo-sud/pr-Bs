<?php

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\MovimientoInventario;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->cliente = User::factory()->create();
    $this->categoria = Categoria::factory()->create();
    $this->proveedor = Proveedor::factory()->create();
});

it('impide a un cliente entrar al panel administrativo', function () {
    $this->actingAs($this->cliente)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('permite al administrador ver el dashboard', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard');
});

it('permite al administrador ver el inventario', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.inventario.index'))
        ->assertOk()
        ->assertSee('Inventario');
});

it('renderiza las pantallas de gestion administrativa', function () {
    $libro = Libro::factory()->create([
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->actingAs($this->admin);

    $this->get(route('admin.libros.index'))->assertOk()->assertSee($libro->titulo);
    $this->get(route('admin.libros.create'))->assertOk()->assertSee('Registrar libro');
    $this->get(route('admin.libros.edit', $libro))->assertOk()->assertSee('Ajustar stock');
    $this->get(route('admin.categorias.index'))->assertOk()->assertSee($this->categoria->nombre);
    $this->get(route('admin.proveedores.index'))->assertOk()->assertSee($this->proveedor->nombre);
});

it('permite crear un libro con stock inicial y registra el movimiento', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.libros.store'), [
        'isbn' => '9781234567890',
        'titulo' => 'Libro administrado',
        'autor' => 'Autora Ejemplo',
        'descripcion' => 'Descripción de prueba',
        'editorial' => 'Editorial Ejemplo',
        'fecha_publicacion' => '2026-01-10',
        'precio' => '49.90',
        'stock' => 12,
        'estado' => 'activo',
        'destacado' => true,
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $libro = Libro::query()->where('isbn', '9781234567890')->firstOrFail();

    $response->assertRedirect(route('admin.libros.edit', $libro));
    expect($libro->stock)->toBe(12);

    $this->assertDatabaseHas('movimientos_inventario', [
        'libro_id' => $libro->id,
        'user_id' => $this->admin->id,
        'tipo' => 'entrada',
        'cantidad' => 12,
        'stock_anterior' => 0,
        'stock_nuevo' => 12,
    ]);
});

it('muestra al cliente la portada subida por el administrador', function () {
    Storage::fake('public');
    $portada = UploadedFile::fake()->image('portada.jpg', 600, 900);

    $this->actingAs($this->admin)->post(route('admin.libros.store'), [
        'isbn' => '9781234567891',
        'titulo' => 'Libro con portada',
        'autor' => 'Autora Visual',
        'precio' => '39.90',
        'stock' => 3,
        'estado' => 'activo',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
        'portada' => $portada,
    ])->assertRedirect();

    $libro = Libro::query()->where('isbn', '9781234567891')->firstOrFail();

    Storage::disk('public')->assertExists($libro->portada);
    expect($libro->portada_url)->toBe('/storage/'.$libro->portada);

    $this->actingAs($this->cliente)
        ->get(route('libros.show', $libro))
        ->assertOk()
        ->assertSee($libro->portada_url, false);
});

it('permite aumentar y reducir stock dejando auditoria', function () {
    $libro = Libro::factory()->create([
        'stock' => 10,
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.libros.stock', $libro), [
            'cantidad' => -3,
            'motivo' => 'Corrección de inventario',
        ])
        ->assertRedirect();

    expect($libro->fresh()->stock)->toBe(7);
    expect(MovimientoInventario::query()->whereBelongsTo($libro)->latest()->first())
        ->cantidad->toBe(-3)
        ->stock_anterior->toBe(10)
        ->stock_nuevo->toBe(7);
});

it('rechaza un ajuste que dejaria stock negativo', function () {
    $libro = Libro::factory()->create([
        'stock' => 2,
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.libros.stock', $libro), [
            'cantidad' => -3,
            'motivo' => 'Ajuste inválido',
        ])
        ->assertStatus(422);

    expect($libro->fresh()->stock)->toBe(2);
    $this->assertDatabaseCount('movimientos_inventario', 0);
});

it('desactiva un libro sin eliminarlo', function () {
    $libro = Libro::factory()->create([
        'estado' => 'activo',
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.libros.destroy', $libro))
        ->assertRedirect(route('admin.libros.index'));

    expect($libro->fresh()->estado)->toBe('inactivo');
});

it('no elimina categorias ni proveedores con libros asociados', function () {
    Libro::factory()->create([
        'categoria_id' => $this->categoria->id,
        'proveedor_id' => $this->proveedor->id,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.categorias.destroy', $this->categoria))
        ->assertSessionHasErrors('categoria');

    $this->actingAs($this->admin)
        ->delete(route('admin.proveedores.destroy', $this->proveedor))
        ->assertSessionHasErrors('proveedor');

    $this->assertDatabaseHas('categorias', ['id' => $this->categoria->id]);
    $this->assertDatabaseHas('proveedores', ['id' => $this->proveedor->id]);
});
