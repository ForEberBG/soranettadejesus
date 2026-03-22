@extends('layouts.app')

@section('title', 'Editar Plato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Plato: {{ $plato->nombre }}</h2>
    <a href="{{ route('admin.platos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.platos.update', $plato) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $plato->nombre) }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bold">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $plato->descripcion) }}</textarea>
                @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="precio" class="form-label fw-bold">Precio (S/)</label>
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{ old('precio', $plato->precio) }}" required>
                    @error('precio')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="categoria_plato_id" class="form-label fw-bold">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $plato->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_plato_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label fw-bold">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="disponible" {{ old('estado', $plato->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="no disponible" {{ old('estado', $plato->estado) == 'no disponible' ? 'selected' : '' }}>No disponible</option>
                </select>
                @error('estado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label fw-bold">Imagen (opcional)</label>
                <input type="file" name="imagen" id="imagen" class="form-control">
                @if ($plato->imagen)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $plato->imagen) }}" alt="Imagen actual" width="80" height="80" class="rounded shadow-sm">
                    </div>
                @endif
                @error('imagen')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Plato
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
