@extends('layouts.app')

@section('title', 'Editar Venta')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Editar Venta</h2>
    <a href="{{ route('admin.ventas.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver al listado
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body">
        <form action="{{ route('admin.ventas.update', $venta->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Formulario para la venta -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mesa_id" class="form-label fw-bold">Mesa</label>
                    <select name="mesa_id" id="mesa_id" class="form-select">
                        <option value="">Sin asignar</option>
                        @foreach($mesas as $mesa)
                        <option value="{{ $mesa->id }}" {{ old('mesa_id', $venta->mesa_id) == $mesa->id ? 'selected' :
                            '' }}>
                            Mesa {{ $mesa->numero }} (Capacidad: {{ $mesa->capacidad }})
                        </option>
                        @endforeach
                    </select>
                    @error('mesa_id')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cliente_id" class="form-label fw-bold">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-select">
                        <option value="">Sin asignar</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id', $venta->cliente_id) == $cliente->id ?
                            'selected' : '' }}>
                            {{ $cliente->nombre }} - {{ $cliente->documento }}
                        </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label fw-bold">Tipo de Venta</label>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="mesa" {{ old('tipo', $venta->tipo) == 'mesa' ? 'selected' : '' }}>Mesa</option>
                        <option value="llevar" {{ old('tipo', $venta->tipo) == 'llevar' ? 'selected' : '' }}>Para llevar
                        </option>
                        <option value="delivery" {{ old('tipo', $venta->tipo) == 'delivery' ? 'selected' : ''
                            }}>Delivery</option>
                    </select>
                    @error('tipo')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="metodo_pago" class="form-label fw-bold">Método de Pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                        <option value="efectivo" {{ old('metodo_pago', $venta->metodo_pago) == 'efectivo' ? 'selected' :
                            '' }}>💵 Efectivo</option>
                        <option value="yape" {{ old('metodo_pago', $venta->metodo_pago) == 'yape' ? 'selected' : ''
                            }}>📱 Yape</option>
                        <option value="plin" {{ old('metodo_pago', $venta->metodo_pago) == 'plin' ? 'selected' : ''
                            }}>📱 Plin</option>
                        <option value="tarjeta" {{ old('metodo_pago', $venta->metodo_pago) == 'tarjeta' ? 'selected' :
                            '' }}>💳 Tarjeta</option>
                        <option value="qr" {{ old('metodo_pago', $venta->metodo_pago) == 'qr' ? 'selected' : '' }}>📷 QR
                        </option>
                    </select>
                    @error('metodo_pago')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label fw-bold">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="pendiente" {{ old('estado', $venta->estado) == 'pendiente' ? 'selected' : ''
                            }}>Pendiente</option>
                        <option value="pagado" {{ old('estado', $venta->estado) == 'pagado' ? 'selected' : '' }}>Pagado
                        </option>
                    </select>
                    @error('estado')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label fw-bold">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control"
                        value="{{ old('fecha', $venta->fecha) }}" required>
                    @error('fecha')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Tabla para agregar productos (Detalle Venta) -->
            <div class="table-responsive mb-3">
                <table class="table table-striped" id="tabla-productos">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalleVenta as $detalle)
                        <tr class="producto-row">
                            <td>
                                <select name="plato_id[]" class="form-select producto-select" required>
                                    <option value="">Seleccionar plato</option>
                                    @foreach($platos as $plato)
                                    <option value="{{ $plato->id }}" {{ $detalle->plato_id == $plato->id ? 'selected' :
                                        '' }} data-precio="{{ $plato->precio }}">
                                        {{ $plato->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="cantidad[]" class="form-control cantidad"
                                    value="{{ $detalle->cantidad }}" min="1" required></td>
                            <td><input type="number" name="precio_unitario[]" class="form-control precio"
                                    value="{{ $detalle->precio_unitario }}" step="0.01" readonly></td>
                            <td><input type="number" name="subtotal[]" class="form-control subtotal"
                                    value="{{ $detalle->subtotal }}" step="0.01" readonly></td>
                            <td><button type="button" class="btn btn-danger btn-sm eliminar-producto">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" id="agregarProducto">
                    <i class="fas fa-plus me-2"></i> Agregar Producto
                </button>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Actualizar Venta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productosSelect = document.querySelectorAll('.producto-select');
        const cantidadInputs = document.querySelectorAll('.cantidad');
        const precioInputs = document.querySelectorAll('.precio');
        const subtotalInputs = document.querySelectorAll('.subtotal');
        const agregarProductoBtn = document.getElementById('agregarProducto');

        // Al cambiar la selección del producto, actualizamos el precio unitario
        productosSelect.forEach((select, index) => {
            select.addEventListener('change', function() {
                const precio = parseFloat(this.options[this.selectedIndex].getAttribute('data-precio')) || 0;
                precioInputs[index].value = precio.toFixed(2);
                actualizarSubtotal(index);
            });
        });

        // Al cambiar la cantidad, actualizamos el subtotal
        cantidadInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                actualizarSubtotal(index);
            });
        });

        // Función para actualizar el subtotal de un producto
        function actualizarSubtotal(index) {
            const cantidad = parseFloat(cantidadInputs[index].value) || 0;
            const precio = parseFloat(precioInputs[index].value) || 0;
            const subtotal = cantidad * precio;
            subtotalInputs[index].value = subtotal.toFixed(2);
        }

        // Agregar una nueva fila de productos
        agregarProductoBtn.addEventListener('click', function() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="plato_id[]" class="form-select producto-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($platos as $plato)
                            <option value="{{ $plato->id }}" data-precio="{{ $plato->precio }}">{{ $plato->nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="cantidad[]" class="form-control cantidad" value="1" min="1" required></td>
                <td><input type="number" name="precio_unitario[]" class="form-control precio" value="0" step="0.01" readonly></td>
                <td><input type="number" name="subtotal[]" class="form-control subtotal" value="0" step="0.01" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm eliminar-producto">Eliminar</button></td>
            `;
            document.querySelector('#tabla-productos tbody').appendChild(row);
            // Actualizamos el evento de cambio de producto y cantidad
            const nuevoSelect = row.querySelector('.producto-select');
            const nuevoInputCantidad = row.querySelector('.cantidad');
            const nuevoInputPrecio = row.querySelector('.precio');
            const nuevoInputSubtotal = row.querySelector('.subtotal');
            nuevoSelect.addEventListener('change', function() {
                const precio = parseFloat(this.options[this.selectedIndex].getAttribute('data-precio')) || 0;
                nuevoInputPrecio.value = precio.toFixed(2);
                actualizarSubtotal(index);
            });
            nuevoInputCantidad.addEventListener('input', function() {
                actualizarSubtotal(index);
            });
        });

        // Eliminar una fila de producto
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('eliminar-producto')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>

@endsection
