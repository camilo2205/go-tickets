<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationTicket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;

    /**
     * Create a new event instance.
     *
     * @param  Ticket  $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        // Define el canal privado donde se emitirá el evento
        return new PrivateChannel('ticket-channel');
    }

    /**
     * Define el nombre del evento para escucharlo en el frontend.
     *
     * @return string
     */
    public function broadcastAs()
    {
        // Nombre del evento que se usará en el frontend
        return 'ticket-notification';
    }

    /**
     * Datos que se enviarán con el evento.
     *
     * @return array
     */
}
