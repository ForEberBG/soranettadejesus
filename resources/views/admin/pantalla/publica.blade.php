<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porto Azul - Estado de Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(91,200,212,0.08) 0%, transparent 60%),
                linear-gradient(180deg, #0a1628 0%, #0d2240 30%, #0e3a5c 70%, #063347 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* HEADER */
        .header {
            background: linear-gradient(90deg, #1A2E5A 0%, #0e3a5c 50%, #1A2E5A 100%);
            border-bottom: 3px solid #5BC8D4;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-brand img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #5BC8D4;
            object-fit: cover;
        }

        .header-brand .brand-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #5BC8D4;
            line-height: 1;
        }

        .header-brand .brand-info p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header-right {
            text-align: right;
        }

        .header-right .time {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        .header-right .date {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* LEGEND */
        .legend {
            display: flex;
            justify-content: center;
            gap: 32px;
            padding: 12px 40px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(91,200,212,0.2);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        /* GRID DE PEDIDOS */
        .pedidos-grid {
            padding: 24px 32px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        /* CARD DE PEDIDO */
        .pedido-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .pedido-card.pendiente {
            border: 2px solid rgba(255,193,7,0.5);
            background: linear-gradient(135deg, rgba(255,193,7,0.08), rgba(26,46,90,0.95));
        }

        .pedido-card.preparacion {
            border: 2px solid rgba(91,200,212,0.6);
            background: linear-gradient(135deg, rgba(91,200,212,0.1), rgba(26,46,90,0.95));
        }

        .pedido-card.listo {
            border: 2px solid rgba(40,167,69,0.7);
            background: linear-gradient(135deg, rgba(40,167,69,0.12), rgba(26,46,90,0.95));
            animation: pulsoVerde 2s infinite;
        }

        @keyframes pulsoVerde {
            0%, 100% { box-shadow: 0 8px 32px rgba(0,0,0,0.3); }
            50%       { box-shadow: 0 8px 40px rgba(40,167,69,0.4); }
        }

        .card-header {
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pedido-card.pendiente   .card-header { background: rgba(255,193,7,0.15); }
        .pedido-card.preparacion .card-header { background: rgba(91,200,212,0.15); }
        .pedido-card.listo       .card-header { background: rgba(40,167,69,0.2); }

        .card-mesa {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .card-num {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
        }

        .estado-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-pendiente   { background: rgba(255,193,7,0.25);  color: #ffc107; border: 1px solid #ffc107; }
        .badge-preparacion { background: rgba(91,200,212,0.25); color: #5BC8D4; border: 1px solid #5BC8D4; }
        .badge-listo       { background: rgba(40,167,69,0.25);  color: #28a745; border: 1px solid #28a745; }

        .card-body { padding: 14px 18px; }

        .platos-list { margin-bottom: 12px; }

        .plato-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 0.9rem;
        }

        .plato-item:last-child { border-bottom: none; }

        .plato-nombre { color: rgba(255,255,255,0.9); font-weight: 600; }
        .plato-cant   { color: #5BC8D4; font-weight: 800; font-size: 1rem; }

        .card-footer {
            padding: 10px 18px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tiempo-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.6);
        }

        .tiempo-valor {
            font-weight: 800;
            font-size: 1rem;
        }

        .tiempo-valor.rapido  { color: #28a745; }
        .tiempo-valor.normal  { color: #ffc107; }
        .tiempo-valor.demorado { color: #dc3545; }

        .total-venta {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #5BC8D4;
        }

        /* MENSAJE SIN PEDIDOS */
        .sin-pedidos {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
        }

        .sin-pedidos .icono {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.4;
        }

        .sin-pedidos h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 8px;
        }

        .sin-pedidos p {
            color: rgba(255,255,255,0.25);
            font-size: 1rem;
        }

        /* NOTA */
        .nota-pedido {
            font-size: 0.78rem;
            color: rgba(255,193,7,0.8);
            font-style: italic;
            margin-top: 4px;
        }

        /* TICKER BOTTOM */
        .ticker {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1A2E5A;
            border-top: 2px solid #5BC8D4;
            padding: 8px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
        }

        .ticker-pulse {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #5BC8D4;
            font-weight: 700;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #5BC8D4;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.8); }
        }
    </style>
</head>
<body>

@php $config = App\Models\Configuracion::first(); @endphp

<!-- HEADER -->
<div class="header">
    <div class="header-brand">
        @if($config && $config->logo)
        <img src="{{ asset($config->logo) }}" alt="Logo">
        @else
        <div style="width:60px;height:60px;border-radius:50%;background:#5BC8D4;display:flex;align-items:center;justify-content:center;font-size:1.8rem">🦞</div>
        @endif
        <div class="brand-info">
            <h1>{{ $config->nombre ?? 'Porto Azul' }}</h1>
            <p>Estado de Pedidos</p>
        </div>
    </div>
    <div class="header-right">
        <div class="time" id="reloj">--:--</div>
        <div class="date" id="fecha-hoy"></div>
    </div>
</div>

<!-- LEYENDA -->
<div class="legend">
    <div class="legend-item">
        <div class="legend-dot" style="background:#ffc107"></div>
        <span>En espera</span>
    </div>
    <div class="legend-item">
        <div class="legend-dot" style="background:#5BC8D4"></div>
        <span>Preparando</span>
    </div>
    <div class="legend-item">
        <div class="legend-dot" style="background:#28a745"></div>
        <span>¡Listo para servir!</span>
    </div>
</div>

<!-- GRID PEDIDOS -->
<div class="pedidos-grid" id="pedidos-grid">
    @forelse($pedidos as $p)
    @php
        $estado    = $p->estado;
        $clasCard  = $estado == 'en preparacion' ? 'preparacion' : $estado;
        $badgeText = $estado == 'pendiente' ? '⏳ En espera' : ($estado == 'en preparacion' ? '👨‍🍳 Preparando' : '✅ ¡Listo!');
        $minutos   = now()->diffInMinutes($p->created_at);
        $claseT    = $minutos < 10 ? 'rapido' : ($minutos < 20 ? 'normal' : 'demorado');
        $estimado  = $estado == 'listo' ? '¡Listo!' : ($estado == 'en preparacion' ? max(0, 15 - $minutos).' min' : max(0, 20 - $minutos).' min');
    @endphp
    <div class="pedido-card {{ $clasCard }}">
        <div class="card-header">
            <div>
                <div class="card-mesa">Mesa {{ $p->venta->mesa->numero ?? '?' }}</div>
                <div class="card-num">Pedido #{{ $p->id }}</div>
            </div>
            <span class="estado-badge badge-{{ $clasCard }}">{{ $badgeText }}</span>
        </div>
        <div class="card-body">
            <div class="platos-list">
                @foreach($p->venta->detalleVenta as $d)
                <div class="plato-item">
                    <span class="plato-nombre">{{ $d->plato->nombre ?? '—' }}</span>
                    <span class="plato-cant">x{{ $d->cantidad }}</span>
                </div>
                @endforeach
            </div>
            @if($p->nota)
            <div class="nota-pedido">📝 {{ $p->nota }}</div>
            @endif
        </div>
        <div class="card-footer">
            <div class="tiempo-info">
                ⏱ Tiempo:
                <span class="tiempo-valor {{ $estado == 'listo' ? 'rapido' : $claseT }}">
                    {{ $estimado }}
                </span>
            </div>
            <div class="total-venta">S/ {{ number_format($p->venta->total, 2) }}</div>
        </div>
    </div>
    @empty
    <div class="sin-pedidos">
        <div class="icono">🍽️</div>
        <h2>Sin pedidos activos</h2>
        <p>Los pedidos aparecerán aquí cuando sean enviados a cocina</p>
    </div>
    @endforelse
</div>

<!-- TICKER INFERIOR -->
<div class="ticker">
    <div class="ticker-pulse">
        <div class="pulse-dot"></div>
        Actualizando en tiempo real
    </div>
    <span>{{ $config->direccion ?? '' }} · Tel: {{ $config->telefono ?? '' }}</span>
    <span id="ultima-act">Última actualización: --:--</span>
</div>

<script>
// Reloj
function actualizarReloj() {
    const ahora = new Date();
    document.getElementById('reloj').textContent =
        ahora.toLocaleTimeString('es-PE', {hour:'2-digit', minute:'2-digit'});
    document.getElementById('fecha-hoy').textContent =
        ahora.toLocaleDateString('es-PE', {weekday:'long', day:'numeric', month:'long'});
}
actualizarReloj();
setInterval(actualizarReloj, 1000);

// Polling pedidos cada 8 segundos
function actualizarPedidos() {
    fetch('/pantalla/pedidos/json')
        .then(r => r.json())
        .then(pedidos => {
            const grid = document.getElementById('pedidos-grid');
            const ahora = new Date();

            document.getElementById('ultima-act').textContent =
                'Última actualización: ' + ahora.toLocaleTimeString('es-PE', {hour:'2-digit', minute:'2-digit', second:'2-digit'});

            if (!pedidos.length) {
                grid.innerHTML = `
                    <div class="sin-pedidos">
                        <div class="icono">🍽️</div>
                        <h2>Sin pedidos activos</h2>
                        <p>Los pedidos aparecerán aquí cuando sean enviados a cocina</p>
                    </div>`;
                return;
            }

            let html = '';
            pedidos.forEach(p => {
                const estado   = p.estado;
                const clasCard = estado == 'en preparacion' ? 'preparacion' : estado;
                const badge    = estado == 'pendiente'
                    ? '⏳ En espera'
                    : (estado == 'en preparacion' ? '👨‍🍳 Preparando' : '✅ ¡Listo!');

                const creado  = new Date(p.created_at);
                const minutos = Math.floor((ahora - creado) / 60000);
                const claseT  = minutos < 10 ? 'rapido' : (minutos < 20 ? 'normal' : 'demorado');
                const estimado = estado == 'listo'
                    ? '¡Listo!'
                    : (estado == 'en preparacion'
                        ? Math.max(0, 15 - minutos) + ' min'
                        : Math.max(0, 20 - minutos) + ' min');

                const mesa  = p.venta?.mesa?.numero ?? '?';
                const total = p.venta?.total ? 'S/ ' + parseFloat(p.venta.total).toFixed(2) : '';

                let platos = '';
                if (p.venta?.detalle_venta) {
                    p.venta.detalle_venta.forEach(d => {
                        platos += `<div class="plato-item">
                            <span class="plato-nombre">${d.plato?.nombre ?? '—'}</span>
                            <span class="plato-cant">x${d.cantidad}</span>
                        </div>`;
                    });
                }

                const nota = p.nota ? `<div class="nota-pedido">📝 ${p.nota}</div>` : '';

                html += `<div class="pedido-card ${clasCard}">
                    <div class="card-header">
                        <div>
                            <div class="card-mesa">Mesa ${mesa}</div>
                            <div class="card-num">Pedido #${p.id}</div>
                        </div>
                        <span class="estado-badge badge-${clasCard}">${badge}</span>
                    </div>
                    <div class="card-body">
                        <div class="platos-list">${platos}</div>
                        ${nota}
                    </div>
                    <div class="card-footer">
                        <div class="tiempo-info">
                            ⏱ Tiempo:
                            <span class="tiempo-valor ${estado == 'listo' ? 'rapido' : claseT}">${estimado}</span>
                        </div>
                        <div class="total-venta">${total}</div>
                    </div>
                </div>`;
            });

            grid.innerHTML = html;
        })
        .catch(err => console.error('Error actualizando:', err));
}

setInterval(actualizarPedidos, 8000);
</script>

</body>
</html>
