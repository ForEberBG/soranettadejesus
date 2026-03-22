<?php

namespace App\Http\Controllers;

use App\Models\{Alumno, Actividad, Cobro, Gasto, Reunion, Configuracion};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    // ── Opciones comunes para todos los PDFs ──
    private function opciones()
    {
        return [
            'margin_top'            => 10,
            'margin_bottom'         => 10,
            'margin_left'           => 12,
            'margin_right'          => 12,
            'dpi'                   => 96,
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true,
        ];
    }

    private function config()
    {
        return Configuracion::first();
    }

    // 1. Lista de alumnos
    public function listaAlumnos()
    {
        $config  = $this->config();
        $alumnos = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $pdf = Pdf::loadView('aula.pdf.lista-alumnos', compact('alumnos','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('lista-alumnos-'.date('Y-m-d').'.pdf');
    }

    // 2. Asistencia a reunión
    public function asistenciaReunion(Reunion $reunion)
    {
        $config  = $this->config();
        $alumnos = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $pdf = Pdf::loadView('aula.pdf.asistencia-reunion', compact('reunion','alumnos','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('asistencia-reunion-'.date('Y-m-d').'.pdf');
    }

    // 3. Estado de pagos por alumno
    public function estadoPagos()
    {
        $config      = $this->config();
        $alumnos     = Alumno::where('activo', true)->orderBy('apellidos')->get();
        $actividades = Actividad::where('activo', true)->get();
        $cobros      = Cobro::all();
        $pdf = Pdf::loadView('aula.pdf.estado-pagos', compact('alumnos','actividades','cobros','config'))
                  ->setPaper('a4', 'landscape')
                  ->setOptions($this->opciones());
        return $pdf->download('estado-pagos-'.date('Y-m-d').'.pdf');
    }

    // 4. Cobros por actividad
    public function cobrosActividad(Request $request)
    {
        $config       = $this->config();
        $actividades  = Actividad::where('activo', true)
                          ->withSum('cobros','monto')->get();
        $totalAlumnos = Alumno::where('activo', true)->count();
        $cobros       = Cobro::with('alumno','actividad')
                          ->when($request->actividad_id, fn($q,$id) => $q->where('actividad_id',$id))
                          ->latest('fecha')->get();
        $pdf = Pdf::loadView('aula.pdf.cobros-actividad', compact('actividades','cobros','totalAlumnos','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('cobros-actividad-'.date('Y-m-d').'.pdf');
    }

    // 5. Gastos detallados
    public function gastos()
    {
        $config  = $this->config();
        $gastos  = Gasto::with('actividad')->latest('fecha')->get();
        $total   = $gastos->sum('monto');
        $porCat  = $gastos->groupBy('categoria');
        $pdf = Pdf::loadView('aula.pdf.gastos', compact('gastos','total','porCat','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('gastos-'.date('Y-m-d').'.pdf');
    }

    // 6. Reporte de ingresos y gastos
    public function ingresosGastos()
    {
        $config        = $this->config();
        $totalIngresos = Cobro::sum('monto');
        $totalGastos   = Gasto::sum('monto');
        $utilidad      = $totalIngresos - $totalGastos;
        $totalAlumnos  = Alumno::where('activo', true)->count();
        $actividades   = Actividad::where('activo', true)
                            ->withSum('cobros','monto')
                            ->withSum('gastos','monto')->get();
        $metaTotal     = $actividades->sum(fn($a) => $a->cuota * $totalAlumnos);
        $pdf = Pdf::loadView('aula.pdf.ingresos-gastos', compact(
                    'totalIngresos','totalGastos','utilidad',
                    'actividades','metaTotal','totalAlumnos','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('ingresos-gastos-'.date('Y-m-d').'.pdf');
    }

    // 7. Reporte general de utilidad
    public function utilidad()
    {
        $config        = $this->config();
        $totalIngresos = Cobro::sum('monto');
        $totalGastos   = Gasto::sum('monto');
        $utilidad      = $totalIngresos - $totalGastos;
        $totalAlumnos  = Alumno::where('activo', true)->count();
        $actividades   = Actividad::where('activo', true)
                            ->withSum('cobros','monto')
                            ->withSum('gastos','monto')->get();
        $cobros        = Cobro::with('alumno','actividad')->latest('fecha')->get();
        $gastos        = Gasto::with('actividad')->latest('fecha')->get();
        $pdf = Pdf::loadView('aula.pdf.utilidad', compact(
                    'totalIngresos','totalGastos','utilidad',
                    'actividades','cobros','gastos','totalAlumnos','config'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions($this->opciones());
        return $pdf->download('utilidad-'.date('Y-m-d').'.pdf');
    }
}
