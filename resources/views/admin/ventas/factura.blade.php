@extends('layouts.app')

@section('title', 'Factura de Venta')

@section('content')
<div class="container">
    <h2>Factura de Venta - #{{ $venta->id }}</h2>

    <table class="table">
        <tr>
            <th>Cliente:</th>
            <td>{{ $venta->cliente->nombre }}</td>
        </tr>
        <tr>
            <th>Mesa:</th>
            <td>{{ $venta->mesa->numero ?? 'Sin asignar' }}</td>
        </tr>
        <tr>
            <th>Fecha:</th>
            <td>{{ $venta->fecha }}</td>
        </tr>
    </table>

    <h3>Detalles de la Venta</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalleVenta as $detalle)
            <tr>
                <td>{{ $detalle->plato->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>S/. {{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>S/. {{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: S/. {{ number_format($venta->total, 2) }}</h3>
</div>
@endsection
