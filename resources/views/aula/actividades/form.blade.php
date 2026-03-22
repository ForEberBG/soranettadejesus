@extends('layouts.app')
@section('title', isset($actividad) ? 'Editar Actividad' : 'Nueva Actividad')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">
            {{ isset($actividad) ? '✏️ Editar Actividad' : '+ Nueva Actividad' }}
        </h4>
        <a href="{{ route('actividades.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card" style="max-width:560px">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-calendar-alt me-2"></i>Datos de la actividad
            </h6>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($actividad) ? route('actividades.update', $actividad) : route('actividades.store') }}">
                @csrf
                @if(isset($actividad)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Nombre de la actividad *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $actividad->nombre ?? '') }}"
                           placeholder="Ej: Día del logro, Cuota Abril..." required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Cuota por alumno (S/) *</label>
                        <input type="number" name="cuota" class="form-control @error('cuota') is-invalid @enderror"
                               step="0.01" min="0"
                               value="{{ old('cuota', $actividad->cuota ?? '') }}"
                               placeholder="0.00" required>
                        @error('cuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Fecha límite</label>
                        <input type="date" name="fecha_limite" class="form-control"
                               value="{{ old('fecha_limite', isset($actividad) ? \Carbon\Carbon::parse($actividad->fecha_limite)->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"
                              placeholder="Descripción opcional...">{{ old('descripcion', $actividad->descripcion ?? '') }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('actividades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($actividad) ? 'Guardar cambios' : 'Crear actividad' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
