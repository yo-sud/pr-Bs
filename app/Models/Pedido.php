<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    public const ESTADOS_PAGO = [
        'pendiente',
        'pagado',
        'fallido',
        'reembolsado',
    ];

    public const ESTADOS_PEDIDO = [
        'pendiente',
        'pagado',
        'preparando',
        'enviado',
        'entregado',
        'cancelado',
    ];

    protected $fillable = [
        'user_id',
        'repartidor_id',
        'direccion',
        'subtotal',
        'envio',
        'total',
        'estado_pago',
        'estado_pedido',
        'pagado_at',
        'enviado_at',
        'entregado_at',
        'cancelado_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'envio' => 'decimal:2',
            'total' => 'decimal:2',
            'pagado_at' => 'datetime',
            'enviado_at' => 'datetime',
            'entregado_at' => 'datetime',
            'cancelado_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(Repartidor::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    public function transaccionesPago(): HasMany
    {
        return $this->hasMany(TransaccionPago::class);
    }

    public function historialEstados(): HasMany
    {
        return $this->hasMany(PedidoEstado::class);
    }
}
