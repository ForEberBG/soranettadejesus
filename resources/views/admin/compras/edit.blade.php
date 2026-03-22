@extends('layouts.app')

@section('title', 'Editar Compra')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Compra</h2>
    <a href="{{ route('admin.compras.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.compras.update', $compra) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Formulario para la compra -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="proveedor_id" class="form-label fw-bold">Proveedor</label>
                    <select name="proveedor_id" id="proveedor_id" class="form-select">
                        <option value="">Seleccionar proveedor</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ $compra->proveedor_id == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="usuario_id" class="form-label fw-bold">Usuario</label>
                    <input type="text" name="usuario_id" id="usuario_id" class="form-control" value="{{ $compra->usuario_id }}" readonly>
                    @error('usuario_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label fw-bold">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', $compra->fecha) }}" required>
                    @error('fecha')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Tabla para editar ingredientes (Detalle Compra) -->
            <div class="table-responsive mb-3">
                <table class="table table-striped" id="tabla-ingredientes">
                    <thead class="table-dark">
                        <tr>
                            <th>Ingrediente</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalleCompra as $detalle)
                            <tr class="ingrediente-row">
                                <td>
                                    <select name="ingrediente_id[]" class="form-select ingrediente-select" required>
                                        <option value="">Seleccionar ingrediente</option>
                                        @foreach($ingredientes as $ingrediente)
                                            <option value="{{ $ingrediente->id }}" 
                                                    {{ $ingrediente->id == $detalle->ingrediente_id ? 'selected' : '' }}
                                                    data-precio="{{ $ingrediente->precio }}">
                                                {{ $ingrediente->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control cantidad" value="{{ $detalle->cantidad }}" min="1" required></td>
                                <td><input type="number" name="precio_unitario[]" class="form-control precio" value="{{ $detalle->precio_unitario }}" step="0.01" readonly></td>
                                <td><input type="number" name="subtotal[]" class="form-control subtotal" value="{{ $detalle->subtotal }}" step="0.01" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-sm eliminar-ingrediente">Eliminar</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" id="agregarIngrediente">
                    <i class="fas fa-plus me-2"></i> Agregar Ingrediente
                </button>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar Compra
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agregarIngredienteBtn = document.getElementById('agregarIngrediente');
        const tablaIngredientes = document.querySelector('#tabla-ingredientes tbody');

        // Función para actualizar el subtotal de un ingrediente
        function actualizarSubtotal(index) {
            const cantidad = parseFloat(document.querySelectorAll('.cantidad')[index].value) || 0;
            const precio = parseFloat(document.querySelectorAll('.precio')[index].value) || 0;
            const subtotal = cantidad * precio;
            document.querySelectorAll('.subtotal')[index].value = subtotal.toFixed(2);
        }

        // Agregar una nueva fila de ingredientes
        agregarIngredienteBtn.addEventListener('click', function() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="ingrediente_id[]" class="form-select ingrediente-select" required>
                        <option value="">Seleccione un ingrediente</option>
                        @foreach($ingredientes as $ingrediente)
                            <option value="{{ $ingrediente->id }}" data-precio="{{ $ingrediente->precio }}">{{ $ingrediente->nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="cantidad[]" class="form-control cantidad" value="1" min="1" required></td>
                <td><input type="number" name="precio_unitario[]" class="form-control precio" value="0" step="0.01" readonly></td>
                <td><input type="number" name="subtotal[]" class="form-control subtotal" value="0" step="0.01" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-ingrediente">Eliminar</button></td>
            `;

            tablaIngredientes.appendChild(row);

            // Asignamos el evento de cambio para la selección del ingrediente
            const nuevoSelect = row.querySelector('.ingrediente-select');
            const nuevoInputCantidad = row.querySelector('.cantidad');
            const nuevoInputPrecio = row.querySelector('.precio');
            const nuevoInputSubtotal = row.querySelector('.subtotal');

            nuevoSelect.addEventListener('change', function() {
                const precio = parseFloat(this.options[this.selectedIndex].getAttribute('data-precio')) || 0;
                nuevoInputPrecio.value = precio.toFixed(2);
                actualizarSubtotal(tablaIngredientes.children.length - 1);
            });

            // Asignamos el evento de input para la cantidad
            nuevoInputCantidad.addEventListener('input', function() {
                actualizarSubtotal(tablaIngredientes.children.length - 1);
            });
        });

        // Eliminar una fila de ingrediente
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('eliminar-ingrediente')) {
                e.target.closest('tr').remove();
            }
        });

        // Asignamos eventos para las filas ya existentes (en caso de haber ingredientes precargados)
        document.querySelectorAll('.ingrediente-select').forEach((select, index) => {
            select.addEventListener('change', function() {
                const precio = parseFloat(this.options[this.selectedIndex].getAttribute('data-precio')) || 0;
                document.querySelectorAll('.precio')[index].value = precio.toFixed(2);
                actualizarSubtotal(index);
            });
        });

        document.querySelectorAll('.cantidad').forEach((input, index) => {
            input.addEventListener('input', function() {
                actualizarSubtotal(index);
            });
        });
    });
</script>

@endsection
