@extends('layouts.app')

@section('title', 'Mesas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Mesas</h2>
    <a href="{{ route('admin.mesas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nueva Mesa
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="tablaMesas" class="table table-striped table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mesas as $index => $mesa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mesa->numero }}</td>
                    <td><span class="badge bg-{{ $mesa->estado === 'ocupada' ? 'danger' : ($mesa->estado === 'reservada' ? 'warning' : 'success') }}">
                        {{ ucfirst($mesa->estado) }}</span>
                    </td>
                    <td>{{ $mesa->capacidad ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.mesas.edit', $mesa) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.mesas.destroy', $mesa) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#tablaMesas').DataTable({
            pageLength: 5,
            language: {
                lengthMenu: "Mostrar _MENU_ mesas",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ mesas",
                infoEmpty: "Mostrando 0 a 0 de 0 mesas",
                infoFiltered: "(filtrado de _MAX_ mesas en total)",
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
</script>

<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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
