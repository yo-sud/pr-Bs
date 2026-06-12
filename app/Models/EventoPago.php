<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoPago extends Model
{
    protected $table = 'eventos_pago';

    protected $fillable = [
        'transaccion_pago_id',
        'evento_id',
        'estado',
        'payload',
        'procesado_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'procesado_at' => 'datetime',
        ];
    }

    public function transaccion(): BelongsTo
    {
        return $this->belongsTo(TransaccionPago::class, 'transaccion_pago_id');
    }
}
