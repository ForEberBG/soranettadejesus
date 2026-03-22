<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $fillable = [
        'apellidos',
        'nombres',
        'dni',
        'seccion',
        'apoderado',
        'celular',
        'parentesco',
        'observaciones',
        'activo',
        'foto', // ← agregar
    ];

    public function getNombreCompletoAttribute(): string
    {
        return $this->apellidos . ', ' . $this->nombres;
    }

    public function cobros(): HasMany
    {
        return $this->hasMany(Cobro::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function getDeudaTotalAttribute(): float
    {
        $actividades = Actividad::where('activo', true)->get();
        $deuda = 0;
        foreach ($actividades as $act) {
            $pagado = $this->cobros()->where('actividad_id', $act->id)->sum('monto');
            if ($pagado < $act->cuota) {
                $deuda += ($act->cuota - $pagado);
            }
        }
        return $deuda;
    }

    public function getTotalPagadoAttribute(): float
    {
        return $this->cobros()->sum('monto');
    }
}
