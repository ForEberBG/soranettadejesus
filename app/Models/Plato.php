<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_id',
        'imagen',
        'estado',
    ];

    // Relación: un plato pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(CategoriaPlato::class, 'categoria_id');
    }

    // Relación: un plato tiene muchos ingredientes (many-to-many)
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'ingrediente_plato')
                    ->withPivot('cantidad_usada')
                    ->withTimestamps();
    }

    // Relación: un plato puede estar en muchas ventas
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
