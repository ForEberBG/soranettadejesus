<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class PedidoListoNotification extends Notification
{
    use Queueable;

    protected $pedido;

    public function __construct($pedido)
    
    {
        $this->pedido = $pedido;
    }

    // Canales de notificación
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Puedes agregar 'mail' o 'sms' si quieres
    }

    // Mensaje para base de datos
    public function toDatabase($notifiable)
    {
        return [
            'pedido_id' => $this->pedido->id,
            'mensaje' => "El pedido #{$this->pedido->id} ya está listo para servir.",
        ];
    }

    // Mensaje para broadcast (real-time)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'pedido_id' => $this->pedido->id,
            'mensaje' => "El pedido #{$this->pedido->id} ya está listo para servir.",
        ]);
    }
}
