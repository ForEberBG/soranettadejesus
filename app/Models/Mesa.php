<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'estado',
        'capacidad',
    ];

    // Relación: una mesa puede tener muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
