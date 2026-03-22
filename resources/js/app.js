import './bootstrap';

import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

// Echo para escuchar eventos privados
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, // o '415ed4d7aa3b6d664fb9'
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // o 'sa1'
    forceTLS: true
});

// ID del mozo logueado
const userId = document.head.querySelector('meta[name="user-id"]').content;

// Escucha notificaciones privadas
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // Aquí puedes mostrar un alert, toast o actualizar tu panel
        alert(notification.mensaje);
    });


