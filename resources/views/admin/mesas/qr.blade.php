@extends('layouts.app')
@section('title', 'QR Mesas - Porto Azul')
@section('content')

<style>
    .qr-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
        transition: transform 0.2s;
    }
    .qr-card:hover { transform: translateY(-4px); }
    .qr-header {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
        color: white;
        padding: 12px;
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .qr-body { padding: 20px; }
    .qr-url {
        font-size: 0.7rem;
        color: #aaa;
        margin-top: 8px;
        word-break: break-all;
    }
    .btn-print {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 10px;
        width: 100%;
        font-family: 'Nunito', sans-serif;
    }
    @media print {
        .no-print { display: none !important; }
        .qr-card { break-inside: avoid; box-shadow: none; border: 1px solid #ddd; }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h4 style="font-family:'Playfair Display',serif;color:#5BC8D4;margin:0">
        📱 Códigos QR por Mesa
    </h4>
    <button onclick="window.print()"
        style="background:linear-gradient(135deg,#C0392B,#96281B);color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer">
        🖨️ Imprimir Todos
    </button>
</div>

<div class="row g-4">
    @foreach($mesas as $mesa)
    @php
        $url   = url('/carta/mesa/' . $mesa->id);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($url);
    @endphp
    <div class="col-6 col-md-4 col-lg-3">
        <div class="qr-card">
            <div class="qr-header">Mesa {{ $mesa->numero }}</div>
            <div class="qr-body">
                <div id="qr-svg-{{ $mesa->id }}">
                    <img src="{{ $qrUrl }}" width="160" height="160" alt="QR Mesa {{ $mesa->numero }}">
                </div>
                <div class="qr-url">{{ $url }}</div>
                <a href="{{ $url }}" target="_blank"
                    style="display:block;margin-top:8px;font-size:0.78rem;color:#5BC8D4">
                    🔗 Ver carta
                </a>
                <button class="btn-print no-print"
                    data-mesa="Mesa {{ $mesa->numero }}"
                    data-svg-id="qr-svg-{{ $mesa->id }}"
                    onclick="imprimirQR(this)">
                    🖨️ Imprimir este
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
function imprimirQR(btn) {
    const mesaNombre = btn.getAttribute('data-mesa');
    const svgId      = btn.getAttribute('data-svg-id');
    const imgSrc     = document.getElementById(svgId).querySelector('img').src;

    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>QR ${mesaNombre}</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 40px; }
                h2 { color: #1A2E5A; font-size: 1.5rem; margin-bottom: 4px; }
                h3 { color: #C0392B; font-size: 1.2rem; margin-bottom: 16px; }
                p { color: #888; font-size: 0.85rem; margin-top: 12px; }
            </style>
        </head>
        <body>
            <h2>Porto Azul</h2>
            <h3>${mesaNombre}</h3>
            <img src="${imgSrc}" style="width:220px;height:220px">
            <p>Escanea para ver nuestra carta y hacer tu pedido</p>
        </body>
        </html>
    `);
    win.document.close();
    setTimeout(() => win.print(), 500);
}
</script>

@endsection
