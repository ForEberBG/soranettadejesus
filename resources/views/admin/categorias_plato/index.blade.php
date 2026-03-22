@extends('layouts.app')
@section('title', 'Categorías de Platos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-white">Categorías Registradas</h2>
    <a href="{{ route('admin.categorias_plato.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Nueva Categoría
    </a>
</div>

<div class="card border-0 shadow-sm rounded">
    <div class="card-body table-responsive">
        <table id="miTabla" class="table table-striped table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nro</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $index => $categoria)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.categorias_plato.edit', $categoria) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categorias_plato.destroy', $categoria) }}" method="POST" class="d-inline-block" onsubmit="return confirmarEliminar(event)">
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                lengthMenu: "Mostra _MENU_ categorias",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ categorias",
                infoEmpty: "Mostrando 0 a 0 de 0 categorias",
                infoFiltered: "(filtrado de _MAX_ categorias en total)",
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
