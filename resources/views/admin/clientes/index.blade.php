@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Clientes</h2>
    <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nuevo Cliente
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="miTabla" class="table table-striped table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $index => $cliente)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->documento ?? '-' }}</td>
                    <td>{{ $cliente->telefono ?? '-' }}</td>
                    <td>{{ $cliente->direccion ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.clientes.destroy', $cliente) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
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
@endsection

@push('scripts')
<!-- DataTables y SweetAlert -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#miTabla').DataTable({
            pageLength: 5,
            language: {
                lengthMenu: "Mostra _MENU_ clientes",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                infoEmpty: "Mostrando 0 a 0 de 0 clientes",
                infoFiltered: "(filtrado de _MAX_ clientes en total)",
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
