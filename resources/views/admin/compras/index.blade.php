@extends('layouts.app')

@section('title', 'Listado de Compras')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Compras</h2>
    <a href="{{ route('admin.compras.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nueva Compra
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="miTabla" class="table table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compras as $index => $compra)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $compra->proveedor->nombre }}</td>
                        <td>S/. {{ number_format($compra->total, 2) }}</td>
                        <td>{{ $compra->fecha }}</td>
                        <td>
                            <a href="{{ route('admin.compras.edit', $compra) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.compras.destroy', $compra) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <!-- Botón para abrir el modal de detalles de la compra -->
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalleCompraModal{{ $compra->id }}">
                                Ver detalles
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para mostrar los detalles de la compra -->
@foreach ($compras as $compra)
<div class="modal fade" id="detalleCompraModal{{ $compra->id }}" tabindex="-1" aria-labelledby="detalleCompraModalLabel{{ $compra->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleCompraModalLabel{{ $compra->id }}">Detalles de la Compra #{{ $compra->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Ingrediente</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compra->detalleCompra as $detalle)
                            <tr>
                                <td>{{ $detalle->ingrediente->nombre }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td>{{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td class="text-center"><strong>{{ number_format($compra->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
@push('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#miTabla').DataTable({
            pageLength: 5,
            language: {
                lengthMenu: "Mostra _MENU_ compras",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ compras",
                infoEmpty: "Mostrando 0 a 0 de 0 compras",
                infoFiltered: "(filtrado de _MAX_ compras en total)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                loadingRecords: "Cargando...",
                processing: "Procesando..."
            }
        });
    });
    
    function confirmarEliminar(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    }
</script>
@endpush