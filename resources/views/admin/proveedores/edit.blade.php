@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Proveedor</h2>
    <a href="{{ route('admin.proveedores.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.proveedores.update', $proveedor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $proveedor->nombre) }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}">
                @error('telefono')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}">
                @error('direccion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar Proveedor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
