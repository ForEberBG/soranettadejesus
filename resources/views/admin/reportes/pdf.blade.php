<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 20px; color: #000; }
        .center { text-align: center; }
        .right  { text-align: right; }
        .bold   { font-weight: bold; }
        h2 { font-size: 14px; margin-bottom: 4px; }
        .linea { border-top: 1px solid #ccc; margin: 8px 0; }
        .linea-doble { border-top: 2px solid #000; margin: 8px 0; }

        .stats { display: flex; gap: 10px; margin: 10px 0; }
        .stat-box {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        .stat-valor { font-size: 13px; font-weight: bold; color: #1A2E5A; }
        .stat-label { font-size: 9px; color: #888; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        th {
            background: #1A2E5A;
            color: white;
            padding: 6px 8px;
            font-size: 10px;
            text-align: left;
        }
        td { padding: 5px 8px; border-bottom: 1px solid #f0f0f0; font-size: 10px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        tfoot td { font-weight: bold; background: #f0f0f0; font-size: 11px; }

        .titulo-seccion {
            background: #f0f0f0;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            margin: 12px 0 6px;
            border-left: 4px solid #1A2E5A;
        }
    </style>
</head>
<body>
@php $config = App\Models\Configuracion::first(); @endphp

<div class="center">
    <h2>{{ $config->nombre ?? 'Porto Azul' }}</h2>
    <div>RUC: {{ env('SUNAT_RUC') }} | {{ $config->direccion ?? '' }}</div>
</div>
<div class="linea"></div>

<div class="center bold" style="font-size:13px">REPORTE DE VENTAS</div>
<div class="center">Del {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</div>
<div class="linea"></div>

{{-- RESUMEN --}}
<div class="titulo-seccion">RESUMEN</div>
<table>
    <tr>
        <td class="bold">Total de ventas:</td><td>{{ $ventas->count() }}</td>
        <td class="bold">Total general:</td><td class="bold" style="color:#C0392B">S/ {{ number_format($totalGeneral, 2) }}</td>
    </tr>
    <tr>
        <td class="bold">Efectivo:</td><td>S/ {{ number_format($totalEfectivo, 2) }}</td>
        <td class="bold">Yape:</td><td>S/ {{ number_format($totalYape, 2) }}</td>
    </tr>
    <tr>
        <td class="bold">Plin:</td><td>S/ {{ number_format($totalPlin, 2) }}</td>
        <td class="bold">Tarjeta:</td><td>S/ {{ number_format($totalTarjeta, 2) }}</td>
    </tr>
</table>

{{-- PLATOS MÁS VENDIDOS --}}
<div class="titulo-seccion">PLATOS MÁS VENDIDOS</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Plato</th>
            <th>Cantidad</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($platosVendidos as $i => $plato)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $plato['nombre'] }}</td>
            <td>{{ $plato['cantidad'] }} unid.</td>
            <td class="right">S/ {{ number_format($plato['total'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- DETALLE --}}
<div class="titulo-seccion">DETALLE DE VENTAS</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Comprobante</th>
            <th>Método</th>
            <th>Mozo</th>
            <th class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $v)
        <tr>
            <td>{{ $v->id }}</td>
            <td>{{ $v->created_at->format('d/m H:i') }}</td>
            <td>{{ $v->cliente->nombre ?? 'Consumidor' }}</td>
            <td>
                @if($v->tipo_comprobante == 'factura') F
                @elseif($v->tipo_comprobante == 'boleta') B
                @else NV @endif
                -{{ $v->correlativo ?? $v->id }}
            </td>
            <td>{{ ucfirst($v->metodo_pago) }}</td>
            <td>{{ $v->usuario->name ?? '—' }}</td>
            <td class="right">S/ {{ number_format($v->total, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" class="right">TOTAL:</td>
            <td class="right">S/ {{ number_format($totalGeneral, 2) }}</td>
        </tr>
    </tfoot>
</table>

<div class="linea"></div>
<div class="center" style="font-size:9px;color:#888">
    Generado: {{ now()->format('d/m/Y H:i:s') }} — {{ $config->nombre ?? 'Porto Azul' }}
</div>
</body>
</html>
