<?php

use App\Mail\PedidoActualizadoMail;
use App\Models\EventoPago;
use App\Models\Pedido;
use App\Models\TransaccionPago;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

it('procesa un pago simulado usando el total guardado en el pedido', function () {
    Mail::fake();

    $cliente = User::factory()->create();
    $pedido = Pedido::factory()->create([
        'user_id' => $cliente->id,
        'total' => 125.50,
        'estado_pago' => 'pendiente',
        'estado_pedido' => 'pendiente',
    ]);

    $this->actingAs($cliente)
        ->post(route('pagos.store', $pedido), ['resultado' => 'aprobado'])
        ->assertRedirect(route('pedidos.show', $pedido));

    $transaccion = TransaccionPago::query()->sole();

    expect($transaccion)
        ->monto->toBe('125.50')
        ->moneda->toBe('PEN')
        ->estado->toBe('aprobado')
        ->and($pedido->fresh())
        ->estado_pago->toBe('pagado')
        ->estado_pedido->toBe('pagado')
        ->pagado_at->not->toBeNull();

    $this->assertDatabaseHas('pedido_estados', [
        'pedido_id' => $pedido->id,
        'estado_anterior' => 'pendiente',
        'estado_nuevo' => 'pagado',
    ]);
    Mail::assertQueued(PedidoActualizadoMail::class);
});

it('registra pagos rechazados y pendientes sin avanzar el pedido', function (string $resultado, string $estadoPago) {
    $cliente = User::factory()->create();
    $pedido = Pedido::factory()->create([
        'user_id' => $cliente->id,
        'estado_pago' => 'pendiente',
        'estado_pedido' => 'pendiente',
    ]);

    $this->actingAs($cliente)
        ->post(route('pagos.store', $pedido), ['resultado' => $resultado])
        ->assertRedirect(route('pedidos.show', $pedido));

    expect($pedido->fresh())
        ->estado_pago->toBe($estadoPago)
        ->estado_pedido->toBe('pendiente');
})->with([
    'rechazado' => ['rechazado', 'fallido'],
    'pendiente' => ['pendiente', 'pendiente'],
]);

it('rechaza webhooks sin firma valida', function () {
    $this->postJson(route('webhooks.pagos.falso'), [
        'evento_id' => (string) Str::uuid(),
        'referencia' => (string) Str::uuid(),
        'monto' => 10,
        'moneda' => 'PEN',
        'estado' => 'aprobado',
    ])->assertUnauthorized();
});

it('procesa un webhook firmado una sola vez aunque el evento se repita', function () {
    Mail::fake();

    $cliente = User::factory()->create();
    $pedido = Pedido::factory()->create([
        'user_id' => $cliente->id,
        'total' => 89.90,
        'estado_pago' => 'pendiente',
        'estado_pedido' => 'pendiente',
    ]);
    $transaccion = TransaccionPago::query()->create([
        'pedido_id' => $pedido->id,
        'referencia' => (string) Str::uuid(),
        'monto' => $pedido->total,
        'moneda' => 'PEN',
        'estado' => 'pendiente',
    ]);
    $payload = [
        'evento_id' => (string) Str::uuid(),
        'referencia' => $transaccion->referencia,
        'monto' => 89.90,
        'moneda' => 'PEN',
        'estado' => 'aprobado',
    ];

    $primera = enviarWebhookPago($this, $payload);
    $segunda = enviarWebhookPago($this, $payload);

    $primera->assertOk()->assertJson(['procesado' => true, 'estado' => 'aprobado']);
    $segunda->assertOk()->assertJson(['procesado' => true, 'estado' => 'aprobado']);

    expect(EventoPago::query()->count())->toBe(1)
        ->and($pedido->fresh()->estado_pago)->toBe('pagado')
        ->and($pedido->historialEstados()->where('estado_nuevo', 'pagado')->count())->toBe(1);
});

it('rechaza un webhook cuyo monto no coincide con el pedido', function () {
    $pedido = Pedido::factory()->create([
        'total' => 100,
        'estado_pago' => 'pendiente',
        'estado_pedido' => 'pendiente',
    ]);
    $transaccion = TransaccionPago::query()->create([
        'pedido_id' => $pedido->id,
        'referencia' => (string) Str::uuid(),
        'monto' => 100,
        'moneda' => 'PEN',
        'estado' => 'pendiente',
    ]);

    enviarWebhookPago($this, [
        'evento_id' => (string) Str::uuid(),
        'referencia' => $transaccion->referencia,
        'monto' => 1,
        'moneda' => 'PEN',
        'estado' => 'aprobado',
    ])->assertUnprocessable()->assertJsonValidationErrors('monto');

    expect($pedido->fresh()->estado_pago)->toBe('pendiente')
        ->and(EventoPago::query()->count())->toBe(0);
});

it('permite al administrador avanzar un pedido pagado hasta la entrega', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $pedido = Pedido::factory()->pagado()->create();

    $this->actingAs($admin)
        ->patch(route('admin.pedidos.update-status', $pedido), [
            'estado' => 'preparando',
            'observacion' => 'Pedido verificado y en preparacion.',
        ])
        ->assertRedirect();

    expect($pedido->fresh())
        ->estado_pedido->toBe('preparando');

    $this->assertDatabaseHas('pedido_estados', [
        'pedido_id' => $pedido->id,
        'user_id' => $admin->id,
        'estado_anterior' => 'pagado',
        'estado_nuevo' => 'preparando',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.pedidos.update-status', $pedido), [
            'estado' => 'enviado',
            'observacion' => 'Pedido recogido y en camino.',
        ])
        ->assertRedirect();

    expect($pedido->fresh())
        ->estado_pedido->toBe('enviado')
        ->enviado_at->not->toBeNull();

    $this->actingAs($admin)
        ->patch(route('admin.pedidos.update-status', $pedido), [
            'estado' => 'entregado',
            'observacion' => 'Entregado al cliente.',
        ])
        ->assertRedirect();

    expect($pedido->fresh())
        ->estado_pedido->toBe('entregado')
        ->entregado_at->not->toBeNull()
        ->and($pedido->historialEstados()->count())->toBe(3);

    Mail::assertQueued(PedidoActualizadoMail::class, 3);
});

it('impide al cliente cambiar estados de despacho', function () {
    $cliente = User::factory()->create();
    $pedido = Pedido::factory()->pagado()->create(['user_id' => $cliente->id]);

    $this->actingAs($cliente)
        ->patch(route('admin.pedidos.update-status', $pedido), [
            'estado' => 'preparando',
            'observacion' => 'Intento no autorizado.',
        ])
        ->assertForbidden();

    expect($pedido->fresh()->estado_pedido)->toBe('pagado');
});

it('obliga al administrador a respetar el orden del despacho', function () {
    $admin = User::factory()->admin()->create();
    $pedido = Pedido::factory()->pagado()->create();

    $this->actingAs($admin)
        ->patch(route('admin.pedidos.update-status', $pedido), [
            'estado' => 'entregado',
            'observacion' => 'Salto de estado invalido.',
        ])
        ->assertSessionHasErrors('estado');

    expect($pedido->fresh()->estado_pedido)->toBe('pagado');
});

function enviarWebhookPago($test, array $payload)
{
    $contenido = json_encode($payload, JSON_THROW_ON_ERROR);
    $firma = hash_hmac('sha256', $contenido, (string) config('services.fake_payment.webhook_secret'));

    return $test->call(
        'POST',
        route('webhooks.pagos.falso'),
        [],
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_BOOKSHOP_SIGNATURE' => $firma,
        ],
        $contenido,
    );
}
