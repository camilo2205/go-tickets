<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Server;
use App\Models\User;
use App\Notifications\PushDemo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification as Notification;

class PushNotificationServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:webpushserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para el envío de notificaciones de escritorio cuando un servidor del cliente su almacenamiento se encuentre lleno';

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
        $clientes = Cliente::with('server')->get();
        $users = User::WhereHas('roles', function ($query) {
            $query->whereIn('name', ['administrativo', 'funcionario', 'superadmin']);
        })->get();
        foreach ($clientes as $cliente) {
            $server = $cliente->server;
            if ($server) {
                foreach ($server->disks as $disk) {
                    $body = "";
                    if ($disk->notificable && $disk->used >= 90) {
                        Notification::send($users, new PushDemo("El servidor" .$server->nombre . " tiene el almacenamiento en" . $disk->used, $body, "verDatos"));
                    }
                }
            }
        }
    }
}
