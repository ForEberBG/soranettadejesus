<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = ['nombre','cuota','fecha_limite','descripcion','activo'];
    protected $casts    = ['fecha_limite' => 'date'];

    public function cobros(): HasMany
    {
        return $this->hasMany(Cobro::class);
    }

    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class);
    }
}
