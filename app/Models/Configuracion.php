<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracions';

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'email',
        'web',
        'moneda',
        'logo',
        'aula',        // ← nuevo
        'docente',     // ← nuevo
        'anio_escolar',// ← nuevo
        'turno',       // ← nuevo
    ];
}
