@extends('layouts.app')
@section('title', 'Portal de Padres')

@section('content')
<div class="container-fluid">

    {{-- Banner --}}
    <div style="background:linear-gradient(135deg,#1a5c2e,#2d8a48);border-radius:14px;padding:20px 24px;margin-bottom:24px;border:1px solid rgba(200,153,26,0.3)">
        <div class="d-flex align-items-center gap-3">
            <div style="width:54px;height:54px;border-radius:50%;background:rgba(200,153,26,0.2);border:2px solid #c8991a;display:flex;align-items:center;justify-content:center;font-size:1.4rem">
                👨‍👩‍👧
            </div>
            <div>
                <div style="font-family:'Merriweather Sans',sans-serif;font-size:1rem;font-weight:800;color:#f0c040">
                    Bienvenido/a, {{ auth()->user()->name }}
                </div>
                <div style="font-size:0.82rem;color:rgba(255,255,255,0.7)">
                    Portal de padres · IE Sor Annetta de Jesús
                </div>
            </div>
        </div>
    </div>

    {{-- Info del alumno --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100" style="border-left:4px solid #1a5c2e">
                <div class="card-body text-center py-4">
                    <div style="width:60px;height:60px;border-radius:50%;background:#e8f5ec;border:3px solid #1a5c2e;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 12px">
                        🎒
                    </div>
                    <div class="fw-bold" style="font-size:1rem;color:#1a5c2e">{{ $alumno->nombre_completo }}</div>
                    <div class="text-muted" style="font-size:0.82rem">4to "{{ $alumno->seccion ?? '' }}"</div>
                    <hr>
                    <div class="d-flex justify-content-between" style="font-size:0.85rem">
                        <span class="text-muted">DNI</span>
                        <span class="fw-bold">{{ $alumno->dni ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1" style="font-size:0.85rem">
                        <span class="text-muted">Apoderado</span>
                        <span class="fw-bold">{{ $alumno->apoderado }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100" style="border-left:4px solid {{ $deudaTotal > 0 ? '#c0392b' : '#2d8a48' }}">
                <div class="card-body text-center py-4">
                    <div style="font-size:2.5rem;margin-bottom:8px">
                        {{ $deudaTotal > 0 ? '⚠️' : '✅' }}
                    </div>
                    <div class="fw-bold" style="font-size:1.5rem;color:{{ $deudaTotal > 0 ? '#c0392b' : '#1a5c2e' }}">
                        S/ {{ number_format($deudaTotal, 2) }}
                    </div>
                    <div class="text-muted" style="font-size:0.82rem">
                        {{ $deudaTotal > 0 ? 'Deuda pendiente' : 'Todo al día ✓' }}
                    </div>
                    @if($deudaTotal > 0)
                        <div class="mt-3 p-2" style="background:#fdf6e0;border-radius:8px;font-size:0.78rem;color:#7a5a00">
                            Por favor acercarse a cancelar sus cuotas pendientes
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100" style="border-left:4px solid #c8991a">
                <div class="card-body text-center py-4">
                    <div style="font-size:2rem;margin-bottom:8px">📅</div>
                    <div class="fw-bold" style="font-size:1.5rem;color:#7a5a00">
                        {{ $estadoPagos->where('pagado', true)->count() }}/{{ $estadoPagos->count() }}
                    </div>
                    <div class="text-muted" style="font-size:0.82rem">Cuotas pagadas</div>
                    <div class="progress mt-3" style="height:8px">
                        @php $pct = $estadoPagos->count() > 0 ? round($estadoPagos->where('pagado',true)->count() / $estadoPagos->count() * 100) : 0; @endphp
                        <div class="progress-bar" style="width:{{ $pct }}%;background:#c8991a"></div>
                    </div>
                    <div class="text-muted mt-1" style="font-size:0.72rem">{{ $pct }}% completado</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Estado de pagos por actividad --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">
                        <i class="fas fa-list-check me-2"></i>Estado de pagos por actividad
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($estadoPagos as $ep)
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                        <div>
                            <div class="fw-bold" style="font-size:0.9rem">{{ $ep->actividad->nombre }}</div>
                            <div class="text-muted" style="font-size:0.75rem">
                                Cuota: S/ {{ number_format($ep->actividad->cuota, 2) }}
                                @if($ep->fecha)
                                    · Pagado el {{ \Carbon\Carbon::parse($ep->fecha)->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>
                        @if($ep->pagado)
                            <span class="badge bg-success" style="font-size:0.82rem">✓ Pagado</span>
                        @else
                            <span class="badge bg-danger" style="font-size:0.82rem">Pendiente</span>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar fa-2x mb-2 d-block opacity-25"></i>
                        Sin actividades registradas
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Próximas reuniones --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header" style="background:#1a5c2e">
                    <h6 class="mb-0" style="color:#f0c040">
                        <i class="fas fa-handshake me-2"></i>Próximas reuniones
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($reuniones as $reunion)
                    @php $proxima = !\Carbon\Carbon::parse($reunion->fecha)->isPast(); @endphp
                    <div class="px-3 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold" style="font-size:0.88rem">{{ $reunion->tema }}</div>
                                <div class="text-muted" style="font-size:0.75rem">
                                    📅 {{ \Carbon\Carbon::parse($reunion->fecha)->format('d/m/Y') }}
                                    · 🕐 {{ $reunion->hora }}
                                </div>
                                @if($reunion->lugar)
                                    <div class="text-muted" style="font-size:0.75rem">
                                        📍 {{ $reunion->lugar }}
                                    </div>
                                @endif
                            </div>
                            <span class="badge {{ $proxima ? 'bg-warning text-dark' : 'bg-secondary' }}" style="font-size:0.7rem">
                                {{ $proxima ? 'Próxima' : 'Realizada' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-handshake fa-2x mb-2 d-block opacity-25"></i>
                        Sin reuniones programadas
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
