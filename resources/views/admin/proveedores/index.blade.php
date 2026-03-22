@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Listado de Proveedores</h2>
    <a href="{{ route('admin.proveedores.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nuevo Proveedor
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="tablaProveedores" class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proveedores as $index => $proveedor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->telefono }}</td>
                    <td>{{ $proveedor->direccion }}</td>
                    <td>
                        <a href="{{ route('admin.proveedores.edit', $proveedor) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.proveedores.destroy', $proveedor) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
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

<script>
    $(document).ready(function () {
        $('#tablaProveedores').DataTable({
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
