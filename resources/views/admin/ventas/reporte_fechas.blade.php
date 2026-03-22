
@section('title', 'Reporte de Ventas por Fechas')

@section('content')
<div class="container">
    <h2>Reporte de Ventas entre Fechas</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Venta ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>{{ $venta->fecha }}</td>
                <td>S/. {{ number_format($venta->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
