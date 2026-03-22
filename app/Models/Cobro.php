<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobros';
    protected $fillable = [
    'alumno_id','actividad_id','monto','fecha',
    'observaciones','metodo_pago','captura', // ← agregar
    ];
    protected $casts    = ['fecha' => 'date'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
}
