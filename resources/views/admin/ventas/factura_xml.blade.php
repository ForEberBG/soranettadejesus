<?xml version="1.0" encoding="UTF-8"?>
<Factura>
    <Id>{{ $venta->id }}</Id>
    <Cliente>{{ $venta->cliente->nombre ?? 'Consumidor Final' }}</Cliente>
    <Fecha>{{ $venta->fecha }}</Fecha>
    <Total>{{ $venta->total }}</Total>
    <Items>
        @foreach($venta->detalleVenta  as $d)
        <Item>
            <Descripcion>{{ $d->plato->nombre }}</Descripcion>
            <Cantidad>{{ $d->cantidad }}</Cantidad>
            <Precio>{{ $d->precio_unitario }}</Precio>
            <Subtotal>{{ $d->subtotal }}</Subtotal>
        </Item>
        @endforeach
    </Items>
</Factura>
