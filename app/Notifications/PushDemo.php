<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PushDemo extends Notification
{
    use Queueable;

    private $title;
    private $body;
    private $action;
    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body, $action = "verTickets", $data = NULL)
    {
        $this->title = $title;
        $this->body = $body;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

  /*   public function notificar(Request $request)
    {
        $estado = "asignado";
        $notificar = Ticket::where('estado', $estado)->get();
        return response()->json(['notificar' => $notificar]);        
    } */


    public function toWebPush($notifiable, $notification)
    {

        return (new WebPushMessage())
            ->title($this->title)
            ->icon('/img/logo.png')
            ->body($this->body)
            ->action('Ver', $this->action)
            ->data($this->data);
    }
}
