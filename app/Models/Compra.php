<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id',
        'usuario_id',
        'total',
        'fecha',
    ];

    // Relación: una compra pertenece a un proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación: una compra pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: una compra tiene muchos detalles
    public function detalleCompra()
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
