@extends('layouts.app')

@section('title', 'Editar Mesa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Mesa #{{ $mesa->numero }}</h2>
    <a href="{{ route('admin.mesas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.mesas.update', $mesa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="numero" class="form-label fw-bold">Número de Mesa</label>
                <input type="number" name="numero" id="numero" class="form-control" value="{{ old('numero', $mesa->numero) }}" required>
                @error('numero')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label fw-bold">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="libre" {{ old('estado', $mesa->estado) == 'libre' ? 'selected' : '' }}>Libre</option>
                    <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ old('estado', $mesa->estado) == 'reservada' ? 'selected' : '' }}>Reservada</option>
                </select>
                @error('estado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacidad" class="form-label fw-bold">Capacidad</label>
                <input type="number" name="capacidad" id="capacidad" class="form-control" value="{{ old('capacidad', $mesa->capacidad) }}">
                @error('capacidad')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Mesa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
