<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'libro_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
    ];

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
