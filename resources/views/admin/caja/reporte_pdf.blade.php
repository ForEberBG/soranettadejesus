<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Caja</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; padding: 20px; color: #000; }
        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        .linea  { border-top: 1px dashed #000; margin: 6px 0; }
        .linea-doble { border-top: 2px solid #000; margin: 6px 0; }
        h2 { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin: 6px 0; }
        th { background: #1A2E5A; color: white; padding: 5px 8px; font-size: 10px; text-align: left; }
        td { padding: 4px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        .stat-row td { padding: 6px 8px; }
        .total-row td { font-weight: bold; font-size: 12px; background: #f5f5f5; }
    </style>
</head>
<body>
@php $config = App\Models\Configuracion::first(); @endphp

<div class="center bold">{{ $config->nombre ?? 'Porto Azul' }}</div>
<div class="center">RUC: {{ env('SUNAT_RUC') }}</div>
<div class="center">{{ $config->direccion ?? '' }}</div>
<div class="linea"></div>

<div class="center bold" style="font-size:13px">REPORTE DE CIERRE DE CAJA</div>
<div class="center">Caja #{{ $caja->id }}</div>
<div class="linea"></div>

<table class="stat-row">
    <tr><td class="bold">Responsable:</td><td>{{ $caja->usuario->name }}</td></tr>
    <tr><td class="bold">Apertura:</td><td>{{ $caja->apertura_at->format('d/m/Y H:i:s') }}</td></tr>
    <tr><td class="bold">Cierre:</td><td>{{ $caja->cierre_at?->format('d/m/Y H:i:s') ?? 'En curso' }}</td></tr>
    <tr><td class="bold">Monto inicial:</td><td>S/ {{ number_format($caja->monto_inicial, 2) }}</td></tr>
    @if($caja->observaciones)
    <tr><td class="bold">Observaciones:</td><td>{{ $caja->observaciones }}</td></tr>
    @endif
</table>

<div class="linea"></div>
<div class="bold" style="margin-bottom:4px">RESUMEN DE VENTAS</div>

<table>
    <tr>
        <td>💵 Efectivo</td>
        <td class="right">S/ {{ number_format($caja->total_efectivo, 2) }}</td>
    </tr>
    <tr>
        <td>📱 Yape</td>
        <td class="right">S/ {{ number_format($caja->total_yape, 2) }}</td>
    </tr>
    <tr>
        <td>📱 Plin</td>
        <td class="right">S/ {{ number_format($caja->total_plin, 2) }}</td>
    </tr>
    <tr>
        <td>💳 Tarjeta</td>
        <td class="right">S/ {{ number_format($caja->total_tarjeta, 2) }}</td>
    </tr>
    <tr class="total-row">
        <td>TOTAL VENDIDO ({{ $caja->num_ventas }} ventas)</td>
        <td class="right">S/ {{ number_format($caja->total_ventas, 2) }}</td>
    </tr>
    <tr class="total-row">
        <td>TOTAL EN CAJA</td>
        <td class="right">S/ {{ number_format($caja->monto_inicial + $caja->total_efectivo, 2) }}</td>
    </tr>
</table>

<div class="linea"></div>
<div class="bold" style="margin-bottom:4px">DETALLE DE VENTAS</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Método</th>
            <th>Comprobante</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->cliente->nombre ?? 'Consumidor' }}</td>
            <td>{{ ucfirst($v->metodo_pago) }}</td>
            <td>
                @if($v->tipo_comprobante == 'factura') F
                @elseif($v->tipo_comprobante == 'boleta') B
                @else NV @endif
                -{{ $v->correlativo ?? $v->id }}
            </td>
            <td class="right">S/ {{ number_format($v->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="linea-doble"></div>
<div class="center" style="font-size:10px;color:#666">
    Generado: {{ now()->format('d/m/Y H:i:s') }} — {{ $config->nombre ?? 'Porto Azul' }}
</div>
</body>
</html>
