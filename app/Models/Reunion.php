<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reunion extends Model
{
    protected $table = 'reuniones';
    protected $fillable = ['tema','fecha','hora','lugar','notas','imagen_acta'];
    protected $casts    = ['fecha' => 'date'];

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }
}
