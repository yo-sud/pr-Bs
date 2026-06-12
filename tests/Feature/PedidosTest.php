<?php

use App\Models\Libro;
use App\Models\Pedido;
use App\Models\PedidoDetalle;
use App\Models\User;

it('conserva los datos historicos de una compra cuando cambia el catalogo', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create([
        'isbn' => '9781234567890',
        'titulo' => 'Titulo original',
        'precio' => 49.90,
    ]);

    $pedido = Pedido::factory()->create([
        'user_id' => $usuario->id,
        'subtotal' => 99.80,
        'envio' => 12,
        'total' => 111.80,
    ]);

    $detalle = PedidoDetalle::query()->create([
        'pedido_id' => $pedido->id,
        'libro_id' => $libro->id,
        'isbn' => $libro->isbn,
        'titulo' => $libro->titulo,
        'cantidad' => 2,
        'precio_unitario' => $libro->precio,
        'subtotal' => 99.80,
    ]);

    $libro->update([
        'isbn' => '9780987654321',
        'titulo' => 'Titulo modificado',
        'precio' => 79.90,
    ]);

    $detalle->refresh();
    $pedido->refresh();

    expect($detalle)
        ->isbn->toBe('9781234567890')
        ->titulo->toBe('Titulo original')
        ->cantidad->toBe(2)
        ->precio_unitario->toBe('49.90')
        ->subtotal->toBe('99.80');

    expect($pedido)
        ->subtotal->toBe('99.80')
        ->envio->toBe('12.00')
        ->total->toBe('111.80');
});

it('define las relaciones entre usuarios pedidos detalles y libros', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create();
    $pedido = Pedido::factory()->create(['user_id' => $usuario->id]);
    $detalle = PedidoDetalle::factory()->create([
        'pedido_id' => $pedido->id,
        'libro_id' => $libro->id,
    ]);

    expect($usuario->pedidos->first()->is($pedido))->toBeTrue()
        ->and($pedido->usuario->is($usuario))->toBeTrue()
        ->and($pedido->detalles->first()->is($detalle))->toBeTrue()
        ->and($detalle->libro->is($libro))->toBeTrue()
        ->and($libro->pedidoDetalles->first()->is($detalle))->toBeTrue();
});

it('preserva el pedido y su detalle si se eliminan usuario o libro', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create();
    $pedido = Pedido::factory()->create(['user_id' => $usuario->id]);
    $detalle = PedidoDetalle::factory()->create([
        'pedido_id' => $pedido->id,
        'libro_id' => $libro->id,
        'isbn' => $libro->isbn,
        'titulo' => $libro->titulo,
    ]);

    $usuario->delete();
    $libro->delete();

    expect($pedido->fresh()->user_id)->toBeNull()
        ->and($detalle->fresh()->libro_id)->toBeNull()
        ->and($detalle->fresh()->titulo)->toBe($libro->titulo);
});
