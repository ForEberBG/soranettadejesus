<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $fillable = ['reunion_id','alumno_id','asistio'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function reunion()
    {
        return $this->belongsTo(Reunion::class);
    }
}
