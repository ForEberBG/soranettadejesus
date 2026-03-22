<?php

namespace App\Http\Controllers;

use App\Models\{Alumno, Actividad, Cobro, Gasto};
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        $totalAlumnos  = Alumno::where('activo', true)->count();
        $totalIngresos = Cobro::sum('monto');
        $totalGastos   = Gasto::sum('monto');
        $utilidad      = $totalIngresos - $totalGastos;

        $actividades = Actividad::where('activo', true)
            ->withSum('cobros', 'monto')
            ->withSum('gastos', 'monto')
            ->get();

        $metaTotal  = $actividades->sum(fn($a) => $a->cuota * $totalAlumnos);
        $pctCobrado = $metaTotal > 0 ? round($totalIngresos / $metaTotal * 100) : 0;

        $alumnosDeudores = Alumno::where('activo', true)
            ->get()
            ->filter(fn($a) => $a->deuda_total > 0)
            ->sortByDesc('deuda_total');

        return view('aula.reportes.index', compact(
            'totalAlumnos','totalIngresos','totalGastos','utilidad',
            'actividades','metaTotal','pctCobrado','alumnosDeudores'
        ));
    }

    public function export(Request $request)
    {
        return redirect()->route('reportes.aula')->with('success', 'Exportación en desarrollo.');
    }
}
