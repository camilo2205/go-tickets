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
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['administrativo', 'funcionario', 'superadmin']);
        })->get();
        Server::whereHas('disks', function ($query) {
            $query->where('notificable', true)->where('used', '>=', 90);
        })->with(['disks' => function ($query) {
            $query->where('notificable', true)->where('used', '>=', 90);
        }])->get()->each(function ($server) use ($users) {
            $disks = $server->disks->where('notificable', true)->where('used', '>=', 90);
            if ($disks->count() > 0) {
                $body = "";
                $message = "El servidor " . $server->nombre . " -- " . $server->cliente->razon_social . " almacenamiento lleno:\n";
                foreach ($disks as $disk) {
                    $body .= "- Disco montado en '" . $disk->mounted . " ' con (" . $disk->used . "%)\n";
                }
                Notification::send($users, new PushDemo($message, $body, "verServidor", ['server' => $server->cliente_id]));
            }
        });
    }
}
