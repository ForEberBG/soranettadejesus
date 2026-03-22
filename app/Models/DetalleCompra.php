<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compra';

    protected $fillable = [
        'compra_id',
        'ingrediente_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación: este detalle pertenece a una compra
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    // Relación: este detalle se refiere a un ingrediente
    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }
}
