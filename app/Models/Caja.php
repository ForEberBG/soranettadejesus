<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $fillable = [
        'usuario_id',
        'monto_inicial',
        'total_efectivo',
        'total_yape',
        'total_plin',
        'total_tarjeta',
        'total_ventas',
        'num_ventas',
        'observaciones',
        'apertura_at',
        'cierre_at',
        'estado',
    ];

    protected $casts = [
        'apertura_at' => 'datetime',
        'cierre_at'   => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
