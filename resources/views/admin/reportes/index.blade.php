@extends('layouts.app')
@section('title', 'Reportes - Porto Azul')
@section('content')

<style>
    .reporte-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .reporte-header {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
        color: white;
        padding: 14px 20px;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        border-top: 4px solid #ddd;
    }

    .stat-box .valor {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: #1A2E5A;
    }

    .stat-box .label {
        font-size: 0.75rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    .stat-box.total {
        border-top-color: #C0392B;
    }

    .stat-box.efectivo {
        border-top-color: #198754;
    }

    .stat-box.yape {
        border-top-color: #7B2D8B;
    }

    .stat-box.plin {
        border-top-color: #00A0E3;
    }

    .stat-box.tarjeta {
        border-top-color: #ffc107;
    }

    .stat-box.ventas {
        border-top-color: #5BC8D4;
    }

    .btn-reporte {
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        font-family: 'Nunito', sans-serif;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-pdf {
        background: #C0392B;
        color: white;
    }

    .btn-excel {
        background: #198754;
        color: white;
    }

    .btn-filtrar {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
        color: white;
    }

    .tabla-ventas th {
        background: #1A2E5A;
        color: white;
        font-size: 0.8rem;
        padding: 10px 12px;
        font-weight: 700;
    }

    .tabla-ventas td {
        padding: 10px 12px;
        font-size: 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .tabla-ventas tr:hover td {
        background: #f8f9fa;
    }

    .badge-metodo {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-efectivo {
        background: #d4edda;
        color: #155724;
    }

    .badge-yape {
        background: #e8d5f0;
        color: #7B2D8B;
    }

    .badge-plin {
        background: #d0eaf8;
        color: #00A0E3;
    }

    .badge-tarjeta {
        background: #fff3cd;
        color: #856404;
    }

    .plato-rank {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .plato-rank:last-child {
        border-bottom: none;
    }

    .plato-nro {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #1A2E5A;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .plato-nro.gold {
        background: #ffc107;
        color: #000;
    }

    .plato-nro.silver {
        background: #adb5bd;
        color: #000;
    }

    .plato-nro.bronze {
        background: #cd7f32;
        color: white;
    }

    .plato-bar {
        height: 6px;
        background: #5BC8D4;
        border-radius: 3px;
        margin-top: 4px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-family:'Playfair Display',serif;color:#5BC8D4;margin:0">
        📊 Reportes de Ventas
    </h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reportes.pdf', request()->all()) }}" class="btn-reporte btn-pdf">
            📄 PDF
        </a>
        <a href="{{ route('admin.reportes.excel', request()->all()) }}" class="btn-reporte btn-excel">
            📊 Excel
        </a>
    </div>
</div>

{{-- FILTROS --}}
<div class="reporte-card">
    <div class="reporte-header">🔍 Filtros</div>
    <div class="p-4">
        <form method="GET" action="{{ route('admin.reportes.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label style="font-weight:700;font-size:0.85rem;color:#1A2E5A;display:block;margin-bottom:6px">📅
                        Desde</label>
                    <input type="date" name="desde" value="{{ $desde }}" class="form-control"
                        style="border:2px solid #e9ecef;border-radius:8px">
                </div>
                <div class="col-md-3">
                    <label style="font-weight:700;font-size:0.85rem;color:#1A2E5A;display:block;margin-bottom:6px">📅
                        Hasta</label>
                    <input type="date" name="hasta" value="{{ $hasta }}" class="form-control"
                        style="border:2px solid #e9ecef;border-radius:8px">
                </div>
                <div class="col-md-3">
                    <label style="font-weight:700;font-size:0.85rem;color:#1A2E5A;display:block;margin-bottom:6px">👤
                        Mozo</label>
                    <select name="mozo_id" class="form-control" style="border:2px solid #e9ecef;border-radius:8px">
                        <option value="">Todos</option>
                        @foreach($mozos as $m)
                        <option value="{{ $m->id }}" {{ $mozo_id==$m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-reporte btn-filtrar w-100" style="padding:10px">
                        🔍 Filtrar
                    </button>
                </div>
            </div>
            {{-- Accesos rápidos --}}
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.reportes.index', ['desde' => now()->toDateString(), 'hasta' => now()->toDateString()]) }}"
                    class="btn-reporte" style="background:#e9ecef;color:#555;padding:5px 12px;font-size:0.78rem">
                    Hoy
                </a>
                <a href="{{ route('admin.reportes.index', ['desde' => now()->startOfWeek()->toDateString(), 'hasta' => now()->toDateString()]) }}"
                    class="btn-reporte" style="background:#e9ecef;color:#555;padding:5px 12px;font-size:0.78rem">
                    Esta semana
                </a>
                <a href="{{ route('admin.reportes.index', ['desde' => now()->startOfMonth()->toDateString(), 'hasta' => now()->toDateString()]) }}"
                    class="btn-reporte" style="background:#e9ecef;color:#555;padding:5px 12px;font-size:0.78rem">
                    Este mes
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ESTADÍSTICAS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="stat-box ventas">
            <div class="valor">{{ $ventas->count() }}</div>
            <div class="label">Ventas</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-box total">
            <div class="valor" style="color:#C0392B">S/ {{ number_format($totalGeneral, 2) }}</div>
            <div class="label">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-box efectivo">
            <div class="valor" style="color:#198754">S/ {{ number_format($totalEfectivo, 2) }}</div>
            <div class="label">💵 Efectivo</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-box yape">
            <div class="valor" style="color:#7B2D8B">S/ {{ number_format($totalYape, 2) }}</div>
            <div class="label">📱 Yape</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-box plin">
            <div class="valor" style="color:#00A0E3">S/ {{ number_format($totalPlin, 2) }}</div>
            <div class="label">📱 Plin</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-box tarjeta">
            <div class="valor" style="color:#856404">S/ {{ number_format($totalTarjeta, 2) }}</div>
            <div class="label">💳 Tarjeta</div>
        </div>
    </div>
</div>
{{-- UTILIDAD --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-box" style="border-top:4px solid #198754">
            <div class="valor" style="color:#198754">S/ {{ number_format($totalGeneral, 2) }}</div>
            <div class="label">💰 Total Ventas</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box" style="border-top:4px solid #C0392B">
            <div class="valor" style="color:#C0392B">S/ {{ number_format($comprasTotal, 2) }}</div>
            <div class="label">🛒 Total Compras</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box" style="border-top:4px solid {{ $utilidad >= 0 ? '#198754' : '#C0392B' }}">
            <div class="valor" style="color:{{ $utilidad >= 0 ? '#198754' : '#C0392B' }}">
                S/ {{ number_format($utilidad, 2) }}
            </div>
            <div class="label">📈 Utilidad Bruta</div>
        </div>
    </div>
</div>

{{-- ALERTAS STOCK BAJO --}}
@if($stockBajo->count())
<div class="reporte-card mb-4">
    <div class="reporte-header" style="background:linear-gradient(135deg,#C0392B,#96281B)">
        <span>⚠️ Ingredientes con Stock Bajo</span>
        <span style="font-size:0.8rem;font-weight:400">{{ $stockBajo->count() }} ingredientes</span>
    </div>
    <div class="p-3">
        <div class="row g-2">
            @foreach($stockBajo as $ing)
            <div class="col-md-3">
                <div
                    style="background:#fff5f5;border:1px solid #f5c6cb;border-radius:10px;padding:12px;text-align:center">
                    <div style="font-weight:800;color:#C0392B;font-size:1.1rem">{{ $ing->stock }} {{ $ing->unidad }}
                    </div>
                    <div style="font-size:0.82rem;color:#1A2E5A;font-weight:700">{{ $ing->nombre }}</div>
                    <div style="font-size:0.72rem;color:#aaa">Mínimo: {{ $ing->stock_minimo }} {{ $ing->unidad }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<div class="row g-4">
    {{-- TABLA DE VENTAS --}}
    <div class="col-lg-8">
        <div class="reporte-card">
            <div class="reporte-header">
                <span>🧾 Detalle de Ventas</span>
                <span style="font-size:0.8rem;font-weight:400">{{ $desde }} al {{ $hasta }}</span>
            </div>
            <div style="overflow-x:auto">
                <table class="tabla-ventas w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Comprobante</th>
                            <th>Método</th>
                            <th>Mozo</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $v)
                        <tr>
                            <td style="color:#888;font-size:0.78rem">{{ $v->id }}</td>
                            <td style="font-size:0.8rem">{{ $v->created_at->format('d/m H:i') }}</td>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem">{{ $v->cliente->nombre ?? 'Consumidor' }}
                                </div>
                                <div style="font-size:0.72rem;color:#aaa">{{ $v->cliente->documento ?? '' }}</div>
                            </td>
                            <td style="font-size:0.78rem">
                                @if($v->tipo_comprobante == 'factura') 🧾 F
                                @elseif($v->tipo_comprobante == 'boleta') 🧾 B
                                @else 📝 NV @endif
                                -{{ $v->correlativo ?? $v->id }}
                            </td>
                            <td>
                                @if($v->pagos->count() > 1)
                                @foreach($v->pagos as $pago)
                                <span class="badge-metodo badge-{{ $pago->metodo }}"
                                    style="font-size:0.7rem;display:block;margin-bottom:2px">
                                    {{ ucfirst($pago->metodo) }} S/{{ number_format($pago->monto,2) }}
                                </span>
                                @endforeach
                                @else
                                <span class="badge-metodo badge-{{ $v->metodo_pago }}">
                                    {{ ucfirst($v->metodo_pago) }}
                                </span>
                                @endif
                            </td>
                            <td style="font-size:0.82rem">{{ $v->usuario->name ?? '—' }}</td>
                            <td class="text-end" style="font-weight:700;color:#1A2E5A">
                                S/ {{ number_format($v->total, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-5" style="color:#aaa">
                                No hay ventas en este período
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($ventas->count())
                    <tfoot>
                        <tr style="background:#f8f9fa">
                            <td colspan="6" style="font-weight:800;padding:12px;color:#1A2E5A">TOTAL</td>
                            <td class="text-end" style="font-weight:800;color:#C0392B;padding:12px;font-size:1rem">
                                S/ {{ number_format($totalGeneral, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- PLATOS MÁS VENDIDOS --}}
    <div class="col-lg-4">
        <div class="reporte-card">
            <div class="reporte-header">🏆 Platos más vendidos</div>
            <div class="p-4">
                @forelse($platosVendidos as $i => $plato)
                @php
                $maxCant = $platosVendidos->first()['cantidad'] ?? 1;
                $pct = round(($plato['cantidad'] / $maxCant) * 100);
                $nroCls = $i == 0 ? 'gold' : ($i == 1 ? 'silver' : ($i == 2 ? 'bronze' : ''));
                @endphp
                <div class="plato-rank">
                    <div class="plato-nro {{ $nroCls }}">{{ $i + 1 }}</div>
                    <div style="flex:1">
                        <div style="font-weight:700;font-size:0.88rem;color:#1A2E5A">{{ $plato['nombre'] }}</div>
                        <div class="plato-bar" style="width:{{ $pct }}%"></div>
                        <div style="font-size:0.75rem;color:#888;margin-top:2px">
                            {{ $plato['cantidad'] }} unidades · S/ {{ number_format($plato['total'], 2) }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-3">Sin datos</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
