@extends('layouts.app')
@section('title', 'Dashboard - IE Sor Annetta de Jesús')

@section('content')
<style>
    .stat-card {
        background: rgba(255,255,255,0.95);
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid #2d8a48;
        text-decoration: none;
        color: inherit;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(26,92,46,0.18);
    }
    .stat-card.dorado { border-left-color: #c8991a; }
    .stat-card.rojo   { border-left-color: #c0392b; }
    .stat-card.azul   { border-left-color: #2980b9; }

    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-value {
        font-family: 'Merriweather Sans', sans-serif;
        font-size: 1.8rem; font-weight: 800;
        color: #1a3d22; line-height: 1;
    }
    .stat-label {
        font-size: 0.72rem; color: #888;
        text-transform: uppercase; letter-spacing: 1px;
        font-weight: 700; margin-top: 3px;
    }
    .chart-card {
        background: rgba(255,255,255,0.95);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        overflow: hidden;
    }
    .chart-header {
        background: linear-gradient(135deg, #1a5c2e, #2d8a48);
        color: white; padding: 14px 20px;
        font-family: 'Merriweather Sans', sans-serif;
        font-weight: 700; font-size: 0.9rem;
        display: flex; align-items: center; gap: 8px;
        border-bottom: 2px solid rgba(200,153,26,0.4);
    }
    .welcome-bar {
        background: linear-gradient(135deg, #1a5c2e, #2d8a48);
        border-radius: 14px; padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid rgba(200,153,26,0.3);
        display: flex; align-items: center; justify-content: space-between;
    }
    .progress { height: 7px; border-radius: 20px; }
</style>

{{-- Alertas de deuda --}}
@php
    $deudores = $alumnosDeudores->count();
@endphp
@if($deudores > 0)
<div class="alert mb-4" style="background:#fdf6e0;border:1px solid #c8991a;border-radius:12px;padding:14px 18px">
    <div style="font-weight:800;color:#7a5a00;margin-bottom:6px">
        ⚠️ {{ $deudores }} alumno(s) con cuotas pendientes
    </div>
    <div class="d-flex flex-wrap gap-2">
        @foreach($alumnosDeudores as $al)
        <span style="background:white;border:1px solid #c8991a;border-radius:8px;padding:4px 10px;font-size:0.82rem">
            <strong>{{ $al->nombre_completo }}</strong>:
            <span style="color:#c0392b;font-weight:800">S/ {{ number_format($al->deuda_total,2) }}</span>
        </span>
        @endforeach
    </div>
    <a href="{{ url('admin/cuotas') }}" style="display:inline-block;margin-top:8px;font-size:0.82rem;color:#7a5a00;font-weight:700">
        → Gestionar cobros
    </a>
</div>
@endif

{{-- Welcome Bar --}}
<div class="welcome-bar">
    <div>
        <h4 style="font-family:'Merriweather Sans',sans-serif;color:#f0c040;margin:0;font-weight:800">
            🎓 Bienvenido, {{ Auth::user()->name }}
        </h4>
        <small style="color:rgba(255,255,255,0.65)">
            {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </small>
    </div>
    <div style="text-align:right">
        <div style="color:#f0c040;font-weight:700;font-size:0.85rem">IE Sor Annetta de Jesús</div>
        <small style="color:rgba(255,255,255,0.5)">Control de Aula · {{ date('Y') }}</small>
    </div>
</div>

{{-- Métricas --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/alumnos') }}" class="stat-card d-flex">
            <div class="stat-icon" style="background:rgba(45,138,72,0.12)">
                <i class="fas fa-user-graduate" style="color:#1a5c2e"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalAlumnos }}</div>
                <div class="stat-label">Alumnos</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/actividades') }}" class="stat-card d-flex">
            <div class="stat-icon" style="background:rgba(45,138,72,0.12)">
                <i class="fas fa-calendar-alt" style="color:#1a5c2e"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalActividades }}</div>
                <div class="stat-label">Actividades</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/cuotas') }}" class="stat-card dorado d-flex">
            <div class="stat-icon" style="background:rgba(200,153,26,0.12)">
                <i class="fas fa-hand-holding-usd" style="color:#c8991a"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#7a5a00;font-size:1.2rem">
                    S/ {{ number_format($totalIngresos,0) }}
                </div>
                <div class="stat-label">Ingresos</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/gastos') }}" class="stat-card rojo d-flex">
            <div class="stat-icon" style="background:rgba(192,57,43,0.1)">
                <i class="fas fa-receipt" style="color:#c0392b"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#c0392b;font-size:1.2rem">
                    S/ {{ number_format($totalGastos,0) }}
                </div>
                <div class="stat-label">Gastos</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/reportes-aula') }}" class="stat-card {{ $utilidad >= 0 ? '' : 'rojo' }} d-flex">
            <div class="stat-icon" style="background:rgba(45,138,72,0.12)">
                <i class="fas fa-chart-line" style="color:{{ $utilidad >= 0 ? '#1a5c2e' : '#c0392b' }}"></i>
            </div>
            <div>
                <div class="stat-value" style="color:{{ $utilidad >= 0 ? '#1a5c2e' : '#c0392b' }};font-size:1.2rem">
                    S/ {{ number_format($utilidad,0) }}
                </div>
                <div class="stat-label">Utilidad</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ url('admin/reuniones') }}" class="stat-card azul d-flex">
            <div class="stat-icon" style="background:rgba(41,128,185,0.1)">
                <i class="fas fa-handshake" style="color:#2980b9"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalReuniones }}</div>
                <div class="stat-label">Reuniones</div>
            </div>
        </a>
    </div>
</div>

{{-- Gráficos --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="chart-card">
            <div class="chart-header">
                <i class="fas fa-chart-bar" style="color:#f0c040"></i> Ingresos vs Gastos (últimos 6 meses)
            </div>
            <div class="p-3"><canvas id="graficoIngGastos" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="chart-card h-100">
            <div class="chart-header">
                <i class="fas fa-chart-pie" style="color:#f0c040"></i> Distribución financiera
            </div>
            <div class="p-3 d-flex align-items-center justify-content-center" style="min-height:200px">
                <canvas id="graficoDist" style="max-height:200px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Actividades y Movimientos --}}
<div class="row g-3">
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-header">
                <i class="fas fa-calendar-alt" style="color:#f0c040"></i> Avance por actividad
            </div>
            <div class="p-3">
                @forelse($actividades as $act)
                    @php
                        $meta    = $act->cuota * $totalAlumnos;
                        $cobrado = $act->cobros_sum_monto ?? 0;
                        $pct     = $meta > 0 ? min(100, round($cobrado / $meta * 100)) : 0;
                        $color   = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem">
                            <span class="fw-bold">{{ $act->nombre }}</span>
                            <span class="text-muted">{{ $pct }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size:0.72rem;color:#888">
                            <span>Cobrado: S/ {{ number_format($cobrado,2) }}</span>
                            <span>Meta: S/ {{ number_format($meta,2) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar fa-2x mb-2 d-block opacity-25"></i>
                        Sin actividades.
                        <a href="{{ url('admin/actividades') }}" style="color:#1a5c2e">Crear una</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-header">
                <i class="fas fa-clock" style="color:#f0c040"></i> Últimas transacciones
            </div>
            <div class="p-0">
                <table class="table table-hover mb-0" style="font-size:0.85rem">
                    <tbody>
                        @forelse($ultimosMovimientos as $mov)
                        <tr>
                            <td style="width:80px;color:#888">
                                {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                            </td>
                            <td>{{ $mov->descripcion }}</td>
                            <td class="text-end fw-bold {{ $mov->tipo === 'ingreso' ? 'text-success' : 'text-danger' }}">
                                {{ $mov->tipo === 'ingreso' ? '+' : '-' }}S/ {{ number_format($mov->monto,2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Sin movimientos aún</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const cobrosPorMes = @json($cobrosPorMes);
const gastosPorMes = @json($gastosPorMes);
const totalIngresos = {{ $totalIngresos }};
const totalGastos   = {{ $totalGastos }};
const utilidad      = {{ $utilidad }};

// Gráfico barras ingresos vs gastos
new Chart(document.getElementById('graficoIngGastos'), {
    type: 'bar',
    data: {
        labels: Object.keys(cobrosPorMes),
        datasets: [
            {
                label: 'Ingresos',
                data: Object.values(cobrosPorMes),
                backgroundColor: 'rgba(45,138,72,0.7)',
                borderColor: '#1a5c2e',
                borderWidth: 1, borderRadius: 6,
            },
            {
                label: 'Gastos',
                data: Object.values(gastosPorMes),
                backgroundColor: 'rgba(192,57,43,0.7)',
                borderColor: '#c0392b',
                borderWidth: 1, borderRadius: 6,
            }
        ]
    },
    options: {
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});

// Gráfico dona distribución
new Chart(document.getElementById('graficoDist'), {
    type: 'doughnut',
    data: {
        labels: ['Ingresos', 'Gastos', 'Utilidad'],
        datasets: [{
            data: [totalIngresos, totalGastos, Math.max(0, utilidad)],
            backgroundColor: ['#2d8a48', '#c0392b', '#c8991a'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
});
</script>
@stop
