@extends('layouts.app')

@section('title', 'Pedidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Pedidos</h2>
    <a href="{{ route('admin.pedidos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Pedido
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="tablaPedidos" class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Venta ID</th>
                    <th>Detalle</th>
                    <th>Estado</th>
                    <th>Nota</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $index => $pedido)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pedido->venta_id }}</td>
                    <td>
                        {{-- ← Sin data-bs-toggle, usamos onclick manual --}}
                        <button class="btn btn-sm btn-primary"
                            onclick="abrirModal('detalleModal{{ $pedido->id }}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                    <td>
                        <span class="badge bg-{{ $pedido->estado === 'pendiente' ? 'warning' : ($pedido->estado === 'preparando' ? 'primary' : ($pedido->estado === 'listo' ? 'info' : 'success')) }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </td>
                    <td>{{ $pedido->nota ?? 'No hay nota' }}</td>
                    <td>
                        <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.pedidos.destroy', $pedido) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirmarEliminar(event)">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modales fuera de la tabla --}}
@foreach ($pedidos as $pedido)
<div class="modal" id="detalleModal{{ $pedido->id }}" tabindex="-1" style="display:none">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de la Venta #{{ $pedido->venta_id }}</h5>
                <button type="button" class="btn-close" style="filter:invert(1)"
                    onclick="cerrarModal()"></button>
            </div>
            <div class="modal-body" style="max-height:70vh;overflow-y:auto">
                @if($pedido->venta && $pedido->venta->detalleVenta->count())
                <table class="table table-sm">
                    <thead>
                        <tr><th>Plato</th><th>Cantidad</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pedido->venta->detalleVenta as $detalle)
                        <tr>
                            <td>{{ $detalle->plato->nombre ?? 'Sin nombre' }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center text-muted">No hay detalles disponibles.</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('css')
<style>
    .modal-backdrop { display: none !important; }
    .modal.show {
        background: rgba(0,0,0,0.5);
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#tablaPedidos').DataTable({
            pageLength: 10,
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
        });
    });

    function abrirModal(id) {
        cerrarModal();
        $('#' + id).css({ 'display': 'flex', 'z-index': '9999' }).addClass('show');
        $('body').addClass('modal-open');
    }

    function cerrarModal() {
        $('.modal').css('display', 'none').removeClass('show');
        $('body').removeClass('modal-open').removeAttr('style');
        $('.modal-backdrop').remove();
    }

    $(document).on('keydown', function(e) { if (e.key === 'Escape') cerrarModal(); });

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
            if (result.isConfirmed) e.target.submit();
        });
    }
</script>
@endpush
