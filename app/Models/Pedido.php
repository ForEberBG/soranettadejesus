<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'estado',
        'nota',
        'numero_dia',
        'fecha_dia',
    ];

    // Relación: un pedido pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }
}
