@extends('layouts.app')

@section('title', 'Registrar Pedido')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Registrar Nuevo Pedido</h2>
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.pedidos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="venta_id" class="form-label fw-bold">Venta</label>
                <select name="venta_id" id="venta_id" class="form-select" required>
                    <option value="">Seleccione una venta</option>
                    @foreach($ventas as $venta)
                        <option value="{{ $venta->id }}" {{ old('venta_id') == $venta->id ? 'selected' : '' }}>
                            Venta #{{ $venta->id }} - {{ $venta->cliente->nombre ?? 'No asignado' }}
                        </option>
                    @endforeach
                </select>
                @error('venta_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label fw-bold">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en preparacion" {{ old('estado') == 'en preparacion' ? 'selected' : '' }}>En preparación</option>
                    <option value="listo" {{ old('estado') == 'listo' ? 'selected' : '' }}>Listo</option>
                    <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                </select>
                @error('estado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nota" class="form-label fw-bold">Nota (opcional)</label>
                <textarea name="nota" id="nota" class="form-control">{{ old('nota') }}</textarea>
                @error('nota')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Registrar Pedido
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
