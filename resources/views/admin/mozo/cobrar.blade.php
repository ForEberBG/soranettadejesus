@extends('layouts.app')
@section('title', 'Cobrar Pedido')

@section('content')
@php
$venta = $pedido->venta;
$cliente = $venta->cliente;
$mesa = $venta->mesa;
$doc = $cliente->documento ?? '';
$esQR = str_starts_with($pedido->nota ?? '', '[QR]');
if ($esQR) $tipo = 'nota_venta';
elseif (strlen($doc) == 11) $tipo = 'factura';
elseif (strlen($doc) == 8) $tipo = 'boleta';
else $tipo = 'nota_venta';
@endphp

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Encabezado --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('admin.mozo.panel') }}" class="btn btn-sm"
                    style="background:rgba(91,200,212,0.15);border:1px solid var(--azul-claro);color:var(--azul-claro)">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="mb-0" style="color:#fff">
                    <i class="fas fa-file-invoice-dollar me-2" style="color:var(--azul-claro)"></i>
                    Cobrar — Mesa {{ $mesa->numero ?? '?' }}
                </h2>
            </div>

            {{-- Resumen del pedido --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background:linear-gradient(135deg,#1A2E5A,#2a4a7f);color:#fff">
                    <span><i class="fas fa-receipt me-2"></i>Resumen del Pedido #{{ $pedido->id }}</span>
                    <span class="badge" style="background:var(--azul-claro);color:#1A2E5A">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa">
                            <tr>
                                <th>Plato</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalleVenta as $det)
                            <tr>
                                <td>{{ $det->plato->nombre ?? '—' }}</td>
                                <td class="text-center">{{ $det->cantidad }}</td>
                                <td class="text-end">S/ {{ number_format($det->precio_unitario, 2) }}</td>
                                <td class="text-end">S/ {{ number_format($det->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f8f9fa;font-weight:700">
                                <td colspan="3" class="text-end">TOTAL:</td>
                                <td class="text-end" style="color:var(--rojo);font-size:1.1rem">
                                    S/ {{ number_format($venta->total, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Datos del cliente --}}
            <div class="card mb-4">
                <div class="card-header" style="background:linear-gradient(135deg,#1A2E5A,#2a4a7f);color:#fff">
                    <i class="fas fa-user me-2"></i>Cliente
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Nombre</small>
                            <p class="mb-0 fw-bold">
                                @if(str_starts_with($pedido->nota ?? '', '[QR]'))
                                {{ trim(str_replace('[QR]', '', $pedido->nota)) }}
                                <span style="background:#5BC8D4;color:#1A2E5A;font-size:0.72rem;padding:2px 8px;border-radius:10px;font-weight:800">📱 QR</span>
                                @else
                                {{ $cliente->nombre ?? 'Sin cliente' }}
                                @endif
                            </p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Documento</small>
                            <p class="mb-0 fw-bold">{{ $cliente->documento ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1">Comprobante:</small>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" onclick="cambiarTipo('nota_venta', this)"
                                class="btn btn-sm {{ $tipo == 'nota_venta' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                                id="btn-nv">NOTA VENTA</button>
                            <button type="button" onclick="cambiarTipo('boleta', this)"
                                class="btn btn-sm {{ $tipo == 'boleta' ? 'btn-success' : 'btn-outline-success' }}"
                                id="btn-boleta">BOLETA</button>
                            <button type="button" onclick="cambiarTipo('factura', this)"
                                class="btn btn-sm {{ $tipo == 'factura' ? 'btn-primary' : 'btn-outline-primary' }}"
                                id="btn-factura">FACTURA</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Método de Pago --}}
            <div class="card">
                <div class="card-header" style="background:linear-gradient(135deg,#1A2E5A,#2a4a7f);color:#fff">
                    <i class="fas fa-cash-register me-2"></i>Método de Pago
                </div>
                <div class="card-body">

                    {{-- Pagos agregados --}}
                    <div id="lista-pagos" class="mb-3"></div>

                    {{-- Pendiente --}}
                    <div class="d-flex justify-content-between mb-3 p-2 rounded"
                        style="background:#fff3cd;font-weight:700">
                        <span>💰 Pendiente por cobrar:</span>
                        <span id="monto-pendiente" style="color:#C0392B">
                            S/ {{ number_format($venta->total, 2) }}
                        </span>
                    </div>

                    {{-- VUELTO (oculto hasta que aplique) --}}
                    <div id="caja-vuelto" class="d-flex justify-content-between mb-3 p-3 rounded"
                        style="background:#d1e7dd;font-weight:700;font-size:1.1rem;display:none!important;border:2px solid #198754">
                        <span>🪙 Vuelto a entregar:</span>
                        <span id="monto-vuelto" style="color:#0a3622;font-size:1.3rem">S/ 0.00</span>
                    </div>

                    {{-- Agregar pago --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <select id="metodo-nuevo" class="form-select form-select-sm">
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="yape">📱 Yape</option>
                                <option value="plin">📲 Plin</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" id="monto-nuevo" class="form-control form-control-sm"
                                placeholder="Monto" step="0.01" min="0"
                                oninput="previsualizarVuelto()">
                        </div>
                        <div class="col-2">
                            <button type="button" onclick="agregarPago()"
                                style="background:#1A2E5A;border:none;color:white;border-radius:6px;padding:5px 10px;font-size:0.85rem;cursor:pointer;width:100%">
                                ➕
                            </button>
                        </div>
                    </div>

                    {{-- Previsualización vuelto al tipear --}}
                    <div id="preview-vuelto" class="mb-3" style="display:none">
                        <div class="p-2 rounded text-center fw-bold"
                            style="background:#fff3cd;color:#856404;font-size:0.9rem">
                            Si paga S/ <span id="preview-monto">0.00</span>
                            → Vuelto: <span id="preview-vuelta" style="color:#0a3622">S/ 0.00</span>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button onclick="procesarCobro()" id="btnCobrar" class="btn btn-lg fw-bold"
                            style="background:linear-gradient(135deg,#C0392B,#96281B);color:#fff;border:none">
                            <i class="fas fa-check-circle me-2"></i>
                            Confirmar Cobro — S/ {{ number_format($venta->total, 2) }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let tipoComprobante = '{{ $tipo }}';
    const totalVenta = {{ $venta->total }};
    let pagos = [];

    function previsualizarVuelto() {
        const metodo      = document.getElementById('metodo-nuevo').value;
        const monto       = parseFloat(document.getElementById('monto-nuevo').value) || 0;
        const totalPagado = pagos.reduce((s, p) => s + p.monto, 0);
        const pendiente   = totalVenta - totalPagado;

        if (metodo === 'efectivo' && monto > 0) {
            const vuelto = monto - pendiente;
            document.getElementById('preview-monto').textContent  = monto.toFixed(2);
            document.getElementById('preview-vuelta').textContent = vuelto > 0
                ? 'S/ ' + vuelto.toFixed(2)
                : 'Sin vuelto';
            document.getElementById('preview-vuelto').style.display = 'block';
        } else {
            document.getElementById('preview-vuelto').style.display = 'none';
        }
    }

    function agregarPago() {
        const metodo      = document.getElementById('metodo-nuevo').value;
        const monto       = parseFloat(document.getElementById('monto-nuevo').value);
        const totalPagado = pagos.reduce((s, p) => s + p.monto, 0);
        const pendiente   = totalVenta - totalPagado;

        if (!monto || monto <= 0) {
            Swal.fire('⚠️', 'Ingresa un monto válido', 'warning');
            return;
        }

        // Solo efectivo puede superar el total (para dar vuelto)
        if (metodo !== 'efectivo' && monto > pendiente + 0.01) {
            Swal.fire('⚠️', `El monto excede el pendiente S/ ${pendiente.toFixed(2)}`, 'warning');
            return;
        }

        const existente = pagos.find(p => p.metodo === metodo);
        if (existente) {
            existente.monto = parseFloat((existente.monto + monto).toFixed(2));
        } else {
            pagos.push({ metodo, monto });
        }

        document.getElementById('monto-nuevo').value = '';
        document.getElementById('preview-vuelto').style.display = 'none';
        renderPagos();
    }

    function quitarPago(index) {
        pagos.splice(index, 1);
        renderPagos();
    }

    function renderPagos() {
        const iconos      = { efectivo:'💵', tarjeta:'💳', yape:'📱', plin:'📲' };
        const totalPagado = pagos.reduce((s, p) => s + p.monto, 0);
        const pendiente   = totalVenta - totalPagado;
        const vuelto      = totalPagado - totalVenta;

        let html = '';
        pagos.forEach((p, i) => {
            html += `<div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                style="background:#f0f9ff;border:1px solid #5BC8D4">
                <span>${iconos[p.metodo] ?? '💰'} <strong>${p.metodo.toUpperCase()}</strong></span>
                <span>S/ ${p.monto.toFixed(2)}</span>
                <button onclick="quitarPago(${i})"
                    style="background:#C0392B;border:none;color:white;border-radius:50%;width:22px;height:22px;font-size:0.75rem;cursor:pointer">✕</button>
            </div>`;
        });

        document.getElementById('lista-pagos').innerHTML = html;
        document.getElementById('monto-pendiente').textContent = 'S/ ' + Math.max(0, pendiente).toFixed(2);

        const cajaVuelto = document.getElementById('caja-vuelto');
        if (vuelto > 0.009) {
            document.getElementById('monto-vuelto').textContent = 'S/ ' + vuelto.toFixed(2);
            cajaVuelto.style.display = 'flex';
        } else {
            cajaVuelto.style.display = 'none';
        }

        if (pendiente > 0) {
            document.getElementById('monto-nuevo').placeholder = `Pendiente: S/ ${pendiente.toFixed(2)}`;
        }
    }

    function procesarCobro() {
        const totalPagado = pagos.reduce((s, p) => s + p.monto, 0);

        if (pagos.length === 0) {
            Swal.fire('⚠️', 'Agrega al menos un método de pago', 'warning');
            return;
        }

        if (totalPagado < totalVenta - 0.01) {
            Swal.fire('⚠️', `Falta S/ ${(totalVenta - totalPagado).toFixed(2)} por cubrir`, 'warning');
            return;
        }

        const vuelto = parseFloat((totalPagado - totalVenta).toFixed(2));

        const btn = document.getElementById('btnCobrar');
        Swal.fire({
            title: '¿Confirmar cobro?',
            html: `Total: <strong>S/ ${totalVenta.toFixed(2)}</strong>${vuelto > 0 ? '<br><span style="color:#198754;font-size:1.1rem">🪙 Vuelto: <strong>S/ ' + vuelto.toFixed(2) + '</strong></span>' : ''}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, cobrar',
            cancelButtonText: 'Cancelar',
            background: '#1A2E5A',
            color: '#fff',
            confirmButtonColor: '#C0392B',
            cancelButtonColor: '#5BC8D4',
        }).then(result => {
            if (!result.isConfirmed) return;
            btn.disabled  = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';

            // ← CAMBIO CLAVE: enviamos pagos originales + vuelto por separado
            fetch('{{ route("admin.mozo.pedido.procesar_cobro", $pedido->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    tipo_comprobante: tipoComprobante,
                    pagos:  pagos,   // ← pagos con montos originales (incluye el excedente)
                    vuelto: vuelto,  // ← vuelto calculado en frontend
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const msgVuelto = vuelto > 0
                        ? `<br><br>🪙 <strong>Vuelto a entregar: S/ ${vuelto.toFixed(2)}</strong>`
                        : '';
                    Swal.fire({
                        title: '¡Cobro registrado!',
                        html: `Pedido cobrado correctamente.${msgVuelto}`,
                        icon: 'success',
                        background: '#1A2E5A',
                        color: '#fff',
                        confirmButtonColor: '#5BC8D4',
                        confirmButtonText: vuelto > 0 ? `Entendido — Vuelto S/ ${vuelto.toFixed(2)}` : 'OK',
                    }).then(() => {
                        window.location.href = '{{ route("admin.mozo.panel") }}';
                    });
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmar Cobro';
                }
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo procesar el cobro', 'error');
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Confirmar Cobro';
            });
        });
    }

    function cambiarTipo(tipo, btn) {
        tipoComprobante = tipo;
        document.querySelectorAll('#btn-nv, #btn-boleta, #btn-factura').forEach(b => {
            b.className = b.className.replace('btn-secondary','btn-outline-secondary')
                                     .replace('btn-success','btn-outline-success')
                                     .replace('btn-primary','btn-outline-primary');
        });
        if (tipo == 'nota_venta') btn.className = btn.className.replace('btn-outline-secondary','btn-secondary');
        if (tipo == 'boleta')     btn.className = btn.className.replace('btn-outline-success','btn-success');
        if (tipo == 'factura')    btn.className = btn.className.replace('btn-outline-primary','btn-primary');
    }
</script>
@endpush
@endsection
