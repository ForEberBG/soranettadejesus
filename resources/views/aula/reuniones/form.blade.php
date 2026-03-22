@extends('layouts.app')
@section('title', isset($reunion) ? 'Editar Reunión' : 'Nueva Reunión')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">
            {{ isset($reunion) ? '✏️ Editar Reunión' : '+ Nueva Reunión' }}
        </h4>
        <a href="{{ route('reuniones.index') }}" class="btn btn-outline-secondary">
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

    <div class="card" style="max-width:700px">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-handshake me-2"></i>Datos de la reunión
            </h6>
        </div>
        <div class="card-body">
            <form method="POST"
                  action="{{ isset($reunion) ? route('reuniones.update', $reunion) : route('reuniones.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($reunion)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Tema de la reunión *
                    </label>
                    <input type="text" name="tema" class="form-control @error('tema') is-invalid @enderror"
                           value="{{ old('tema', $reunion->tema ?? '') }}"
                           placeholder="Ej: Planificación actividad fin de año" required>
                    @error('tema')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Fecha *</label>
                        <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', isset($reunion) ? \Carbon\Carbon::parse($reunion->fecha)->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Hora *</label>
                        <input type="time" name="hora" class="form-control @error('hora') is-invalid @enderror"
                               value="{{ old('hora', $reunion->hora ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">Lugar</label>
                        <input type="text" name="lugar" class="form-control"
                               value="{{ old('lugar', $reunion->lugar ?? '') }}"
                               placeholder="Ej: Aula 4to C">
                    </div>
                </div>

                <hr class="my-3">
                <p class="fw-bold text-muted mb-3" style="font-size:0.78rem;text-transform:uppercase">
                    <i class="fas fa-clipboard me-1"></i> Acuerdos y acta
                </p>

                {{-- Notas escritas --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        ✍️ Acuerdos / Notas escritas
                    </label>
                    <textarea name="notas" class="form-control" rows="4"
                              placeholder="Escribe aquí los acuerdos tomados en la reunión...&#10;1. &#10;2. &#10;3. ">{{ old('notas', $reunion->notas ?? '') }}</textarea>
                    <div class="text-muted mt-1" style="font-size:0.72rem">
                        Puedes escribir los acuerdos directamente aquí
                    </div>
                </div>

                {{-- Imagen del acta --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        📸 Foto del acta escrita (opcional)
                    </label>
                    <div style="background:#e8f5ec;border-radius:10px;border:1px dashed #2d8a48;padding:14px">
                        <div class="d-flex align-items-center gap-3">
                            {{-- Preview actual --}}
                            <div id="acta-preview">
                                @if(isset($reunion) && $reunion->imagen_acta)
                                    <img src="{{ asset($reunion->imagen_acta) }}" alt="Acta"
                                         style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #1a5c2e;cursor:pointer"
                                         onclick="window.open('{{ asset($reunion->imagen_acta) }}','_blank')">
                                @else
                                    <div style="width:80px;height:80px;border-radius:8px;background:#c8e6c9;border:2px solid #1a5c2e;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
                                        📋
                                    </div>
                                @endif
                            </div>
                            <div style="flex:1">
                                <input type="file" name="imagen_acta" class="form-control"
                                       accept="image/*" onchange="previewActa(this)">
                                <div class="text-muted mt-1" style="font-size:0.72rem">
                                    Sube una foto del acta escrita a mano por la secretaria · JPG o PNG · Máx. 5MB
                                </div>
                                @if(isset($reunion) && $reunion->imagen_acta)
                                    <div class="mt-2">
                                        <a href="{{ asset($reunion->imagen_acta) }}" target="_blank"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye me-1"></i> Ver acta actual
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('reuniones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($reunion) ? 'Guardar cambios' : 'Registrar reunión' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewActa(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('acta-preview').innerHTML =
                '<img src="' + e.target.result + '" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #1a5c2e;cursor:pointer" onclick="window.open(this.src,\'_blank\')">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
