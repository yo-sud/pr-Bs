<?php

use App\Models\Libro;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use App\Models\User;

it('permite agregar actualizar eliminar y vaciar el carrito de sesion', function () {
    $libro = Libro::factory()->create(['stock' => 5]);

    $this->post(route('carrito.store', $libro), ['cantidad' => 2])
        ->assertRedirect()
        ->assertSessionHas('carrito', [$libro->id => 2]);

    $this->patch(route('carrito.update', $libro), ['cantidad' => 4])
        ->assertRedirect()
        ->assertSessionHas('carrito', [$libro->id => 4]);

    $this->delete(route('carrito.destroy', $libro))
        ->assertRedirect()
        ->assertSessionHas('carrito', []);

    $this->withSession(['carrito' => [$libro->id => 1]])
        ->delete(route('carrito.clear'))
        ->assertRedirect()
        ->assertSessionMissing('carrito');
});

it('rechaza cantidades mayores al stock al modificar el carrito', function () {
    $libro = Libro::factory()->create(['stock' => 2]);

    $this->from(route('libros.show', $libro))
        ->post(route('carrito.store', $libro), ['cantidad' => 3])
        ->assertRedirect(route('libros.show', $libro))
        ->assertSessionHasErrors('cantidad')
        ->assertSessionMissing('carrito');
});

it('requiere autenticacion para confirmar una compra', function () {
    $libro = Libro::factory()->create(['stock' => 2]);

    $this->withSession(['carrito' => [$libro->id => 1]])
        ->get(route('checkout.create'))
        ->assertRedirect(route('login'));
});

it('crea un pedido recalculando precios y reduce stock en una transaccion', function () {
    $usuario = User::factory()->create();
    $primerLibro = Libro::factory()->create([
        'titulo' => 'Primer libro',
        'precio' => 40,
        'stock' => 5,
        'ventas' => 2,
    ]);
    $segundoLibro = Libro::factory()->create([
        'titulo' => 'Segundo libro',
        'precio' => 30,
        'stock' => 4,
        'ventas' => 0,
    ]);

    $sesion = [
        'carrito' => [
            $primerLibro->id => 2,
            $segundoLibro->id => 1,
        ],
    ];

    $primerLibro->update(['precio' => 50]);

    $response = $this->actingAs($usuario)
        ->withSession($sesion)
        ->post(route('checkout.store'), [
            'direccion' => 'Av. Principal 123, Miraflores, Lima',
        ]);

    $pedido = Pedido::query()->with('detalles')->sole();

    $response
        ->assertRedirect(route('pedidos.show', $pedido))
        ->assertSessionMissing('carrito');
    expect($pedido)
        ->subtotal->toBe('130.00')
        ->envio->toBe('12.00')
        ->total->toBe('142.00')
        ->estado_pedido->toBe('pendiente');

    expect($pedido->detalles)->toHaveCount(2)
        ->and($pedido->detalles->firstWhere('libro_id', $primerLibro->id)->precio_unitario)->toBe('50.00')
        ->and($primerLibro->fresh()->stock)->toBe(3)
        ->and($primerLibro->fresh()->ventas)->toBe(4)
        ->and($segundoLibro->fresh()->stock)->toBe(3);

    $this->assertDatabaseHas('movimientos_inventario', [
        'libro_id' => $primerLibro->id,
        'user_id' => $usuario->id,
        'tipo' => 'venta',
        'cantidad' => -2,
        'stock_anterior' => 5,
        'stock_nuevo' => 3,
    ]);
});

it('aplica envio gratis desde ciento cincuenta soles', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create([
        'precio' => 75,
        'stock' => 2,
    ]);

    $this->actingAs($usuario)
        ->withSession(['carrito' => [$libro->id => 2]])
        ->post(route('checkout.store'), [
            'direccion' => 'Jr. Los Libros 456, Lima Cercado',
        ]);

    expect(Pedido::query()->sole())
        ->subtotal->toBe('150.00')
        ->envio->toBe('0.00')
        ->total->toBe('150.00');
});

it('no crea un pedido si el stock cambia antes de confirmar', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create(['stock' => 2]);
    $libro->update(['stock' => 1]);

    $this->actingAs($usuario)
        ->withSession(['carrito' => [$libro->id => 2]])
        ->post(route('checkout.store'), [
            'direccion' => 'Av. Sin Stock 789, Lima',
        ])
        ->assertSessionHasErrors('carrito');

    $this->assertDatabaseCount('pedidos', 0);
    $this->assertDatabaseCount('pedido_detalles', 0);
    $this->assertDatabaseCount('movimientos_inventario', 0);
    expect($libro->fresh()->stock)->toBe(1);
});

it('cancela un pedido restaurando stock una sola vez', function () {
    $usuario = User::factory()->create();
    $libro = Libro::factory()->create([
        'precio' => 60,
        'stock' => 3,
        'ventas' => 0,
    ]);

    $this->actingAs($usuario)
        ->withSession(['carrito' => [$libro->id => 2]])
        ->post(route('checkout.store'), [
            'direccion' => 'Calle Cancelacion 123, Lima',
        ]);

    $pedido = Pedido::query()->sole();
    expect($libro->fresh()->stock)->toBe(1);

    $this->actingAs($usuario)->post(route('pedidos.cancel', $pedido))->assertRedirect();
    $this->actingAs($usuario)->post(route('pedidos.cancel', $pedido))->assertRedirect();

    expect($pedido->fresh())
        ->estado_pedido->toBe('cancelado')
        ->cancelado_at->not->toBeNull()
        ->and($libro->fresh()->stock)->toBe(3)
        ->and($libro->fresh()->ventas)->toBe(0);

    expect(MovimientoInventario::query()
        ->where('libro_id', $libro->id)
        ->where('tipo', 'devolucion')
        ->count())->toBe(1);
});

it('impide ver o cancelar pedidos de otro usuario', function () {
    $propietario = User::factory()->create();
    $otroUsuario = User::factory()->create();
    $pedido = Pedido::factory()->create(['user_id' => $propietario->id]);

    $this->actingAs($otroUsuario)
        ->get(route('pedidos.show', $pedido))
        ->assertForbidden();

    $this->actingAs($otroUsuario)
        ->post(route('pedidos.cancel', $pedido))
        ->assertForbidden();
});
