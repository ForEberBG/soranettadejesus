<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';
    protected $fillable = ['descripcion','monto','categoria','actividad_id','fecha','comprobante'];
    protected $casts    = ['fecha' => 'date'];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
}
