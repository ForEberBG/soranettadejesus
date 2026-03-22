<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante #{{ $venta->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            width: 72mm;
            margin: 0 auto;
            padding: 4px 8px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .linea {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .linea-doble {
            border-top: 2px solid #000;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 2px;
            vertical-align: top;
        }

        .col-desc {
            width: 45%;
        }

        .col-cant {
            width: 10%;
            text-align: center;
        }

        .col-precio {
            width: 20%;
            text-align: right;
        }

        .col-subtotal {
            width: 25%;
            text-align: right;
        }
    </style>
</head>

<body>
    @php


    $config = App\Models\Configuracion::first();

    if ($venta->tipo_comprobante == 'factura') {
    $tipoLabel = 'FACTURA ELECTRÓNICA';
    $serie = $venta->serie ?? 'F001';
    } else {
    $tipoLabel = 'BOLETA DE VENTA ELECTRÓNICA';
    $serie = $venta->serie ?? 'B001';
    }

    $correlativo = $venta->correlativo ?? str_pad($venta->id, 8, '0', STR_PAD_LEFT);
    $totalBase = $venta->total;
    $totalIgv = 0;
    $doc = $venta->cliente->documento ?? '';


    @endphp

    {{-- LOGO --}}
    @if($config && $config->logo)
    <div class="center">
        <img src="{{ public_path($config->logo) }}" style="max-width:60mm;max-height:20mm;object-fit:contain;">
    </div>
    @endif

    {{-- ENCABEZADO --}}
    <div class="center bold">{{ $config->nombre ?? 'Porto Azul' }}</div>
    <div class="center">RUC: {{ env('SUNAT_RUC') }}</div>
    <div class="center">{{ $config->direccion ?? '' }}</div>
    <div class="center">Tel: {{ $config->telefono ?? '' }}</div>
    <div class="linea"></div>

    {{-- TIPO COMPROBANTE --}}
    <div class="center bold">{{ $tipoLabel }}</div>
    <div class="center bold">{{ $serie }}-{{ $correlativo }}</div>
    <div class="linea"></div>

    {{-- DATOS CLIENTE --}}
    <div><span class="bold">Cliente:</span> {{ $venta->cliente->nombre ?? 'Consumidor Final' }}</div>
    @if($doc)
    <div><span class="bold">Doc:</span> {{ $doc }}</div>
    @endif
    <div><span class="bold">Fecha:</span> {{ $venta->fecha }}</div>
    <div><span class="bold">Mesa:</span> {{ $venta->mesa->numero ?? '-' }}</div>
    <div class="linea"></div>

    {{-- DETALLE --}}
    <table>
        <thead>
            <tr>
                <td class="col-desc bold">Descripción</td>
                <td class="col-cant bold">Cnt</td>
                <td class="col-precio bold">P.U.</td>
                <td class="col-subtotal bold">Sub.</td>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalleVenta as $d)
            <tr>
                <td class="col-desc">{{ $d->plato->nombre }}</td>
                <td class="col-cant">{{ $d->cantidad }}</td>
                <td class="col-precio">{{ number_format($d->precio_unitario, 2) }}</td>
                <td class="col-subtotal">{{ number_format($d->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="linea-doble"></div>

    {{-- TOTALES --}}
    <table>
        <tr>
            <td class="left">OP. EXONERADAS:</td>
            <td class="right">S/ {{ number_format($totalBase, 2) }}</td>
        </tr>
        <tr>
            <td class="left">IGV (0%):</td>
            <td class="right">S/ 0.00</td>
        </tr>
        <tr>
            <td class="left bold">TOTAL A PAGAR:</td>
            <td class="right bold">S/ {{ number_format($venta->total, 2) }}</td>
        </tr>
    </table>
    {{-- PAGOS --}}
    @if($venta->pagos->count() > 1)
    <div class="linea"></div>
    @foreach($venta->pagos as $pago)
    <div style="display:flex;justify-content:space-between">
        <span>{{ ucfirst($pago->metodo) }}:</span>
        <span>S/ {{ number_format($pago->monto, 2) }}</span>
    </div>
    @endforeach
    @endif
    <div class="linea"></div>
    <div class="center">Representación impresa de la</div>
    <div class="center">{{ $tipoLabel }}</div>
    <div class="center">Consulte en: www.sunat.gob.pe</div>
    <div class="linea"></div>
    <div class="center">¡Gracias por su preferencia!</div>
</body>

</html>
