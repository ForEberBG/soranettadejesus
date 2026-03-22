@extends('layouts.app')
@section('title', 'Reportes')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">📈 Reportes y Estadísticas</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">Ingresos, gastos y utilidad del aula</p>
    </div>

    {{-- Métricas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center" style="border-left:4px solid #2d8a48">
                <div class="card-body">
                    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;font-weight:700">Total
                        ingresos</div>
                    <div class="fw-bold text-success" style="font-size:1.6rem">S/ {{ number_format($totalIngresos,2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="border-left:4px solid #c0392b">
                <div class="card-body">
                    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;font-weight:700">Total
                        gastos</div>
                    <div class="fw-bold text-danger" style="font-size:1.6rem">S/ {{ number_format($totalGastos,2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="border-left:4px solid {{ $utilidad >= 0 ? '#2d8a48' : '#c0392b' }}">
                <div class="card-body">
                    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;font-weight:700">Utilidad
                        neta</div>
                    <div class="fw-bold {{ $utilidad >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:1.6rem">
                        S/ {{ number_format($utilidad,2) }}
                    </div>
                    <div class="text-muted" style="font-size:0.75rem">{{ $utilidad >= 0 ? 'Superávit' : 'Déficit' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center" style="border-left:4px solid #c8991a">
                <div class="card-body">
                    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;font-weight:700">% Cobrado
                    </div>
                    <div class="fw-bold text-warning" style="font-size:1.6rem">{{ $pctCobrado }}%</div>
                    <div class="text-muted" style="font-size:0.75rem">de la meta total</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        {{-- Resumen financiero --}}
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">📊 Resumen financiero</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless" style="font-size:0.9rem">
                        <tr>
                            <td>Total ingresos</td>
                            <td class="text-end fw-bold text-success">S/ {{ number_format($totalIngresos,2) }}</td>
                        </tr>
                        <tr>
                            <td>Total gastos</td>
                            <td class="text-end fw-bold text-danger">– S/ {{ number_format($totalGastos,2) }}</td>
                        </tr>
                        <tr style="border-top:2px solid #dde8df">
                            <td class="fw-bold">Utilidad neta</td>
                            <td class="text-end fw-bold {{ $utilidad >= 0 ? 'text-success' : 'text-danger' }}"
                                style="font-size:1.1rem">
                                S/ {{ number_format($utilidad,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Meta total</td>
                            <td class="text-end">S/ {{ number_format($metaTotal,2) }}</td>
                        </tr>
                        <tr>
                            <td>Pendiente por cobrar</td>
                            <td class="text-end text-danger">S/ {{ number_format($metaTotal - $totalIngresos,2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Alumnos con deuda --}}
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">⚠️ Alumnos con deuda pendiente</h6>
                </div>
                <div class="card-body p-0">
                    @forelse($alumnosDeudores as $al)
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <div>
                            <div class="fw-bold" style="font-size:0.9rem">{{ $al->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:0.75rem">{{ $al->celular ?? 'Sin celular' }}</div>
                        </div>
                        <span class="badge bg-danger" style="font-size:0.85rem">S/ {{ number_format($al->deuda_total,2)
                            }}</span>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block text-success opacity-50"></i>
                        Todos los alumnos están al día ✓
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle por actividad --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">📅 Detalle por actividad</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.85rem">
                <thead>
                    <div style="display:flex; gap:8px">
                        <a href="{{ route('pdf.estado-pagos') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Estado pagos
                        </a>
                        <a href="{{ route('pdf.cobros') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Cobros
                        </a>
                        <a href="{{ route('pdf.gastos') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Gastos
                        </a>
                        <a href="{{ route('pdf.ingresos-gastos') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Ing/Gastos
                        </a>
                        <a href="{{ route('pdf.utilidad') }}" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Utilidad
                        </a>
                    </div>
                    <tr style="background:#e8f5ec">
                        <th>Actividad</th>
                        <th class="text-end">Cuota</th>
                        <th class="text-end">Meta</th>
                        <th class="text-end">Cobrado</th>
                        <th class="text-end">Pendiente</th>
                        <th class="text-end">Gastos</th>
                        <th class="text-end">Utilidad</th>
                        <th>Avance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($actividades as $act)
                    @php
                    $meta = $act->cuota * $totalAlumnos;
                    $cobrado = $act->cobros_sum_monto ?? 0;
                    $gastosAct = $act->gastos_sum_monto ?? 0;
                    $pendiente = $meta - $cobrado;
                    $util = $cobrado - $gastosAct;
                    $pct = $meta > 0 ? min(100, round($cobrado / $meta * 100)) : 0;
                    $color = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ $act->nombre }}</td>
                        <td class="text-end">S/ {{ number_format($act->cuota,2) }}</td>
                        <td class="text-end">S/ {{ number_format($meta,2) }}</td>
                        <td class="text-end text-success fw-bold">S/ {{ number_format($cobrado,2) }}</td>
                        <td class="text-end {{ $pendiente > 0 ? 'text-danger' : 'text-success' }}">
                            S/ {{ number_format($pendiente,2) }}
                        </td>
                        <td class="text-end text-danger">S/ {{ number_format($gastosAct,2) }}</td>
                        <td class="text-end fw-bold {{ $util >= 0 ? 'text-success' : 'text-danger' }}">
                            S/ {{ number_format($util,2) }}
                        </td>
                        <td style="min-width:100px">
                            <div class="progress" style="height:6px">
                                <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                            </div>
                            <div class="text-muted" style="font-size:0.7rem;margin-top:2px">{{ $pct }}%</div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#e8f5ec;font-weight:700">
                        <td>TOTALES</td>
                        <td></td>
                        <td class="text-end">S/ {{ number_format($metaTotal,2) }}</td>
                        <td class="text-end text-success">S/ {{ number_format($totalIngresos,2) }}</td>
                        <td class="text-end text-danger">S/ {{ number_format($metaTotal - $totalIngresos,2) }}</td>
                        <td class="text-end text-danger">S/ {{ number_format($totalGastos,2) }}</td>
                        <td class="text-end {{ $utilidad >= 0 ? 'text-success' : 'text-danger' }}">
                            S/ {{ number_format($utilidad,2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
