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
