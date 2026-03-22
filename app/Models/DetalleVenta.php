<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta';

    protected $fillable = [
        'venta_id',
        'plato_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación: este detalle pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    // Relación: este detalle hace referencia a un plato
    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}
