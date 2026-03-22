@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Cliente: {{ $cliente->nombre }}</h2>
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.clientes.update', $cliente) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required>
                @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                    @error('telefono')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="documento" class="form-label fw-bold">Documento</label>
                    <input type="text" name="documento" id="documento" class="form-control" value="{{ old('documento', $cliente->documento) }}" required>
                    @error('documento')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label fw-bold">Dirección</label>
                <textarea name="direccion" id="direccion" class="form-control" rows="2">{{ old('direccion', $cliente->direccion) }}</textarea>
                @error('direccion')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Actualizar Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
