@extends('layouts.app')
@section('title', 'Panel Cocina - Porto Azul')
@section('content')

<style>
    :root { --azul:#5BC8D4; --oscuro:#1A2E5A; --rojo:#C0392B; }

    .pedido-card {
        background: rgba(255,255,255,0.97);
        border-radius: 12px;
        margin-bottom: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s;
        border-left: 5px solid #ffc107;
    }
    .pedido-card.en-preparacion { border-left-color: #0dcaf0; }
    .pedido-card.listo          { border-left-color: #198754; opacity: 0.7; }

    .pedido-header {
        background: linear-gradient(135deg, #1A2E5A, #1a4a6b);
        color: white;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tiempo-badge {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
    }
    .tiempo-normal  { background: rgba(91,200,212,0.2); color:#5BC8D4; }
    .tiempo-urgente { background: rgba(255,193,7,0.2);  color:#ffc107; }
    .tiempo-critico { background: rgba(192,57,43,0.2);  color:#C0392B; animation: pulsar 1s infinite; }

    @keyframes pulsar {
        0%,100% { opacity:1; } 50% { opacity:0.6; }
    }

    .btn-accion {
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-preparar { background:#0dcaf0; color:#055160; }
    .btn-preparar:hover { background:#0ab5db; }
    .btn-listo    { background:#198754; color:white; }
    .btn-listo:hover { background:#146c43; }

    .panel-cocina-card {
        background: rgba(255,255,255,0.95);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        overflow: hidden;
    }
    .panel-cocina-header {
        background: linear-gradient(135deg, var(--rojo), #96281B);
        color: white;
        padding: 14px 18px;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="font-family:'Playfair Display',serif;color:#5BC8D4;margin:0">
        👨‍🍳 Panel de Cocina
    </h4>
    <span class="badge" id="contador"
          style="background:linear-gradient(135deg,#C0392B,#96281B);font-size:0.9rem;padding:6px 14px;border-radius:20px">
        🔥 {{ $pedidos->count() }} en cola
    </span>
</div>

<div class="row g-3">

    {{-- COLA DE PEDIDOS --}}
    <div class="col-lg-8">
        <div class="panel-cocina-card">
            <div class="panel-cocina-header">
                <span>🔥 Cola de Pedidos</span>
                <button onclick="actualizarCocina()"
                        style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:6px;padding:4px 10px;font-size:0.8rem;cursor:pointer">
                    🔄 Actualizar
                </button>
            </div>
            <div class="p-3" id="cola-pedidos">
                @forelse($pedidos as $pedido)
                @php
                    $mins = $pedido->created_at->diffInMinutes(now());
                    $tiempoCls = $mins < 10 ? 'tiempo-normal' : ($mins < 20 ? 'tiempo-urgente' : 'tiempo-critico');
                    $cardCls   = $pedido->estado == 'en preparacion' ? 'en-preparacion' : '';
                @endphp
                <div class="pedido-card {{ $cardCls }}" id="pedido-{{ $pedido->id }}">
                    <div class="pedido-header">
                        <div>
                            <span style="font-size:1.1rem;font-weight:900">
                                #{{ $pedido->numero_dia ?? $pedido->id }} — Mesa {{ $pedido->venta->mesa->numero ?? '-' }}
                            </span>
                            <span class="badge ms-2" style="background:{{ $pedido->estado=='pendiente' ? '#ffc107' : '#0dcaf0' }};color:#000;font-size:0.75rem">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="tiempo-badge {{ $tiempoCls }}">⏱ {{ $mins }}min</span>
                            @if($pedido->estado == 'pendiente')
                            <button class="btn-accion btn-preparar"
                                    onclick="cambiarEstado({{ $pedido->id }}, 'en preparacion')">
                                👨‍🍳 Preparar
                            </button>
                            @elseif($pedido->estado == 'en preparacion')
                            <button class="btn-accion btn-listo"
                                    onclick="cambiarEstado({{ $pedido->id }}, 'listo')">
                                ✅ ¡Listo!
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="p-3">
                        @if($pedido->nota)
                        <div class="mb-2 p-2 rounded" style="background:#fff3cd;font-size:0.85rem">
                            📝 <strong>Nota:</strong> {{ $pedido->nota }}
                        </div>
                        @endif
                        <table style="width:100%;font-size:0.9rem">
                            <thead>
                                <tr style="border-bottom:2px solid #5BC8D4">
                                    <th style="color:#1A2E5A;padding-bottom:6px">Plato</th>
                                    <th style="color:#1A2E5A;text-align:center">Cant.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->venta->detalleVenta as $d)
                                <tr style="border-bottom:1px solid #f0f0f0">
                                    <td style="padding:6px 0;font-weight:600">{{ $d->plato->nombre }}</td>
                                    <td style="text-align:center">
                                        <span style="background:#1A2E5A;color:white;border-radius:50%;width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem">
                                            {{ $d->cantidad }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="font-size:0.75rem;color:#aaa;margin-top:8px">
                            Cliente: {{ $pedido->venta->cliente->nombre ?? 'Sin nombre' }} •
                            Llegó: {{ $pedido->created_at->format('H:i:s') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center p-5" style="color:#aaa">
                    <div style="font-size:3rem">✅</div>
                    <div style="font-weight:700;margin-top:8px">¡Todo al día! No hay pedidos pendientes</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- HISTORIAL --}}
    <div class="col-lg-4">
        <div class="panel-cocina-card">
            <div class="panel-cocina-header" style="background:linear-gradient(135deg,#198754,#146c43)">
                <span>✅ Listos / Entregados</span>
            </div>
            <div style="max-height:600px;overflow-y:auto" id="historial">
                @forelse($historial as $p)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-weight:700;color:#1A2E5A">#{{ $p->numero_dia ?? $p->id }} Mesa {{ $p->venta->mesa->numero ?? '-' }}</span>
                        <span class="badge" style="background:{{ $p->estado=='listo' ? '#198754' : '#6c757d' }}">
                            {{ ucfirst($p->estado) }}
                        </span>
                    </div>
                    <div style="font-size:0.78rem;color:#aaa;margin-top:3px">
                        {{ $p->updated_at->format('H:i:s') }} •
                        {{ $p->venta->detalleVenta->count() }} items
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-3">Sin historial</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
@push('scripts')
<script>
function cambiarEstado(id, estado) {
    fetch(`/admin/cocina/pedido/${id}/estado`, {
        method: 'PUT',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
        body: JSON.stringify({ estado })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            actualizarCocina();
            if (estado === 'listo') {
                Swal.fire({ icon:'success', title:'¡Pedido listo!', text:'Notificando al mozo...', timer:2000, showConfirmButton:false });
            }
        }
    });
}

function actualizarCocina() {
    fetch('{{ route("admin.cocina.pedidos.nuevos") }}')
    .then(r => r.json())
    .then(pedidos => {
        document.getElementById('contador').textContent = '🔥 ' + pedidos.length + ' en cola';
        let html = '';

        if (!pedidos.length) {
            html = `<div class="text-center p-5" style="color:#aaa">
                <div style="font-size:3rem">✅</div>
                <div style="font-weight:700;margin-top:8px">¡Todo al día!</div>
            </div>`;
        } else {
            pedidos.forEach(p => {
                const mesa    = p.venta?.mesa?.numero ?? '-';
                const nroDia  = p.numero_dia ?? p.id;
                const mins    = Math.floor((new Date() - new Date(p.created_at)) / 60000);
                const tCls    = mins < 10 ? 'tiempo-normal' : mins < 20 ? 'tiempo-urgente' : 'tiempo-critico';
                const cCls    = p.estado === 'en preparacion' ? 'en-preparacion' : '';
                const badgeColor = p.estado === 'pendiente' ? '#ffc107' : '#0dcaf0';

                let platos = '';
                p.venta?.detalle_venta?.forEach(d => {
                    platos += `<tr style="border-bottom:1px solid #f0f0f0">
                        <td style="padding:6px 0;font-weight:600">${d.plato?.nombre ?? '-'}</td>
                        <td style="text-align:center"><span style="background:#1A2E5A;color:white;border-radius:50%;width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem">${d.cantidad}</span></td>
                    </tr>`;
                });

                const btnAccion = p.estado === 'pendiente'
                    ? `<button class="btn-accion btn-preparar" onclick="cambiarEstado(${p.id},'en preparacion')">👨‍🍳 Preparar</button>`
                    : p.estado === 'en preparacion'
                    ? `<button class="btn-accion btn-listo" onclick="cambiarEstado(${p.id},'listo')">✅ ¡Listo!</button>`
                    : '';

                html += `<div class="pedido-card ${cCls}">
                    <div class="pedido-header">
                        <div>
                            <span style="font-size:1.1rem;font-weight:900">#${nroDia} — Mesa ${mesa}</span>
                            <span class="badge ms-2" style="background:${badgeColor};color:#000;font-size:0.75rem">${p.estado}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="tiempo-badge ${tCls}">⏱ ${mins}min</span>
                            ${btnAccion}
                        </div>
                    </div>
                    <div class="p-3">
                        ${p.nota ? `<div class="mb-2 p-2 rounded" style="background:#fff3cd;font-size:0.85rem">📝 <strong>Nota:</strong> ${p.nota}</div>` : ''}
                        <table style="width:100%;font-size:0.9rem">
                            <thead><tr style="border-bottom:2px solid #5BC8D4"><th style="color:#1A2E5A;padding-bottom:6px">Plato</th><th style="color:#1A2E5A;text-align:center">Cant.</th></tr></thead>
                            <tbody>${platos}</tbody>
                        </table>
                    </div>
                </div>`;
            });
        }
        document.getElementById('cola-pedidos').innerHTML = html;
    });
}

// Actualizar cada 8 segundos
setInterval(actualizarCocina, 8000);
</script>
@endpush
