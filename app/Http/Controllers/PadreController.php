<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Actividad;
use App\Models\Cobro;
use App\Models\Reunion;
use Illuminate\Http\Request;

class PadreController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $alumno = $user->alumno;

        if (!$alumno) {
            return view('aula.padre.sin-alumno');
        }

        $actividades  = Actividad::where('activo', true)->get();
        $cobros       = Cobro::where('alumno_id', $alumno->id)
                            ->with('actividad')->latest('fecha')->get();
        $reuniones    = Reunion::latest('fecha')->take(5)->get();
        $deudaTotal   = $alumno->deuda_total;

        // Estado de pago por actividad
        $estadoPagos = $actividades->map(function($act) use ($alumno, $cobros) {
            $cobro  = $cobros->where('actividad_id', $act->id)->first();
            return (object)[
                'actividad' => $act,
                'pagado'    => $cobro ? true : false,
                'monto'     => $cobro ? $cobro->monto : 0,
                'fecha'     => $cobro ? $cobro->fecha : null,
            ];
        });

        return view('aula.padre.index', compact(
            'alumno','estadoPagos','cobros','reuniones','deudaTotal'
        ));
    }
}
