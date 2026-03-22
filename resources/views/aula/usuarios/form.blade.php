@extends('layouts.app')
@section('title', isset($user) ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
@if($errors->any())
<div class="alert alert-danger mb-3">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color:#1a5c2e">
            {{ isset($user) ? '✏️ Editar: '.$user->name : '+ Nuevo Usuario' }}
        </h4>
        <a href="{{ route('usuarios.aula') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card" style="max-width:600px">
        <div class="card-header" style="background:#1a5c2e">
            <h6 class="mb-0" style="color:#f0c040">
                <i class="fas fa-user me-2"></i>Datos del usuario
            </h6>
        </div>
        <div class="card-body">
            <form method="POST"
                action="{{ isset($user) ? route('usuarios.aula.update', $user) : route('usuarios.aula.store') }}">
                @csrf
                @if(isset($user)) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Nombre completo *
                    </label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name ?? '') }}" placeholder="Ej: María García" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Correo electrónico *
                    </label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email ?? '') }}" placeholder="correo@ejemplo.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                            Contraseña {{ isset($user) ? '(dejar vacío para no cambiar)' : '*' }}
                        </label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Mínimo 6 caracteres" {{ isset($user) ? '' : 'required' }}>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                            Confirmar contraseña
                        </label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repetir contraseña">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Rol *
                    </label>
                    <select name="rol" id="sel-rol" class="form-select" required onchange="toggleAlumno()">
                        <option value="administrador" {{ old('rol', $user->rol ?? '') === 'administrador' ? 'selected' :
                            '' }}>
                            👨‍💼 Administrador — acceso total
                        </option>
                        <option value="docente" {{ old('rol', $user->rol ?? '') === 'docente' ? 'selected' : '' }}>
                            👩‍🏫 Docente — ve todo, no elimina
                        </option>
                        <option value="tesorero" {{ old('rol', $user->rol ?? '') === 'tesorero' ? 'selected' : '' }}>
                            💰 Tesorero — cuotas, gastos y reportes
                        </option>
                        <option value="padre" {{ old('rol', $user->rol ?? '') === 'padre' ? 'selected' : '' }}>
                            👨‍👩‍👧 Padre de familia — portal de pagos
                        </option>
                    </select>
                </div>

                {{-- Solo visible si es Padre --}}
                <div class="mb-3" id="div-alumno"
                    style="display:{{ old('rol', $user->rol ?? '') === 'padre' ? 'block' : 'none' }}">
                    <label class="form-label fw-bold text-muted" style="font-size:0.78rem;text-transform:uppercase">
                        Alumno vinculado
                    </label>
                    <select name="alumno_id" class="form-select">
                        <option value="">— Seleccionar alumno —</option>
                        @foreach($alumnos as $al)
                        <option value="{{ $al->id }}" {{ old('alumno_id', $user->alumno_id ?? '') == $al->id ?
                            'selected' : '' }}>
                            {{ $al->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                    <div class="text-muted mt-1" style="font-size:0.72rem">
                        El padre podrá ver el estado de pagos de este alumno
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="hidden" name="activo" value="0">
                        <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" {{
                            old('activo', $user->activo ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="activo" style="font-size:0.85rem">
                            Usuario activo
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('usuarios.aula') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($user) ? 'Guardar cambios' : 'Crear usuario' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleAlumno() {
    var rol = document.getElementById('sel-rol').value;
    document.getElementById('div-alumno').style.display = rol === 'padre' ? 'block' : 'none';
}
</script>
@endpush
