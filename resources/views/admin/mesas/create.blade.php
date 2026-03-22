@extends('layouts.app')

@section('title', 'Nueva Mesa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Registrar Nueva Mesa</h2>
    <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.mesas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="numero" class="form-label fw-bold">Número de Mesa</label>
                <input type="number" name="numero" id="numero" class="form-control" value="{{ old('numero') }}" required>
                @error('numero')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label fw-bold">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="libre" {{ old('estado') == 'libre' ? 'selected' : '' }}>Libre</option>
                    <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ old('estado') == 'reservada' ? 'selected' : '' }}>Reservada</option>
                </select>
                @error('estado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacidad" class="form-label fw-bold">Capacidad</label>
                <input type="number" name="capacidad" id="capacidad" class="form-control" value="{{ old('capacidad') }}" placeholder="Ej: 4">
                @error('capacidad')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar Mesa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
