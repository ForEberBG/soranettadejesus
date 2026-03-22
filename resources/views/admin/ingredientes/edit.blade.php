@extends('layouts.app')

@section('title', 'Editar Ingrediente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Ingrediente</h2>
    <a href="{{ route('admin.ingredientes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.ingredientes.update', $ingrediente) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $ingrediente->nombre) }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="unidad" class="form-label">Unidad</label>
                <input type="text" name="unidad" id="unidad" class="form-control" value="{{ old('unidad', $ingrediente->unidad) }}" required>
                @error('unidad')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $ingrediente->stock) }}" required>
                @error('stock')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="stock_minimo" class="form-label">Stock Mínimo</label>
                <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" value="{{ old('stock_minimo', $ingrediente->stock_minimo) }}" required>
                @error('stock_minimo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <!-- Campo Precio Unitario -->
            <div class="mb-3">
                <label for="precio" class="form-label">Precio Unitario</label>
                <input type="number" name="precio" id="precio" class="form-control" value="{{ old('precio', $ingrediente->precio) }}" step="0.01" required>
                @error('precio')
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
