<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'titulo',
        'autor',
        'descripcion',
        'editorial',
        'fecha_publicacion',
        'portada',
        'precio',
        'stock',
        'estado',
        'destacado',
        'ventas',
        'categoria_id',
        'proveedor_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'date',
            'precio' => 'decimal:2',
            'destacado' => 'boolean',
        ];
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function getPortadaUrlAttribute(): string
    {
        if (! $this->portada) {
            return '/images/book-placeholder.svg';
        }

        if (str_starts_with($this->portada, 'http://') || str_starts_with($this->portada, 'https://')) {
            return $this->portada;
        }

        if (str_starts_with($this->portada, '/images/') || str_starts_with($this->portada, 'images/')) {
            return '/'.ltrim($this->portada, '/');
        }

        // Resuelve la ruta desde el almacenamiento público de Laravel.
        if (str_starts_with($this->portada, 'portadas/') || str_contains($this->portada, '/')) {
            return '/storage/'.ltrim($this->portada, '/');
        }

        // Resuelve la ruta desde la carpeta local de portadas.
        if (str_ends_with($this->portada, '.jpg') || str_ends_with($this->portada, '.png') || str_ends_with($this->portada, '.jpeg')) {
            return '/images/portadas/'.ltrim($this->portada, '/');
        }

        return '/storage/'.ltrim($this->portada, '/');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function pedidoDetalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class);
    }
}
