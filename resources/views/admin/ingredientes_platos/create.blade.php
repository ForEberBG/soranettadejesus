@extends('layouts.app')

@section('title', 'Registrar Ingrediente para Plato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Asignar Ingredientes al Plato</h2>
    <a href="{{ route('admin.ingredientes_platos.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.ingredientes_platos.store') }}" method="POST">
            @csrf
            <!-- Formulario de selección de plato -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="plato_id" class="form-label fw-bold">Plato</label>
                    <select name="plato_id" id="plato_id" class="form-select" required>
                        <option value="">Seleccionar plato</option>
                        @foreach($platos as $plato)
                            <option value="{{ $plato->id }}" {{ old('plato_id') == $plato->id ? 'selected' : '' }}>{{ $plato->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Tabla de ingredientes -->
            <div class="table-responsive mb-3">
                <table class="table table-striped" id="tabla-ingredientes">
                    <thead class="table-dark">
                        <tr>
                            <th>Ingrediente</th>
                            <th>Cantidad Usada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="ingrediente-row">
                            <td>
                                <select name="ingrediente_id[]" class="form-select ingrediente-select" required>
                                    <option value="">Seleccionar ingrediente</option>
                                    @foreach($ingredientes as $ingrediente)
                                        <option value="{{ $ingrediente->id }}" data-precio="{{ $ingrediente->precio }}">{{ $ingrediente->nombre }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="cantidad_usada[]" class="form-control cantidad-usada" value="1" min="1" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm eliminar-ingrediente">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" id="agregarIngrediente">
                    <i class="fas fa-plus me-2"></i> Agregar Ingrediente
                </button>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Registrar Ingredientes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agregarIngredienteBtn = document.getElementById('agregarIngrediente');
        const tablaIngredientes = document.querySelector('#tabla-ingredientes tbody');

        // Función para agregar una nueva fila de ingredientes
        agregarIngredienteBtn.addEventListener('click', function() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="ingrediente_id[]" class="form-select ingrediente-select" required>
                        <option value="">Seleccionar ingrediente</option>
                        @foreach($ingredientes as $ingrediente)
                            <option value="{{ $ingrediente->id }}" data-precio="{{ $ingrediente->precio }}">{{ $ingrediente->nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="cantidad_usada[]" class="form-control cantidad-usada" value="1" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-ingrediente">Eliminar</button>
                </td>
            `;
            tablaIngredientes.appendChild(row);
        });

        // Eliminar una fila de ingrediente
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('eliminar-ingrediente')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>

@endsection
