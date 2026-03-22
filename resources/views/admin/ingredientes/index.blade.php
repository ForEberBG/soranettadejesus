@extends('layouts.app')

@section('title', 'Listado de Ingredientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Ingredientes</h2>
    <a href="{{ route('admin.ingredientes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nuevo Ingrediente
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="tablaIngredientes" class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Stock Mínimo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ingredientes as $index => $ingrediente)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ingrediente->nombre }}</td>
                    <td>{{ $ingrediente->unidad }}</td>
                    <td>{{ $ingrediente->stock }}</td>
                    <td>{{ $ingrediente->stock_minimo }}</td>
                    <td>
                        <a href="{{ route('admin.ingredientes.edit', $ingrediente) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.ingredientes.destroy', $ingrediente) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
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
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#tablaIngredientes').DataTable({
            pageLength: 5,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
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
