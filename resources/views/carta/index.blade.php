<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Carta - Porto Azul</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Nunito', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding-bottom: 100px;
        }

        /* HEADER */
        .header {
            background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
            color: white;
            padding: 20px 16px 16px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 15px rgba(0,0,0,0.3);
        }

        .header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #5BC8D4;
            object-fit: cover;
            margin-bottom: 8px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: #5BC8D4;
        }

        .header .mesa-info {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            margin-top: 4px;
        }

        /* CATEGORIAS NAV */
        .cat-nav {
            background: white;
            padding: 12px 16px;
            display: flex;
            gap: 8px;
            overflow-x: auto;
            border-bottom: 2px solid #f0f0f0;
            position: sticky;
            top: 125px;
            z-index: 99;
            scrollbar-width: none;
        }
        .cat-nav::-webkit-scrollbar { display: none; }

        .cat-btn {
            background: #f0f0f0;
            border: none;
            border-radius: 20px;
            padding: 7px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            font-family: 'Nunito', sans-serif;
            transition: all 0.2s;
            color: #555;
        }
        .cat-btn.active {
            background: #1A2E5A;
            color: white;
        }

        /* SECCIONES */
        .seccion {
            padding: 16px;
        }

        .seccion-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: #1A2E5A;
            font-weight: 700;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #5BC8D4;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* CARDS DE PLATOS */
        .plato-card {
            background: white;
            border-radius: 14px;
            margin-bottom: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            align-items: stretch;
            transition: transform 0.2s;
        }

        .plato-card:active { transform: scale(0.98); }

        .plato-img {
            width: 100px;
            min-height: 100px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .plato-img-placeholder {
            width: 100px;
            min-height: 100px;
            background: linear-gradient(135deg, #1A2E5A, #5BC8D4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            flex-shrink: 0;
        }

        .plato-info {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .plato-nombre {
            font-weight: 800;
            font-size: 0.95rem;
            color: #1A2E5A;
            margin-bottom: 4px;
        }

        .plato-desc {
            font-size: 0.78rem;
            color: #888;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .plato-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .plato-precio {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #C0392B;
        }

        .btn-agregar {
            background: #1A2E5A;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Nunito', sans-serif;
            transition: background 0.2s;
        }
        .btn-agregar:active { background: #5BC8D4; }
        .btn-agregar.agregado { background: #198754; }

        /* CARRITO FLOTANTE */
        .carrito-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #C0392B, #96281B);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 22px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(192,57,43,0.4);
            display: none;
            align-items: center;
            gap: 10px;
            font-family: 'Nunito', sans-serif;
            z-index: 200;
            transition: transform 0.2s;
        }
        .carrito-fab:active { transform: scale(0.95); }
        .carrito-fab.visible { display: flex; }

        .carrito-badge {
            background: white;
            color: #C0392B;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 900;
        }

        /* MODAL CARRITO */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 300;
            display: none;
            align-items: flex-end;
        }
        .modal-overlay.open { display: flex; }

        .modal-sheet {
            background: white;
            border-radius: 20px 20px 0 0;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            padding: 20px 16px;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to   { transform: translateY(0); }
        }

        .modal-handle {
            width: 40px;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            margin: 0 auto 16px;
        }

        .modal-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: #1A2E5A;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .carrito-item-info { flex: 1; }
        .carrito-item-nombre { font-weight: 700; font-size: 0.9rem; color: #1A2E5A; }
        .carrito-item-precio { font-size: 0.8rem; color: #888; }

        .cant-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cant-btn {
            background: #f0f0f0;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cant-num { font-weight: 800; font-size: 0.95rem; min-width: 20px; text-align: center; }

        .carrito-total {
            display: flex;
            justify-content: space-between;
            padding: 14px 0 6px;
            font-weight: 800;
            font-size: 1.1rem;
            color: #1A2E5A;
        }

        .total-precio { color: #C0392B; font-family: 'Playfair Display', serif; }

        .form-grupo {
            margin-bottom: 14px;
        }

        .form-grupo label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #1A2E5A;
            display: block;
            margin-bottom: 6px;
        }

        .form-grupo select,
        .form-grupo input,
        .form-grupo textarea {
            width: 100%;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            outline: none;
        }

        .form-grupo select:focus,
        .form-grupo input:focus,
        .form-grupo textarea:focus {
            border-color: #5BC8D4;
        }

        .btn-enviar {
            background: linear-gradient(135deg, #1A2E5A, #0e3a5c);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            font-family: 'Nunito', sans-serif;
            margin-top: 8px;
        }

        .btn-cancelar {
            background: #f0f0f0;
            color: #555;
            border: none;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Nunito', sans-serif;
            margin-top: 8px;
        }

        /* ÉXITO */
        .success-screen {
            display: none;
            text-align: center;
            padding: 40px 20px;
        }
        .success-screen.show { display: block; }
        .success-icon { font-size: 4rem; margin-bottom: 16px; }
        .success-titulo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: #198754;
            margin-bottom: 8px;
        }

        .no-disponible { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body>

@php
    $config     = App\Models\Configuracion::first();
    $categorias = App\Models\CategoriaPlato::with(['platos' => function($q) {
        $q->where('estado', 'disponible');
    }])->get()->filter(fn($c) => $c->platos->count() > 0);
@endphp

<!-- HEADER -->
<div class="header">
    @if($config && $config->logo)
    <img src="{{ asset($config->logo) }}" alt="Logo">
    @else
    <div style="width:60px;height:60px;border-radius:50%;background:#5BC8D4;display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 8px">🦞</div>
    @endif
    <h1>{{ $config->nombre ?? 'Porto Azul' }}</h1>
    <div class="mesa-info">
        @if(isset($mesa))
            📍 Mesa {{ $mesa->numero }} — Escanea y disfruta
        @else
            🍽️ Carta Digital
        @endif
    </div>
</div>

<!-- NAV CATEGORÍAS -->
<div class="cat-nav" id="cat-nav">
    <button class="cat-btn active" onclick="scrollToSeccion('todas', this)">🍽️ Todo</button>
    @foreach($categorias as $cat)
    <button class="cat-btn" onclick="scrollToSeccion('cat-{{ $cat->id }}', this)">
        {{ $cat->nombre }}
    </button>
    @endforeach
</div>

<!-- MENÚ -->
<div id="menu-content">
    @foreach($categorias as $cat)
    <div class="seccion" id="cat-{{ $cat->id }}">
        <div class="seccion-titulo">
            🍴 {{ $cat->nombre }}
        </div>
        @foreach($cat->platos as $plato)
        <div class="plato-card" id="plato-card-{{ $plato->id }}">
            @if($plato->imagen)
            <img class="plato-img" src="{{ asset('storage/' . $plato->imagen) }}" alt="{{ $plato->nombre }}">
            @else
            <div class="plato-img-placeholder">🍽️</div>
            @endif
            <div class="plato-info">
                <div>
                    <div class="plato-nombre">{{ $plato->nombre }}</div>
                    @if($plato->descripcion)
                    <div class="plato-desc">{{ $plato->descripcion }}</div>
                    @endif
                </div>
                <div class="plato-footer">
                    <div class="plato-precio">S/ {{ number_format($plato->precio, 2) }}</div>
                    <button class="btn-agregar" id="btn-{{ $plato->id }}"
                            onclick="agregarAlCarrito({{ $plato->id }}, '{{ addslashes($plato->nombre) }}', {{ $plato->precio }})">
                        + Agregar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>

<!-- CARRITO FLOTANTE -->
<button class="carrito-fab" id="carrito-fab" onclick="abrirCarrito()">
    🛒 Ver pedido
    <span class="carrito-badge" id="carrito-count">0</span>
</button>

<!-- MODAL CARRITO -->
<div class="modal-overlay" id="modal-carrito">
    <div class="modal-sheet">
        <div class="modal-handle"></div>

        <!-- CARRITO -->
        <div id="carrito-content">
            <div class="modal-titulo">🛒 Tu Pedido</div>
            <div id="carrito-items"></div>
            <div class="carrito-total">
                <span>Total:</span>
                <span class="total-precio" id="carrito-total">S/ 0.00</span>
            </div>
            <hr style="margin:12px 0">
            <div class="form-grupo">
                <label>👤 Tu nombre</label>
                <input type="text" id="cliente-nombre" placeholder="Ej: Juan Pérez">
            </div>
            <div class="form-grupo">
                <label>📝 Nota para cocina (opcional)</label>
                <textarea id="pedido-nota" rows="2" placeholder="Sin cebolla, extra limón..."></textarea>
            </div>
            <button class="btn-enviar" onclick="enviarPedido()">
                🚀 Enviar Pedido a Cocina
            </button>
            <button class="btn-cancelar" onclick="cerrarCarrito()">Seguir viendo el menú</button>
        </div>

        <!-- ÉXITO -->
        <div class="success-screen" id="success-screen">
            <div class="success-icon">✅</div>
            <div class="success-titulo">¡Pedido enviado!</div>
            <p style="color:#888;margin-bottom:20px">Tu pedido está siendo preparado. El mozo te atenderá pronto.</p>
            <button class="btn-enviar" style="background:linear-gradient(135deg,#198754,#146c43)" onclick="cerrarYLimpiar()">
                Volver al menú
            </button>
        </div>
    </div>
</div>

<script>
const mesaId   = {{ isset($mesa) ? $mesa->id : 'null' }};
const mesaNro  = "{{ isset($mesa) ? $mesa->numero : '?' }}";
let carrito    = {};

function agregarAlCarrito(id, nombre, precio) {
    if (carrito[id]) {
        carrito[id].cantidad++;
    } else {
        carrito[id] = { id, nombre, precio, cantidad: 1 };
    }
    actualizarCarritoUI();

    // Feedback visual
    const btn = document.getElementById('btn-' + id);
    btn.textContent = '✓ ' + carrito[id].cantidad;
    btn.classList.add('agregado');
}

function actualizarCarritoUI() {
    const items  = Object.values(carrito);
    const total  = items.reduce((s, i) => s + i.precio * i.cantidad, 0);
    const count  = items.reduce((s, i) => s + i.cantidad, 0);

    document.getElementById('carrito-count').textContent = count;

    const fab = document.getElementById('carrito-fab');
    if (count > 0) fab.classList.add('visible');
    else fab.classList.remove('visible');

    // Renderizar items en modal
    let html = '';
    items.forEach(item => {
        html += `<div class="carrito-item">
            <div class="carrito-item-info">
                <div class="carrito-item-nombre">${item.nombre}</div>
                <div class="carrito-item-precio">S/ ${(item.precio * item.cantidad).toFixed(2)}</div>
            </div>
            <div class="cant-control">
                <button class="cant-btn" onclick="cambiarCant(${item.id}, -1)">−</button>
                <span class="cant-num">${item.cantidad}</span>
                <button class="cant-btn" onclick="cambiarCant(${item.id}, 1)">+</button>
            </div>
        </div>`;
    });

    document.getElementById('carrito-items').innerHTML = html || '<p style="color:#aaa;text-align:center;padding:20px">Tu carrito está vacío</p>';
    document.getElementById('carrito-total').textContent = 'S/ ' + total.toFixed(2);
}

function cambiarCant(id, delta) {
    if (!carrito[id]) return;
    carrito[id].cantidad += delta;
    if (carrito[id].cantidad <= 0) {
        delete carrito[id];
        const btn = document.getElementById('btn-' + id);
        if (btn) { btn.textContent = '+ Agregar'; btn.classList.remove('agregado'); }
    } else {
        const btn = document.getElementById('btn-' + id);
        if (btn) btn.textContent = '✓ ' + carrito[id].cantidad;
    }
    actualizarCarritoUI();
}

function abrirCarrito() {
    document.getElementById('modal-carrito').classList.add('open');
    document.getElementById('carrito-content').style.display = 'block';
    document.getElementById('success-screen').classList.remove('show');
}

function cerrarCarrito() {
    document.getElementById('modal-carrito').classList.remove('open');
}

function cerrarYLimpiar() {
    carrito = {};
    actualizarCarritoUI();
    document.querySelectorAll('.btn-agregar').forEach(b => {
        b.textContent = '+ Agregar';
        b.classList.remove('agregado');
    });
    cerrarCarrito();
}

function enviarPedido() {
    const items = Object.values(carrito);
    if (!items.length) { alert('Agrega al menos un plato'); return; }

    const nombre = document.getElementById('cliente-nombre').value.trim();
    if (!nombre) { alert('Por favor ingresa tu nombre'); return; }

    const nota   = document.getElementById('pedido-nota').value.trim();

    const btn = document.querySelector('.btn-enviar');
    btn.textContent = 'Enviando...';
    btn.disabled = true;

    fetch('/carta/pedido', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            mesa_id: mesaId,
            nombre_cliente: nombre,
            nota: nota,
            platos: items.map(i => ({ id: i.id, cantidad: i.cantidad }))
        })
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            document.getElementById('carrito-content').style.display = 'none';
            document.getElementById('success-screen').classList.add('show');
        } else {
            alert('Error: ' + resp.mensaje);
            btn.textContent = '🚀 Enviar Pedido a Cocina';
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert('Error de conexión');
        btn.textContent = '🚀 Enviar Pedido a Cocina';
        btn.disabled = false;
    });
}

function scrollToSeccion(id, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (id === 'todas') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Cerrar modal al tocar overlay
document.getElementById('modal-carrito').addEventListener('click', function(e) {
    if (e.target === this) cerrarCarrito();
});
</script>

</body>
</html>
