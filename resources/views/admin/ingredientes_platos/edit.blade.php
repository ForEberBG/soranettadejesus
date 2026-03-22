@extends('layouts.app')

@section('title', 'Editar Ingrediente en Plato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Ingrediente en Plato</h2>
    <a href="{{ route('admin.ingredientes_platos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.ingredientes_platos.update', $ingredientePlato) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="plato_id" class="form-label">Plato</label>
                <select name="plato_id" id="plato_id" class="form-select" required>
                    <option value="">Seleccionar plato</option>
                    @foreach($platos as $plato)
                        <option value="{{ $plato->id }}" {{ old('plato_id', $ingredientePlato->plato_id) == $plato->id ? 'selected' : '' }}>
                            {{ $plato->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('plato_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ingrediente_id" class="form-label">Ingrediente</label>
                <select name="ingrediente_id" id="ingrediente_id" class="form-select" required>
                    <option value="">Seleccionar ingrediente</option>
                    @foreach($ingredientes as $ingrediente)
                        <option value="{{ $ingrediente->id }}" {{ old('ingrediente_id', $ingredientePlato->ingrediente_id) == $ingrediente->id ? 'selected' : '' }}>
                            {{ $ingrediente->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('ingrediente_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cantidad_usada" class="form-label">Cantidad Usada</label>
                <input type="number" name="cantidad_usada" id="cantidad_usada" class="form-control" value="{{ old('cantidad_usada', $ingredientePlato->cantidad_usada) }}" required>
                @error('cantidad_usada')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar Ingrediente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
