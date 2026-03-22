@extends('layouts.app')
@section('title', 'Panel Mozo - Porto Azul')
@section('content')

<style>
    :root {
        --azul: #5BC8D4;
        --oscuro: #1A2E5A;
        --rojo: #C0392B;
    }

    .mesa-card {
        border-radius: 12px;
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .mesa-libre {
        background: rgba(91, 200, 212, 0.15);
        border-color: #5BC8D4;
        color: #5BC8D4;
    }

    .mesa-libre:hover {
        background: rgba(91, 200, 212, 0.3);
        transform: translateY(-2px);
    }

    .mesa-ocupada {
        background: rgba(192, 57, 43, 0.15);
        border-color: #C0392B;
        color: #C0392B;
    }

    .mesa-ocupada:hover {
        background: rgba(192, 57, 43, 0.25);
    }

    .estado-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .badge-preparacion {
        background: #cff4fc;
        color: #055160;
    }

    .badge-listo {
        background: #d1e7dd;
        color: #0a3622;
    }

    .plato-btn {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.82rem;
    }

    .plato-btn:hover {
        border-color: var(--azul);
        background: rgba(91, 200, 212, 0.1);
    }

    .plato-btn.selected {
        border-color: var(--oscuro);
        background: rgba(26, 46, 90, 0.1);
    }

    .panel-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .panel-header {
        background: linear-gradient(135deg, var(--oscuro), #1a4a6b);
        color: white;
        padding: 14px 18px;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* MODAL ESCRITORIO */
    #modalPedido {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99998;
        background: rgba(0, 0, 0, 0.6);
        align-items: center;
        justify-content: center;
    }

    #modalPedido.abierto {
        display: flex;
    }

    #modal-inner {
        width: 95%;
        max-width: 1000px;
        max-height: 90vh;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    #modal-body {
        display: flex;
        flex: 1;
        overflow: hidden;
        min-height: 0;
    }

    #modal-platos {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        border-right: 1px solid #eee;
    }

    #modal-resumen {
        width: 280px;
        padding: 16px;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        overflow-y: auto;
    }

    #modal-footer {
        padding: 12px 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-shrink: 0;
        background: white;
    }

    /* MÓVIL */
    @media (max-width: 768px) {
        #modalPedido {
            align-items: stretch;
            justify-content: stretch;
        }

        #modal-inner {
            width: 100%;
            max-width: 100%;
            border-radius: 0;
            display: grid;
            grid-template-rows: auto 1fr auto;
            /* altura la pone JS con window.innerHeight */
        }

        #modal-body {
            flex-direction: column;
            overflow-y: auto;
            min-height: 0;
        }

        #modal-platos {
            border-right: none;
            border-bottom: 2px solid #5BC8D4;
            overflow-y: visible;
            flex: none;
            width: 100%;
        }

        #modal-resumen {
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            flex: none;
        }

        .platos-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)) !important;
        }

        #modal-footer {
            padding: 8px 12px;
            border-top: 2px solid #eee;
            background: white;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 style="font-family:'Playfair Display',serif;color:#5BC8D4;margin:0">🍽️ Panel del Mozo</h4>
    <span style="color:rgba(255,255,255,0.6);font-size:0.85rem">{{ auth()->user()->name }}</span>
</div>

<div class="row g-3">
    {{-- MESAS --}}
    <div class="col-12">
        <div class="panel-card">
            <div class="panel-header">
                <span>🍽️ Mesas</span>
                <small style="opacity:0.7">
                    {{ $mesas->where('estado','libre')->count() }} libres /
                    {{ $mesas->where('estado','ocupada')->count() }} ocupadas
                </small>
            </div>
            <div class="p-3">
                <div class="row g-2">
                    @foreach($mesas as $mesa)
                    <div class="col-4 col-md-2">
                        <div class="mesa-card {{ $mesa->estado == 'ocupada' ? 'mesa-ocupada' : 'mesa-libre' }}"
                            onclick="seleccionarMesa({{ $mesa->id }}, {{ $mesa->numero }}, '{{ $mesa->estado }}')">
                            <div style="font-size:1.8rem">🍽️</div>
                            <div>Mesa {{ $mesa->numero }}</div>
                            <small>{{ ucfirst($mesa->estado) }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- PEDIDOS ACTIVOS --}}
    <div class="col-12">
        <div class="panel-card">
            <div class="panel-header">
                <span>📋 Mis Pedidos Activos</span>
                <button onclick="actualizarPedidos()"
                    style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:6px;padding:4px 10px;font-size:0.8rem;cursor:pointer">
                    🔄 Actualizar
                </button>
            </div>
            <div id="pedidos-container" class="p-0">
                @forelse($pedidos as $p)
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <span style="font-weight:700;color:#1A2E5A">
                            Mesa {{ $p->venta->mesa->numero ?? '-' }} — Pedido #{{ $p->numero_dia ?? $p->id }}
                        </span>
                        <br>
                        <small style="color:#888">{{ $p->created_at->format('H:i a') }}</small>
                        @if($p->nota)
                        <br>
                        @if(str_starts_with($p->nota, '[QR]'))
                        <span
                            style="background:#5BC8D4;color:#1A2E5A;font-weight:800;font-size:0.72rem;padding:2px 8px;border-radius:10px">📱
                            QR</span>
                        <small style="color:#C0392B">📋 {{ substr($p->nota, 5) }}</small>
                        @else
                        <small style="color:#888">📋 {{ $p->nota }}</small>
                        @endif
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <span
                            class="estado-badge badge-{{ $p->estado == 'en preparacion' ? 'preparacion' : $p->estado }}">
                            {{ ucfirst($p->estado) }}
                        </span>
                        @if($p->estado == 'listo')
                        <button onclick="marcarEntregado({{ $p->id }})"
                            style="background:#1A2E5A;border:none;color:white;border-radius:6px;padding:5px 12px;font-size:0.8rem;cursor:pointer">
                            ✅ Entregar
                        </button>
                        @endif
                        <a href="{{ route('admin.mozo.pedido.cobrar', $p->id) }}"
                            style="background:#C0392B;border:none;color:white;border-radius:6px;padding:5px 12px;font-size:0.8rem;cursor:pointer;text-decoration:none">
                            💰 Cobrar
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-4">No hay pedidos activos</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL — se moverá al #modal-portal en el body --}}
<div id="modalPedido">
    <div id="modal-inner">

        {{-- Header --}}
        <div
            style="background:linear-gradient(135deg,#1A2E5A,#1a4a6b);color:white;padding:16px 20px;display:flex;justify-content:space-between;align-items:center;flex-shrink:0">
            <h5 style="margin:0;font-family:'Playfair Display',serif;font-size:1rem;color:#ffffff;">
                🛒 Nuevo Pedido — Mesa <span id="modal-mesa-num" style="color:#5BC8D4;font-weight:900"></span>
            </h5>
            <button onclick="cerrarModal()"
                style="background:rgba(255,255,255,0.2);border:none;color:white;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:1.1rem;line-height:1">✕</button>
        </div>

        {{-- Body --}}
        <div id="modal-body">
            <div id="modal-platos">
                <div style="margin-bottom:16px">
                    <label style="font-weight:700;color:#1A2E5A;font-size:0.85rem">
                        <i class="fas fa-user me-1" style="color:#5BC8D4"></i> Cliente
                    </label>
                    <select id="cliente_id" class="form-select form-select-sm mt-1">
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} — {{ $c->documento ?? 'Sin doc' }}</option>
                        @endforeach
                    </select>
                    <div id="nuevo-cliente-box" style="margin-top:8px;display:none">
                        <input type="text" id="nuevo_cliente_nombre" class="form-control form-control-sm"
                            placeholder="Nombre del cliente" style="margin-bottom:4px">
                        <input type="text" id="nuevo_cliente_doc" class="form-control form-control-sm"
                            placeholder="DNI (opcional)">
                        <input type="text" id="nuevo_cliente_ruc" class="form-control form-control-sm"
                            placeholder="RUC - 11 dígitos (opcional)" style="margin-bottom:4px">
                        <button type="button" onclick="crearClienteRapido()"
                            style="margin-top:6px;background:#1A2E5A;border:none;color:white;border-radius:6px;padding:5px 14px;font-size:0.8rem;cursor:pointer;width:100%">
                            ➕ Crear y seleccionar
                        </button>
                    </div>
                    <button type="button" onclick="toggleNuevoCliente()"
                        style="margin-top:6px;background:rgba(91,200,212,0.15);border:1px solid #5BC8D4;color:#5BC8D4;border-radius:6px;padding:4px 12px;font-size:0.75rem;cursor:pointer">
                        ➕ Cliente nuevo
                    </button>
                </div>

                @foreach($categorias as $cat)
                @if($cat->platos->count())
                <div style="margin-bottom:16px">
                    <div
                        style="font-weight:700;color:#1A2E5A;font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;padding-bottom:4px;border-bottom:2px solid #5BC8D4">
                        {{ $cat->nombre }}
                    </div>
                    <div class="platos-grid"
                        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:8px">
                        @foreach($cat->platos as $plato)
                        <div class="plato-btn" id="plato-btn-{{ $plato->id }}"
                            onclick="togglePlato({{ $plato->id }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }})">
                            @if($plato->imagen)
                            <img src="{{ asset('storage/'.$plato->imagen) }}"
                                style="width:100%;height:55px;object-fit:cover;border-radius:6px;margin-bottom:4px"
                                onerror="this.style.display='none'">
                            @endif
                            <div style="font-weight:700;font-size:0.82rem;color:#1A2E5A">{{ $plato->nombre }}</div>
                            <div style="color:#5BC8D4;font-weight:700;font-size:0.85rem">S/ {{
                                number_format($plato->precio,2) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach

                <div style="margin-top:12px">
                    <label style="font-weight:700;color:#1A2E5A;font-size:0.85rem">📝 Nota para cocina</label>
                    <textarea id="nota_pedido" class="form-control form-control-sm mt-1" rows="2"
                        placeholder="Sin cebolla, extra limón..."></textarea>
                </div>
            </div>

            <div id="modal-resumen">
                <div style="font-family:'Playfair Display',serif;font-weight:700;color:#1A2E5A;margin-bottom:12px">🧾
                    Resumen</div>
                <div id="resumen-pedido" style="flex:1;overflow-y:auto;min-height:60px">
                    <p class="text-muted text-center small mt-4">Selecciona platos...</p>
                </div>
                <div style="border-top:2px solid #5BC8D4;padding-top:12px;margin-top:12px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-weight:700;color:#1A2E5A">TOTAL:</span>
                        <span
                            style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:900;color:#C0392B">
                            S/ <span id="total-pedido">0.00</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div id="modal-footer">
            <button onclick="cerrarModal()"
                style="background:#6c757d;border:none;color:white;border-radius:8px;padding:8px 20px;cursor:pointer;font-weight:600">
                Cancelar
            </button>
            <button onclick="enviarPedido()"
                style="background:linear-gradient(135deg,#1A2E5A,#5BC8D4);border:none;color:white;border-radius:8px;padding:8px 24px;cursor:pointer;font-weight:700">
                <i class="fas fa-paper-plane me-1"></i> Enviar a Cocina
            </button>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    let mesaSeleccionada = null;
let platosSeleccionados = {};

// Mover modal al portal al cargar
document.addEventListener('DOMContentLoaded', function() {
    const portal = document.getElementById('modal-portal');
    const modal  = document.getElementById('modalPedido');
    if (portal && modal) portal.appendChild(modal);
});

function seleccionarMesa(id, num, estado) {
    mesaSeleccionada = id;
    document.getElementById('modal-mesa-num').textContent = num;
    platosSeleccionados = {};
    document.querySelectorAll('.plato-btn').forEach(b => b.classList.remove('selected'));
    actualizarResumen();
    document.getElementById('modal-body').scrollTop = 0;

    // Aplicar altura real del viewport (evita problema barra Chrome)
    const vh = window.innerHeight;
    const inner = document.getElementById('modal-inner');
    inner.style.height = vh + 'px';
    inner.style.maxHeight = vh + 'px';

    document.body.style.overflow = 'hidden';
    document.getElementById('modalPedido').classList.add('abierto');
}

function cerrarModal() {
    document.body.style.overflow = '';
    document.getElementById('modalPedido').classList.remove('abierto');
}

window.addEventListener('resize', function() {
    if (document.getElementById('modalPedido').classList.contains('abierto')) {
        const vh = window.innerHeight;
        const inner = document.getElementById('modal-inner');
        inner.style.height = vh + 'px';
        inner.style.maxHeight = vh + 'px';
    }
});

document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'modalPedido') cerrarModal();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

function togglePlato(id, nombre, precio) {
    if (platosSeleccionados[id]) { platosSeleccionados[id].cantidad++; }
    else { platosSeleccionados[id] = { id, nombre, precio, cantidad: 1 }; }
    document.getElementById('plato-btn-' + id).classList.add('selected');
    actualizarResumen();
}

function quitarPlato(id) {
    if (!platosSeleccionados[id]) return;
    platosSeleccionados[id].cantidad--;
    if (platosSeleccionados[id].cantidad <= 0) {
        delete platosSeleccionados[id];
        document.getElementById('plato-btn-' + id)?.classList.remove('selected');
    }
    actualizarResumen();
}

function actualizarResumen() {
    const items = Object.values(platosSeleccionados);
    let total = 0, html = '';
    if (!items.length) {
        document.getElementById('resumen-pedido').innerHTML = '<p class="text-muted text-center small mt-4">Selecciona platos...</p>';
        document.getElementById('total-pedido').textContent = '0.00';
        return;
    }
    items.forEach(p => {
        const sub = p.precio * p.cantidad;
        total += sub;
        html += `<div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded" style="background:white;font-size:0.85rem">
            <div>
                <div style="font-weight:700;color:#1A2E5A">${p.nombre}</div>
                <div style="color:#888">S/ ${p.precio.toFixed(2)} c/u</div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button onclick="quitarPlato(${p.id})" style="background:#C0392B;border:none;color:white;width:22px;height:22px;border-radius:50%;font-size:0.8rem;cursor:pointer;line-height:1">−</button>
                <span style="font-weight:700;min-width:20px;text-align:center">${p.cantidad}</span>
                <button onclick="togglePlato(${p.id},'${p.nombre}',${p.precio})" style="background:#1A2E5A;border:none;color:white;width:22px;height:22px;border-radius:50%;font-size:0.8rem;cursor:pointer;line-height:1">+</button>
                <span style="font-weight:700;color:#C0392B;margin-left:6px">S/${sub.toFixed(2)}</span>
            </div>
        </div>`;
    });
    document.getElementById('resumen-pedido').innerHTML = html;
    document.getElementById('total-pedido').textContent = total.toFixed(2);
}

function enviarPedido() {
    const platos = Object.values(platosSeleccionados).map(p => ({ id: p.id, cantidad: p.cantidad }));
    if (!platos.length) { Swal.fire('⚠️', 'Selecciona al menos un plato', 'warning'); return; }
    fetch('{{ route("admin.mozo.pedido.crear") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            mesa_id:    mesaSeleccionada,
            cliente_id: document.getElementById('cliente_id').value,
            nota:       document.getElementById('nota_pedido').value,
            platos
        })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            cerrarModal();
            Swal.fire({ icon:'success', title:'¡Enviado!', text:resp.mensaje, timer:2000, showConfirmButton:false })
                .then(() => location.reload());
        } else { Swal.fire('Error', resp.mensaje, 'error'); }
    })
    .catch(err => Swal.fire('Error', 'Error de conexión: ' + err.message, 'error'));
}

function marcarEntregado(id) {
    Swal.fire({
        title: '¿Marcar como entregado?', icon: 'question', showCancelButton: true,
        confirmButtonText: 'Sí, entregar', confirmButtonColor: '#1A2E5A',
        cancelButtonColor: '#aaa', background: '#1A2E5A', color: '#fff',
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/admin/mozo/pedido/${id}/entregar`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                Swal.fire({ icon:'success', title:'¡Entregado!', timer:1500, showConfirmButton:false, background:'#1A2E5A', color:'#fff' });
                setTimeout(() => location.reload(), 1500);
            }
        });
    });
}

function actualizarPedidos() {
    fetch('{{ route("admin.mozo.pedidos.estado") }}')
    .then(r => r.json())
    .then(pedidos => {
        let html = '';
        if (!pedidos.length) {
            html = '<p class="text-center text-muted p-4">No hay pedidos activos</p>';
        } else {
            pedidos.forEach(p => {
                const colores = { pendiente:'badge-pendiente', 'en preparacion':'badge-preparacion', listo:'badge-listo' };
                const badge = colores[p.estado] || 'badge-pendiente';
                const mesa  = p.venta?.mesa?.numero ?? '-';
                const hora  = new Date(p.created_at).toLocaleTimeString('es-PE', {hour:'2-digit',minute:'2-digit'});
                const btnEntregar = p.estado === 'listo'
                    ? `<button onclick="marcarEntregado(${p.id})" style="background:#1A2E5A;border:none;color:white;border-radius:6px;padding:5px 12px;font-size:0.8rem;cursor:pointer">✅ Entregar</button>` : '';
                const btnCobrar = `<a href="/admin/mozo/pedido/${p.id}/cobrar" style="background:#C0392B;border:none;color:white;border-radius:6px;padding:5px 12px;font-size:0.8rem;cursor:pointer;text-decoration:none">💰 Cobrar</a>`;
                const notaHtml = p.nota
                    ? (p.nota.startsWith('[QR]')
                        ? `<br><span style="background:#5BC8D4;color:#1A2E5A;font-weight:800;font-size:0.72rem;padding:2px 8px;border-radius:10px">📱 QR</span> <small style="color:#C0392B">📋 ${p.nota.substring(5)}</small>`
                        : `<br><small style="color:#888">📋 ${p.nota}</small>`) : '';
                html += `<div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div><span style="font-weight:700;color:#1A2E5A">Mesa ${mesa} — Pedido #${p.numero_dia ?? p.id}</span>
                    <br><small style="color:#888">${hora}</small>${notaHtml}</div>
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <span class="estado-badge ${badge}">${p.estado}</span>${btnEntregar}${btnCobrar}
                    </div></div>`;
            });
        }
        document.getElementById('pedidos-container').innerHTML = html;
    });
}

setInterval(actualizarPedidos, 10000);

function toggleNuevoCliente() {
    const box = document.getElementById('nuevo-cliente-box');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

function crearClienteRapido() {
    const nombre = document.getElementById('nuevo_cliente_nombre').value.trim();
    const doc    = document.getElementById('nuevo_cliente_doc').value.trim();
    const ruc    = document.getElementById('nuevo_cliente_ruc').value.trim();
    if (!nombre) { alert('Ingresa el nombre'); return; }
    const documento = ruc.length == 11 ? ruc : (doc.length == 8 ? doc : null);
    fetch('/admin/clientes/rapido', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ nombre, documento })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            const select = document.getElementById('cliente_id');
            const option = new Option(resp.nombre + ' — ' + (resp.documento || 'Sin doc'), resp.id, true, true);
            select.add(option);
            select.value = resp.id;
            document.getElementById('nuevo-cliente-box').style.display = 'none';
            document.getElementById('nuevo_cliente_nombre').value = '';
            document.getElementById('nuevo_cliente_doc').value = '';
            document.getElementById('nuevo_cliente_ruc').value = '';
        }
    });
}
</script>
@endpush
