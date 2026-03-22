@extends('layouts.app')
@section('title', isset($alumno) ? 'Editar Alumno' : 'Nuevo Alumno')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">
            {{ isset($alumno) ? '✏️ Editar: '.$alumno->nombre_completo : '+ Nuevo Alumno' }}
        </h4>
        <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="max-width:720px">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-user-graduate me-2"></i>Datos del alumno
            </h6>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($alumno) ? route('alumnos.update', $alumno) : route('alumnos.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($alumno)) @method('PUT') @endif

                {{-- Foto --}}
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-center gap-4 p-3"
                         style="background:#e8f5ec;border-radius:10px;border:1px dashed #2d8a48">
                        <div id="foto-preview">
                            @if(isset($alumno) && $alumno->foto)
                                <img src="{{ asset($alumno->foto) }}" alt="Foto"
                                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #1a5c2e">
                            @else
                                <div style="width:80px;height:80px;border-radius:50%;background:#c8e6c9;border:3px solid #1a5c2e;display:flex;align-items:center;justify-content:center;font-size:2rem">🎒</div>
                            @endif
                        </div>
                        <div style="flex:1">
                            <label class="form-label fw-bold text-muted mb-1" style="font-size:0.78rem;text-transform:uppercase">
                                Fotografía del alumno
                            </label>
                            <input type="file" name="foto" class="form-control" accept="image/*"
                                   style="font-size:0.82rem" onchange="previewFoto(this)">
                            <div class="text-muted mt-1" style="font-size:0.72rem">
                                JPG o PNG · Máximo 2MB
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Apellidos *</label>
                        <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror"
                               value="{{ old('apellidos', $alumno->apellidos ?? '') }}"
                               placeholder="Ej: García López" required>
                        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Nombres *</label>
                        <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror"
                               value="{{ old('nombres', $alumno->nombres ?? '') }}"
                               placeholder="Ej: Juan Carlos" required>
                        @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">DNI</label>
                        <input type="text" name="dni" class="form-control"
                               value="{{ old('dni', $alumno->dni ?? '') }}" placeholder="12345678" maxlength="8">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Sección</label>
                        <select name="seccion" class="form-select">
                            @foreach(['A','B','C','D'] as $s)
                                <option {{ old('seccion', $alumno->seccion ?? 'C') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="my-3">
                <p class="fw-bold text-muted mb-3" style="font-size:0.78rem;text-transform:uppercase">
                    <i class="fas fa-user me-1"></i> Datos del apoderado
                </p>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Nombre del apoderado *</label>
                        <input type="text" name="apoderado" class="form-control @error('apoderado') is-invalid @enderror"
                               value="{{ old('apoderado', $alumno->apoderado ?? '') }}"
                               placeholder="Ej: María García de López" required>
                        @error('apoderado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Celular</label>
                        <input type="text" name="celular" class="form-control"
                               value="{{ old('celular', $alumno->celular ?? '') }}" placeholder="987 654 321">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Parentesco</label>
                        <select name="parentesco" class="form-select">
                            @foreach(['Madre','Padre','Abuela/o','Tía/o','Otro'] as $p)
                                <option {{ old('parentesco', $alumno->parentesco ?? 'Madre') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                                  placeholder="Notas adicionales...">{{ old('observaciones', $alumno->observaciones ?? '') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($alumno) ? 'Guardar cambios' : 'Registrar alumno' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #1a5c2e">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
