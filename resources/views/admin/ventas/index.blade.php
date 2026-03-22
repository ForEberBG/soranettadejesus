@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Ventas</h2>
    <a href="{{ route('admin.ventas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nueva Venta
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="tablaVentas" class="table table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mesa</th>
                    <th>Cliente</th>
                    <th>Tipo de Venta</th>
                    <th>Platos</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Estado SUNAT</th>
                    <th style="text-align:center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $index => $venta)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $venta->mesa ? 'Mesa ' . $venta->mesa->numero : 'No asignada' }}</td>
                    <td>{{ $venta->cliente ? $venta->cliente->nombre : 'No asignado' }}</td>
                    <td>{{ ucfirst($venta->tipo) }}</td>
                    <td>
                        <button class="btn btn-sm btn-dark btn-ver-detalle" data-id="{{ $venta->id }}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                    <td>
                        <span class="badge bg-{{ $venta->estado == 'pagado' ? 'success' : 'warning' }}">
                            {{ ucfirst($venta->estado) }}
                        </span>
                    </td>
                    <td>{{ number_format($venta->total, 2) }} S/</td>
                    <td>{{ $venta->fecha }}</td>
                    <td>
                        <span id="sunat-{{ $venta->id }}" class="badge bg-{{
                            $venta->estado_sunat == 'aceptado' ? 'success' :
                            ($venta->estado_sunat == 'enviado' ? 'primary' : 'warning')
                        }}">
                            {{ ucfirst($venta->estado_sunat ?? 'Pendiente') }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.ventas.edit', $venta) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>

                        @if($venta->tipo_comprobante == 'nota_venta')
                        <a href="{{ route('admin.ventas.nota_venta_pdf', $venta->id) }}" target="_blank"
                            class="btn btn-sm btn-secondary">
                            <i class="fas fa-file-pdf"></i> Nota
                        </a>
                        @elseif($venta->tipo_comprobante == 'boleta')
                        <a href="{{ route('admin.ventas.factura', $venta->id) }}" target="_blank"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf"></i> Boleta
                        </a>
                        @else
                        <a href="{{ route('admin.ventas.factura', $venta->id) }}" target="_blank"
                            class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf"></i> Factura
                        </a>
                        @endif

                        @if($venta->tipo_comprobante != 'nota_venta')
                        <a href="{{ route('admin.ventas.xml', $venta) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-file-code"></i> XML
                        </a>
                        @if($venta->estado_sunat != 'aceptado')
                        <button type="button" class="btn btn-success btn-sm btn-sunat" data-id="{{ $venta->id }}">
                            <i class="fas fa-paper-plane"></i> SUNAT
                        </button>
                        @endif
                        @endif

                        <form action="{{ route('admin.ventas.destroy', $venta) }}" method="POST" class="d-inline-block"
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

{{-- MODALES FUERA DE LA TABLA --}}
@foreach($ventas as $venta)
<div class="modal fade" id="modal-{{ $venta->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de la Venta #{{ $venta->id }}</h5>
                <button type="button" class="btn-close" style="filter:invert(1)" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="max-height:70vh;overflow-y:auto">
                @if($venta->detalleVenta->count())
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Plato</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalleVenta as $detalle)
                        <tr>
                            <td>{{ $detalle->plato->nombre ?? 'Sin nombre' }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>{{ number_format($detalle->precio_unitario, 2) }} S/</td>
                            <td>{{ number_format($detalle->subtotal, 2) }} S/</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-center">No hay detalles disponibles.</p>
                @endif
                {{-- PAGOS --}}
                @if($venta->pagos->count())
                <div class="mt-3 p-2 rounded" style="background:#f8f9fa">
                    <strong>Pagos:</strong>
                    @foreach($venta->pagos as $pago)
                    <span class="badge me-1" style="background:#1A2E5A;font-size:0.85rem">
                        {{ $pago->metodo == 'efectivo' ? '💵' : ($pago->metodo == 'tarjeta' ? '💳' : ($pago->metodo ==
                        'yape' ? '📱' : '📲')) }}
                        {{ strtoupper($pago->metodo) }} S/ {{ number_format($pago->monto, 2) }}
                    </span>
                    @endforeach
                    <span class="ms-2 fw-bold" style="color:#C0392B">Total: S/ {{ number_format($venta->total, 2)
                        }}</span>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@if(session('mensaje'))
<div class="alert alert-success mt-2">{{ session('mensaje') }}</div>
@endif

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
    .modal-backdrop.show {
        opacity: 0.1 !important;
    }
</style>

<script>
    $(document).ready(function() {
    $('#tablaVentas').DataTable({
        pageLength: 10,
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
    });
});

// Abrir modal
$(document).on('click', '.btn-ver-detalle', function() {
    var id = $(this).data('id');
    var el = document.getElementById('modal-' + id);
    el.style.display = 'block';
    el.classList.add('show');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    var bd = document.createElement('div');
    bd.className = 'modal-backdrop fade show';
    bd.id = 'backdrop-activo';
    document.body.appendChild(bd);
});

// Cerrar modal
$(document).on('click', '[data-bs-dismiss="modal"], #backdrop-activo', function() {
    $('.modal.show').each(function() {
        this.style.display = 'none';
        this.classList.remove('show');
    });
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    $('#backdrop-activo').remove();
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
        if (result.isConfirmed) { e.target.submit(); }
    });
}

$(document).on('click', '.btn-sunat', function(e) {
    e.preventDefault();
    let id  = $(this).data('id');
    let btn = $(this);
    fetch(`{{ route('admin.ventas.sunat','ID') }}`.replace('ID', id), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            $('#sunat-' + id).removeClass('bg-warning bg-secondary').addClass('bg-success').text('Aceptado');
            btn.hide();
            Swal.fire('¡Éxito!', resp.mensaje, 'success');
        } else {
            Swal.fire('Error', resp.mensaje || 'Error al enviar a SUNAT', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Error de conexión con Laravel', 'error'));
});
</script>
@endpush
