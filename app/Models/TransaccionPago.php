<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransaccionPago extends Model
{
    use HasFactory;

    protected $table = 'transacciones_pago';

    protected $fillable = [
        'pedido_id',
        'referencia',
        'monto',
        'moneda',
        'estado',
        'payload',
        'procesado_at',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'payload' => 'array',
            'procesado_at' => 'datetime',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(EventoPago::class);
    }
}
