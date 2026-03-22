@extends('layouts.app')
@section('title', 'Caja - Porto Azul')
@section('content')

<style>
    .caja-card {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .caja-header {
        padding: 18px 24px;
        color: white;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .caja-header.abierta {
        background: linear-gradient(135deg, #198754, #146c43);
    }

    .caja-header.cerrada {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
    }

    .caja-header.nueva {
        background: linear-gradient(135deg, #5BC8D4, #3a9aa5);
    }

    .caja-header.cobros {
        background: linear-gradient(135deg, #C0392B, #96281B);
    }

    .stat-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        border: 1px solid #e9ecef;
    }

    .stat-box .valor {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1A2E5A;
        font-family: 'Playfair Display', serif;
    }

    .stat-box .label {
        font-size: 0.78rem;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    .stat-box.efectivo {
        border-top: 3px solid #198754;
    }

    .stat-box.yape {
        border-top: 3px solid #7B2D8B;
    }

    .stat-box.plin {
        border-top: 3px solid #00A0E3;
    }

    .stat-box.tarjeta {
        border-top: 3px solid #ffc107;
    }

    .stat-box.total {
        border-top: 3px solid #C0392B;
        background: #fff5f5;
    }

    .btn-porto {
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .btn-abrir {
        background: linear-gradient(135deg, #5BC8D4, #3a9aa5);
        color: white;
    }

    .btn-cerrar {
        background: linear-gradient(135deg, #C0392B, #96281B);
        color: white;
    }

    .btn-pdf {
        background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
        color: white;
    }

    .historial-row {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .historial-row:last-child {
        border-bottom: none;
    }

    .cobro-row {
        padding: 10px 16px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }

    .cobro-row:last-child {
        border-bottom: none;
    }

    .cobro-row:hover {
        background: #f8f9fa;
    }

    .badge-metodo {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .metodo-efectivo {
        background: #d1e7dd;
        color: #0a3622;
    }

    .metodo-yape {
        background: #e9d8f4;
        color: #7B2D8B;
    }

    .metodo-plin {
        background: #d0eeff;
        color: #00A0E3;
    }

    .metodo-tarjeta {
        background: #fff3cd;
        color: #856404;
    }

    .pulse-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #5BC8D4;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
        margin-right: 6px;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(1.4);
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 style="font-family:'Playfair Display',serif;color:#5BC8D4;margin:0">
        🏦 Gestión de Caja
    </h4>
    @if($cajaAbierta)
    <span style="background:#198754;color:white;border-radius:20px;padding:6px 16px;font-size:0.85rem;font-weight:700">
        🟢 Caja Abierta desde {{ $cajaAbierta->apertura_at->format('H:i') }}
    </span>
    @else
    <span style="background:#C0392B;color:white;border-radius:20px;padding:6px 16px;font-size:0.85rem;font-weight:700">
        🔴 Caja Cerrada
    </span>
    @endif
</div>

<div class="row g-4">

    {{-- PANEL PRINCIPAL --}}
    <div class="col-lg-7">

        @if($cajaAbierta)
        {{-- CAJA ABIERTA --}}
        <div class="caja-card mb-4">
            <div class="caja-header abierta">
                <span>✅ Caja en Curso</span>
                <span style="font-size:0.85rem;font-weight:400">
                    Abierta por {{ $cajaAbierta->usuario->name }} — {{ $cajaAbierta->apertura_at->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="stat-box efectivo">
                            <div class="valor">S/ {{ number_format($cajaAbierta->monto_inicial, 2) }}</div>
                            <div class="label">Monto Inicial</div>
                        </div>
                    </div>
                    @php
                    $ventasActuales = App\Models\Venta::where('estado','pagado')
                    ->where('created_at','>=',$cajaAbierta->apertura_at)->get();
                    $totEfectivo = $ventasActuales->where('metodo_pago','efectivo')->sum('total');
                    $totYape = $ventasActuales->where('metodo_pago','yape')->sum('total');
                    $totPlin = $ventasActuales->where('metodo_pago','plin')->sum('total');
                    $totTarjeta = $ventasActuales->where('metodo_pago','tarjeta')->sum('total');
                    $totGeneral = $ventasActuales->sum('total');
                    $numVentas = $ventasActuales->count();
                    @endphp
                    <div class="col-6 col-md-3">
                        <div class="stat-box">
                            <div class="valor">{{ $numVentas }}</div>
                            <div class="label">Ventas</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-box total">
                            <div class="valor" style="color:#C0392B">S/ {{ number_format($totGeneral, 2) }}</div>
                            <div class="label">Total Día</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-box efectivo">
                            <div class="valor" style="color:#198754">S/ {{ number_format($cajaAbierta->monto_inicial +
                                $totEfectivo, 2) }}</div>
                            <div class="label">En Caja</div>
                        </div>
                    </div>
                </div>

                <h6 style="color:#1A2E5A;font-weight:700;margin-bottom:12px">Desglose por método de pago:</h6>
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="stat-box efectivo">
                            <div class="valor">S/ {{ number_format($totEfectivo, 2) }}</div>
                            <div class="label">💵 Efectivo</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-box yape">
                            <div class="valor" style="color:#7B2D8B">S/ {{ number_format($totYape, 2) }}</div>
                            <div class="label">📱 Yape</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-box plin">
                            <div class="valor" style="color:#00A0E3">S/ {{ number_format($totPlin, 2) }}</div>
                            <div class="label">📱 Plin</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-box tarjeta">
                            <div class="valor" style="color:#ffc107">S/ {{ number_format($totTarjeta, 2) }}</div>
                            <div class="label">💳 Tarjeta</div>
                        </div>
                    </div>
                </div>

                @if($cajaAbierta->observaciones)
                <div class="mb-3 p-3 rounded" style="background:#fff3cd;font-size:0.9rem">
                    📝 <strong>Obs:</strong> {{ $cajaAbierta->observaciones }}
                </div>
                @endif

                <form action="{{ route('admin.caja.cerrar', $cajaAbierta) }}" method="POST"
                    onsubmit="return confirm('¿Confirmar cierre de caja?')">
                    @csrf
                    <button type="submit" class="btn-porto btn-cerrar w-100" style="padding:14px">
                        🔒 Cerrar Caja y Generar Reporte
                    </button>
                </form>
            </div>
        </div>

        {{-- COBROS DE MOZOS EN TIEMPO REAL --}}
        <div class="caja-card mb-4">
            <div class="caja-header cobros" style="justify-content:space-between">
                <span>
                    <span class="pulse-dot"></span>
                    💰 Cobros de Mozos — Turno Actual
                </span>
                <span id="ultimo-update" style="font-size:0.78rem;font-weight:400;opacity:0.8"></span>
            </div>
            <div id="cobros-container">
                {{-- Cargado por JS --}}
                <div class="text-center p-4" style="color:#aaa">
                    <div style="font-size:1.5rem">⏳</div>
                    <div style="font-size:0.85rem;margin-top:4px">Cargando cobros...</div>
                </div>
            </div>
            <div class="p-3 border-top d-flex justify-content-between align-items-center" style="background:#f8f9fa">
                <span style="font-size:0.82rem;color:#888">
                    🔄 Auto-actualiza cada 15 seg
                </span>
                <div style="font-weight:800;color:#1A2E5A;font-size:1rem">
                    Total cobrado por mozos:
                    <span id="total-cobros-mozos" style="color:#C0392B">S/ 0.00</span>
                </div>
            </div>
        </div>

        @else
        {{-- ABRIR CAJA --}}
        <div class="caja-card">
            <div class="caja-header nueva">
                <span>🔑 Apertura de Caja</span>
                <span style="font-size:0.85rem;font-weight:400">{{ now()->format('d/m/Y') }}</span>
            </div>
            <div class="p-4">
                @if(session('error'))
                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.caja.abrir') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label style="font-weight:700;color:#1A2E5A;margin-bottom:6px;display:block">
                            💵 Monto inicial en efectivo (S/)
                        </label>
                        <input type="number" name="monto_inicial" step="0.01" min="0"
                            class="form-control form-control-lg" placeholder="0.00" required
                            style="border:2px solid #5BC8D4;border-radius:10px;font-size:1.2rem;font-weight:700">
                    </div>
                    <div class="mb-4">
                        <label style="font-weight:700;color:#1A2E5A;margin-bottom:6px;display:block">
                            📝 Observaciones (opcional)
                        </label>
                        <textarea name="observaciones" class="form-control" rows="2"
                            placeholder="Ej: Billetes de S/100, monedas de S/20..."
                            style="border:2px solid #e9ecef;border-radius:10px"></textarea>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#e8f4f8;font-size:0.85rem;color:#1A2E5A">
                        👤 <strong>Responsable:</strong> {{ auth()->user()->name }}<br>
                        🕐 <strong>Hora:</strong> {{ now()->format('d/m/Y H:i:s') }}
                    </div>
                    <button type="submit" class="btn-porto btn-abrir w-100" style="padding:14px;font-size:1rem">
                        🔓 Abrir Caja
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- HISTORIAL --}}
    <div class="col-lg-5">
        <div class="caja-card">
            <div class="caja-header cerrada">
                <span>📋 Historial de Cajas</span>
            </div>
            <div style="max-height:600px;overflow-y:auto">
                @forelse($historial as $c)
                <div class="historial-row">
                    <div>
                        <div style="font-weight:700;color:#1A2E5A;font-size:0.95rem">
                            {{ $c->apertura_at->format('d/m/Y') }}
                            <span style="font-size:0.78rem;color:#888;font-weight:400">
                                {{ $c->apertura_at->format('H:i') }} — {{ $c->cierre_at?->format('H:i') ?? '...' }}
                            </span>
                        </div>
                        <div style="font-size:0.8rem;color:#888;margin-top:2px">
                            {{ $c->usuario->name }} • {{ $c->num_ventas }} ventas
                        </div>
                    </div>
                    <div class="text-end">
                        <div style="font-weight:800;color:#C0392B;font-size:1rem">
                            S/ {{ number_format($c->total_ventas, 2) }}
                        </div>
                        <a href="{{ route('admin.caja.reporte', $c) }}" class="btn-porto btn-pdf"
                            style="font-size:0.75rem;padding:4px 12px;text-decoration:none;display:inline-block;margin-top:4px">
                            📄 PDF
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center p-5" style="color:#aaa">
                    <div style="font-size:2.5rem">📋</div>
                    <div style="margin-top:8px">Sin historial aún</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
@if($cajaAbierta)
<script>
    const aperturaCaja = '{{ $cajaAbierta->apertura_at->toISOString() }}';
    const iconos = { efectivo:'💵', yape:'📱', plin:'📲', tarjeta:'💳' };
    const clases = { efectivo:'metodo-efectivo', yape:'metodo-yape', plin:'metodo-plin', tarjeta:'metodo-tarjeta' };

    function cargarCobros() {
        fetch('{{ route("admin.caja.cobros_mozos") }}', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('cobros-container');
            const now = new Date();
            document.getElementById('ultimo-update').textContent =
                'Actualizado: ' + now.toLocaleTimeString('es-PE', {hour:'2-digit', minute:'2-digit', second:'2-digit'});

            if (!data.cobros || !data.cobros.length) {
                container.innerHTML = `<div class="text-center p-4" style="color:#aaa">
                    <div style="font-size:2rem">🧾</div>
                    <div style="font-size:0.85rem;margin-top:4px">Sin cobros registrados aún</div>
                </div>`;
                document.getElementById('total-cobros-mozos').textContent = 'S/ 0.00';
                return;
            }

            let html = '';
            let totalGeneral = 0;

            data.cobros.forEach(v => {
                totalGeneral += parseFloat(v.total);
                const hora = new Date(v.created_at).toLocaleTimeString('es-PE', {hour:'2-digit', minute:'2-digit'});
                const icono = iconos[v.metodo_pago] ?? '💰';
                const clase = clases[v.metodo_pago] ?? 'metodo-efectivo';
                const mesa  = v.mesa ?? '-';
                const mozo  = v.mozo ?? 'Sistema';

                html += `<div class="cobro-row">
                <div>
                    <div style="font-weight:700;color:#1A2E5A;font-size:0.9rem">
                        🪑 Mesa ${mesa}
                        <span style="font-size:0.78rem;color:#888;font-weight:400">— ${hora}</span>
                    </div>
                    <div style="font-size:0.8rem;color:#888;margin-top:2px">
                        👤 ${mozo}
                        &nbsp;|&nbsp;
                        <span class="badge-metodo ${clase}">${icono} ${v.metodo_pago.toUpperCase()}</span>
                        ${parseFloat(v.vuelto) > 0
                            ? `&nbsp;|&nbsp;<span style="background:#d1e7dd;color:#0a3622;font-size:0.75rem;padding:2px 8px;border-radius:10px;font-weight:700">🪙 Vuelto: S/ ${parseFloat(v.vuelto).toFixed(2)}</span>`
                            : ''}
                    </div>
                </div>
                    <div style="font-weight:800;color:#C0392B;font-size:1rem">
                        S/ ${parseFloat(v.total).toFixed(2)}
                    </div>
            </div>`;
            });

            container.innerHTML = html;
            document.getElementById('total-cobros-mozos').textContent = 'S/ ' + totalGeneral.toFixed(2);
        })
        .catch(() => {
            document.getElementById('cobros-container').innerHTML =
                '<div class="text-center p-3" style="color:#C0392B;font-size:0.85rem">⚠️ Error al cargar cobros</div>';
        });
    }

    cargarCobros();
    setInterval(cargarCobros, 15000);
</script>
@endif
@endpush
