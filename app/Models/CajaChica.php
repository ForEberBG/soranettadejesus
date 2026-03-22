<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CajaChica extends Model
{
    protected $table    = 'caja_chica';
    protected $fillable = [
        'monto_inicial','saldo_actual','estado',
        'descripcion','user_id','fecha_apertura','fecha_cierre'
    ];
    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre'   => 'datetime',
    ];

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoCaja::class, 'caja_chica_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalEgresosAttribute(): float
    {
        return $this->movimientos->where('tipo','egreso')->sum('monto');
    }

    public function getTotalReposicionesAttribute(): float
    {
        return $this->movimientos->where('tipo','reposicion')->sum('monto');
    }
}
