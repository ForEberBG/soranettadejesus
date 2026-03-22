<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 10mm 12mm 10mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #1a5c2e;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-logo {
            display: table-cell;
            width: 65px;
            vertical-align: middle;
        }
        .header-logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }
        .header-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }
        .header-colegio {
            font-size: 13px;
            font-weight: bold;
            color: #1a5c2e;
        }
        .header-sub {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }
        .header-right {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
            width: 180px;
        }
        .header-aula {
            font-size: 11px;
            font-weight: bold;
            color: #c8991a;
        }
        .header-fecha {
            font-size: 9px;
            color: #888;
            margin-top: 2px;
        }

        .titulo-reporte {
            background: #1a5c2e;
            color: #f0c040;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 14px;
            border-radius: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th {
            background: #1a5c2e;
            color: #f0c040;
            padding: 6px 7px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        td {
            padding: 5px 7px;
            border-bottom: 1px solid #dde8df;
        }
        tr:nth-child(even) td { background: #f5f7f5; }
        tr:last-child td { border-bottom: none; }

        .badge-green {
            background: #e8f5ec;
            color: #1a5c2e;
            padding: 2px 5px;
            border-radius: 8px;
            font-size: 9px;
        }
        .badge-red {
            background: #fdf0ef;
            color: #c0392b;
            padding: 2px 5px;
            border-radius: 8px;
            font-size: 9px;
        }
        .badge-gold {
            background: #fdf6e0;
            color: #7a5a00;
            padding: 2px 5px;
            border-radius: 8px;
            font-size: 9px;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold     { font-weight: bold; }
        .text-green  { color: #1a5c2e; }
        .text-red    { color: #c0392b; }
        .text-gold   { color: #c8991a; }

        .total-row td {
            background: #e8f5ec;
            font-weight: bold;
            border-top: 2px solid #1a5c2e;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #dde8df;
            padding: 5px 0;
            text-align: center;
            font-size: 8px;
            color: #aaa;
        }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<div class="header">
    <div class="header-logo">
        @if($config && $config->logo && file_exists(public_path($config->logo)))
            @php
                $logoPath = public_path($config->logo);
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMime = mime_content_type($logoPath);
            @endphp
            <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo">
        @endif
    </div>
    <div class="header-info">
        <div class="header-colegio">{{ $config->nombre ?? 'IE Sor Annetta de Jesús' }}</div>
        <div class="header-sub">{{ $config->direccion ?? 'Pucallpa, Ucayali' }}</div>
        <div class="header-sub">
            {{ $config->telefono ?? '' }}
            {{ $config->email ? '· '.$config->email : '' }}
        </div>
    </div>
    <div class="header-right">
        <div class="header-aula">
            Aula: {{ $config->aula ?? '' }} · {{ $config->anio_escolar ?? date('Y') }}
        </div>
        @if($config && $config->docente)
            <div class="header-fecha">Docente: {{ $config->docente }}</div>
        @endif
        <div class="header-fecha">Generado: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>
