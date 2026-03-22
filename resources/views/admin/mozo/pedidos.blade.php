@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pedidos Listos</h1>

    <!-- Contador de pedidos listos -->
    <div id="contador" class="mb-3">
        <strong>Pedidos listos:</strong> <span id="cantidad">0</span>
    </div>

    <!-- Lista de pedidos listos -->
    <ul id="lista-pedidos" class="list-group">
        @foreach($pedidos as $pedido)
            @if($pedido->estado === 'listo')
                <li class="list-group-item" id="pedido-{{ $pedido->id }}">
                    Pedido #{{ $pedido->id }} - {{ $pedido->venta->mesa->nombre ?? 'Sin mesa' }}
                </li>
            @endif
        @endforeach
    </ul>
</div>

<!-- Toast container -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3"></div>
</div>
@endsection

@section('scripts')
<script>
    const userId = document.head.querySelector('meta[name="user-id"]').content;

    // Inicializar contador
    let contador = document.querySelector('#cantidad');
    let lista = document.querySelector('#lista-pedidos');
    contador.textContent = lista.children.length;

    // Función para crear un toast
    function mostrarToast(mensaje) {
        const toastContainer = document.querySelector('#toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 show mb-2';
        toast.role = 'alert';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${mensaje}</div>
                <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        toastContainer.appendChild(toast);

        // Auto ocultar después de 5 segundos
        setTimeout(() => toast.remove(), 5000);
    }

    // Escuchar notificaciones privadas
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            // Actualizar contador
            contador.textContent = parseInt(contador.textContent) + 1;

            // Agregar pedido a la lista
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.id = `pedido-${notification.pedido_id}`;
            li.textContent = `Pedido #${notification.pedido_id} - Mesa X`; // Ajusta si quieres mostrar nombre de mesa
            lista.appendChild(li);

            // Mostrar toast
            mostrarToast(notification.mensaje);
        });
</script>
@endsection
