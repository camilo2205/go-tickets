<?php

use App\Models\Respuesta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $respuesta;

    public function __construct(Respuesta $respuesta)
    {
        $this->respuesta = $respuesta;
    }

    public function broadcastOn()
    {
        return new Channel('message-channel');
    }


    public function broadcastAs(): string
    {
        return 'message-update';
    }
}