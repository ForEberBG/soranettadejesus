@extends('layouts.app')
@section('title', 'Reuniones')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold" style="color:#1a5c2e">🤝 Reuniones de Padres</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem">Programación, actas y control de asistencia</p>
        </div>
        <a href="{{ route('reuniones.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nueva Reunión
        </a>
    </div>

    @forelse($reuniones as $reunion)
    @php $realizada = \Carbon\Carbon::parse($reunion->fecha)->isPast(); @endphp
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a5c2e">
            <div>
                <span style="color:#f0c040;font-weight:700">🤝 {{ $reunion->tema }}</span>
                <div style="color:rgba(255,255,255,0.7);font-size:0.78rem;margin-top:2px">
                    📅 {{ \Carbon\Carbon::parse($reunion->fecha)->format('d/m/Y') }}
                    &nbsp;·&nbsp; 🕐 {{ $reunion->hora }}
                    &nbsp;·&nbsp; 📍 {{ $reunion->lugar ?? '—' }}
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge {{ $realizada ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $realizada ? 'Realizada' : 'Próxima' }}
                </span>
                <a href="{{ route('reuniones.edit', $reunion) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('pdf.asistencia', $reunion) }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> PDF Asistencia
                </a>
                <form method="POST" action="{{ route('reuniones.destroy', $reunion) }}"
                    onsubmit="return confirm('¿Eliminar esta reunión?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    @if($reunion->notas)
                        <p style="font-size:13px; line-height:1.7">{{ $reunion->notas }}</p>
                    @else
                        <p class="text-muted" style="font-size:0.85rem">Sin acuerdos escritos.</p>
                    @endif

                    @if($reunion->imagen_acta)
                    <div class="mt-2">
                        <div style="font-size:0.75rem;font-weight:700;color:#1a5c2e;margin-bottom:6px">
                            📋 Acta fotográfica:
                        </div>
                        <a href="{{ asset($reunion->imagen_acta) }}" target="_blank">
                            <img src="{{ asset($reunion->imagen_acta) }}" alt="Acta"
                                style="max-height:120px;border-radius:8px;border:2px solid #1a5c2e;cursor:pointer">
                        </a>
                    </div>
                    @endif
                </div>
                <div class="col-md-7">
                    <p class="fw-bold text-muted mb-2" style="font-size:0.75rem;text-transform:uppercase">
                        Asistencia ({{ $reunion->asistencias->where('asistio', true)->count() }}/{{ $totalAlumnos }})
                    </p>
                    <div style="max-height:200px;overflow-y:auto">
                        @foreach($alumnos as $al)
                        @php $asistio = $reunion->asistencias->where('alumno_id', $al->id)->first(); @endphp
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span style="font-size:0.85rem">{{ $al->nombre_completo }}</span>
                            <form method="POST" action="{{ route('reuniones.asistencia', $reunion) }}">
                                @csrf
                                <input type="hidden" name="alumno_id" value="{{ $al->id }}">
                                <input type="hidden" name="asistio" value="{{ $asistio && $asistio->asistio ? 0 : 1 }}">
                                <button type="submit"
                                    class="btn btn-sm {{ $asistio && $asistio->asistio ? 'btn-success' : 'btn-outline-secondary' }}"
                                    style="padding:2px 10px;font-size:0.75rem">
                                    {{ $asistio && $asistio->asistio ? '✓ Asistió' : 'Ausente' }}
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-handshake fa-3x mb-3 d-block opacity-25"></i>
            No hay reuniones registradas.
            <a href="{{ route('reuniones.create') }}" style="color:#1a5c2e">Programar la primera</a>
        </div>
    </div>
    @endforelse

</div>
@endsection
