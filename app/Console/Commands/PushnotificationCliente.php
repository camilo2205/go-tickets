<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Respuesta;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\PushDemo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Notification as Notification;


class PushnotificationCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:webpushcliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para el envío de notificaciones de escritorio por parte de un cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $respuestas = Respuesta::where('notificado', 0)
            ->whereDate('created_at', Carbon::now())
            ->get();
        foreach ($respuestas as $respuesta) {
            $user = $respuesta->user;

            if ($user->funcionario) {
                $body = $respuesta->cuerpo;
                Notification::send($respuesta->ticket->cliente->user, new PushDemo("Respuesta Ticket #$respuesta->ticket_id", $body, "verTicket", ['ticket' => $respuesta->ticket_id]));
            } elseif ($user->cliente) {
                $body = $respuesta->cuerpo;
                Notification::send($respuesta->ticket->funcionario->user, new PushDemo("Respuesta Ticket #$respuesta->ticket_id", $body, "verTicket", ['ticket' => $respuesta->ticket_id]));
            }
            $respuesta->update(['notificado' => 1]);
        }
    }
}
