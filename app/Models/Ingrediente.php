<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'unidad',
        'stock',
        'stock_minimo',
        'precio',
    ];

    // Relación: un ingrediente pertenece a muchos platos
    public function platos()
    {
        return $this->belongsToMany(Plato::class, 'ingrediente_plato')
                    ->withPivot('cantidad_usada')
                    ->withTimestamps();
    }

    // Relación: un ingrediente puede aparecer en muchas compras
    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
