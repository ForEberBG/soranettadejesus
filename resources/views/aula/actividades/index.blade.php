@extends('layouts.app')
@section('title', 'Actividades')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold" style="color:#1a5c2e">📅 Actividades y Cuotas</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem">Gestión de eventos y montos por alumno</p>
        </div>
        <a href="{{ route('actividades.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nueva Actividad
        </a>
    </div>

    <div class="row g-3">
        @forelse($actividades as $act)
            @php
                $totalAlumnos = \App\Models\Alumno::where('activo', true)->count();
                $meta    = $act->cuota * $totalAlumnos;
                $cobrado = $act->cobros_sum_monto ?? 0;
                $pct     = $meta > 0 ? min(100, round($cobrado / $meta * 100)) : 0;
                $color   = $pct >= 100 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
            @endphp
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background:#1a5c2e">
                        <span style="color:#f0c040;font-weight:700;font-size:0.9rem">{{ $act->nombre }}</span>
                        <span class="badge bg-{{ $color }}">{{ $pct }}%</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase">Cuota/alumno</div>
                                <div class="fw-bold" style="color:#1a5c2e;font-size:1.1rem">S/ {{ number_format($act->cuota,2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase">Meta total</div>
                                <div class="fw-bold" style="font-size:1.1rem">S/ {{ number_format($meta,2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase">Cobrado</div>
                                <div class="fw-bold text-success">S/ {{ number_format($cobrado,2) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase">Pendiente</div>
                                <div class="fw-bold text-danger">S/ {{ number_format($meta - $cobrado,2) }}</div>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height:8px">
                            <div class="progress-bar bg-{{ $color }}" style="width:{{ $pct }}%"></div>
                        </div>
                        @if($act->fecha_limite)
                            <div class="text-muted" style="font-size:0.75rem">
                                <i class="fas fa-calendar me-1"></i>
                                Vence: {{ \Carbon\Carbon::parse($act->fecha_limite)->format('d/m/Y') }}
                            </div>
                        @endif
                        @if($act->descripcion)
                            <div class="text-muted mt-1" style="font-size:0.78rem">{{ $act->descripcion }}</div>
                        @endif
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('actividades.edit', $act) }}" class="btn btn-sm btn-warning flex-fill">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form method="POST" action="{{ route('actividades.destroy', $act) }}"
                              onsubmit="return confirm('¿Desactivar esta actividad?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="fas fa-calendar-alt fa-3x mb-3 d-block opacity-25"></i>
                        No hay actividades registradas.
                        <a href="{{ route('actividades.create') }}" style="color:#1a5c2e">Crear la primera</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
