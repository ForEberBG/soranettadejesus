<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaPlato extends Model
{
    use HasFactory;

    protected $table = 'categorias_platos';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relación: una categoría tiene muchos platos
    public function platos()
    {
        return $this->hasMany(Plato::class, 'categoria_id');
    }
}
