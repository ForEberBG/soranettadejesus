@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Categoría: {{ $categoria->nombre }}</h2>
    <a href="{{ route('admin.categorias_plato.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.categorias_plato.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $categoria->nombre) }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
