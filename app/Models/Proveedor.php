<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre_empresa',
        'tiempo_entrega_dias',
        'costo_envio',        
        'ruc',                  
        'telefono',               
        'correo',                
        'contacto_ejecutivo',     
        'observaciones',         
        'activo',
    ];

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class);
    }
}
