@extends('layouts.app')
@section('title', 'Configuración del Aula')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">⚙️ Configuración del Sistema</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem">
            Datos del colegio y del aula — estos datos aparecen en todo el sistema
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4" style="border-radius:10px;border-left:4px solid #2d8a48">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('configuracion.aula.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- Datos del colegio --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background:#1a5c2e">
                        <h6 class="mb-0" style="color:#f0c040">
                            <i class="fas fa-school me-2"></i>Datos del Colegio
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                Nombre del colegio *
                            </label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $config->nombre ?? 'IE Sor Annetta de Jesús') }}"
                                   placeholder="Ej: IE Sor Annetta de Jesús" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                Dirección
                            </label>
                            <input type="text" name="direccion" class="form-control"
                                   value="{{ old('direccion', $config->direccion ?? '') }}"
                                   placeholder="Ej: Jr. Los Robles 123, Pucallpa">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                    Teléfono
                                </label>
                                <input type="text" name="telefono" class="form-control"
                                       value="{{ old('telefono', $config->telefono ?? '') }}"
                                       placeholder="061-123456">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                    Email
                                </label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $config->email ?? '') }}"
                                       placeholder="colegio@ejemplo.com">
                            </div>
                        </div>

                        {{-- Logo --}}
                        <div class="mt-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                Logo del colegio
                            </label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                @if($config && $config->logo)
                                    <img src="{{ asset($config->logo) }}" alt="Logo actual"
                                         style="width:60px;height:60px;object-fit:contain;border:2px solid #c8991a;border-radius:8px;padding:4px">
                                @else
                                    <div style="width:60px;height:60px;border:2px dashed #c8991a;border-radius:8px;display:flex;align-items:center;justify-content:center">
                                        <i class="fas fa-image" style="color:#c8991a;font-size:1.4rem"></i>
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="logo" class="form-control" accept="image/*"
                                           style="font-size:0.82rem">
                                    <div class="text-muted mt-1" style="font-size:0.72rem">
                                        PNG o JPG · Máximo 2MB · Recomendado: 200x200px
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Datos del aula --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background:#1a5c2e">
                        <h6 class="mb-0" style="color:#f0c040">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Datos del Aula
                        </h6>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                Grado y sección *
                            </label>
                            <input type="text" name="aula" class="form-control @error('aula') is-invalid @enderror"
                                   value="{{ old('aula', $config->aula ?? '4to "C"') }}"
                                   placeholder='Ej: 4to "C", 5to "B"' required>
                            <div class="text-muted mt-1" style="font-size:0.72rem">
                                Este dato aparece en el menú lateral y en todos los reportes
                            </div>
                            @error('aula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                Nombre del docente
                            </label>
                            <input type="text" name="docente" class="form-control"
                                   value="{{ old('docente', $config->docente ?? '') }}"
                                   placeholder="Ej: Prof. María García">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                    Año escolar *
                                </label>
                                <select name="anio_escolar" class="form-select" required>
                                    @foreach([2023, 2024, 2025, 2026, 2027] as $anio)
                                        <option value="{{ $anio }}"
                                            {{ old('anio_escolar', $config->anio_escolar ?? date('Y')) == $anio ? 'selected' : '' }}>
                                            {{ $anio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                                    Turno *
                                </label>
                                <select name="turno" class="form-select" required>
                                    @foreach(['Mañana','Tarde','Noche'] as $t)
                                        <option {{ old('turno', $config->turno ?? 'Mañana') === $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Vista previa sidebar --}}
                        <div style="background:#1a5c2e;border-radius:10px;padding:14px;margin-top:8px">
                            <div style="font-size:0.7rem;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.8px">
                                Vista previa en el menú
                            </div>
                            <div style="font-size:0.82rem;font-weight:700;color:#f0c040;margin-top:6px" id="preview-aula">
                                {{ $config->aula ?? '4to "C"' }} · {{ $config->anio_escolar ?? date('Y') }}
                            </div>
                            <div style="font-size:0.72rem;color:rgba(255,255,255,0.5)" id="preview-docente">
                                {{ $config->docente ?? 'Docente' }} — Turno {{ $config->turno ?? 'Mañana' }}
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Botón guardar --}}
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save me-2"></i> Guardar configuración
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
// Vista previa en tiempo real
function actualizarPreview() {
    var aula    = document.querySelector('[name="aula"]').value || '—';
    var anio    = document.querySelector('[name="anio_escolar"]').value || '';
    var docente = document.querySelector('[name="docente"]').value || 'Docente';
    var turno   = document.querySelector('[name="turno"]').value || 'Mañana';
    document.getElementById('preview-aula').textContent    = aula + ' · ' + anio;
    document.getElementById('preview-docente').textContent = docente + ' — Turno ' + turno;
}
document.querySelectorAll('[name="aula"],[name="anio_escolar"],[name="docente"],[name="turno"]')
    .forEach(el => el.addEventListener('input', actualizarPreview));
document.querySelector('[name="anio_escolar"]').addEventListener('change', actualizarPreview);
document.querySelector('[name="turno"]').addEventListener('change', actualizarPreview);
</script>
@endpush
