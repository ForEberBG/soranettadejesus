<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Actividad;
use App\Models\Cobro;
use App\Models\Gasto;
use App\Models\Reunion;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // ── Métricas principales ──
        $totalAlumnos  = Alumno::where('activo', true)->count();
        $totalIngresos = Cobro::sum('monto');
        $totalGastos   = Gasto::sum('monto');
        $utilidad      = $totalIngresos - $totalGastos;
        $totalReuniones = Reunion::count();
        $totalActividades = Actividad::where('activo', true)->count();

        // ── Actividades con avance ──
        $actividades = Actividad::where('activo', true)
            ->withSum('cobros', 'monto')
            ->get();

        // ── Alumnos con deuda ──
        $alumnosDeudores = Alumno::where('activo', true)
            ->get()
            ->filter(fn($a) => $a->deuda_total > 0)
            ->sortByDesc('deuda_total')
            ->take(5);

        // ── Cobros por mes (últimos 6 meses) ──
        $cobrosPorMes = [];
        $gastosPorMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $label = $mes->isoFormat('MMM');
            $cobrosPorMes[$label] = Cobro::whereYear('fecha', $mes->year)
                ->whereMonth('fecha', $mes->month)
                ->sum('monto');
            $gastosPorMes[$label] = Gasto::whereYear('fecha', $mes->year)
                ->whereMonth('fecha', $mes->month)
                ->sum('monto');
        }

        // ── Últimos movimientos ──
        $cobros = Cobro::with('alumno', 'actividad')
            ->latest('fecha')->limit(5)->get()
            ->map(fn($c) => (object)[
                'fecha'       => $c->fecha,
                'descripcion' => $c->alumno->nombre_completo . ' — ' . $c->actividad->nombre,
                'monto'       => $c->monto,
                'tipo'        => 'ingreso',
            ]);

        $gastosMov = Gasto::latest('fecha')->limit(5)->get()
            ->map(fn($g) => (object)[
                'fecha'       => $g->fecha,
                'descripcion' => $g->descripcion,
                'monto'       => $g->monto,
                'tipo'        => 'gasto',
            ]);

        $ultimosMovimientos = $cobros->concat($gastosMov)
            ->sortByDesc('fecha')->take(8);

        // ── % cobrado ──
        $metaTotal  = $actividades->sum(fn($a) => $a->cuota * $totalAlumnos);
        $pctCobrado = $metaTotal > 0 ? round($totalIngresos / $metaTotal * 100) : 0;

        return view('admin.index', compact(
            'totalAlumnos', 'totalIngresos', 'totalGastos', 'utilidad',
            'totalReuniones', 'totalActividades',
            'actividades', 'alumnosDeudores', 'ultimosMovimientos',
            'cobrosPorMes', 'gastosPorMes', 'metaTotal', 'pctCobrado'
        ));
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
