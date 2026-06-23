<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repartidor extends Model
{
    use HasFactory;

    protected $table = 'repartidores';

    protected $fillable = [
        'nombre_empresa',
        'ruc',
        'telefono',
        'correo',
        'tiempo_entrega_estimado', 
        'observaciones',          
        'activo',                  
    ];

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}