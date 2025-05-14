<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Server;
use App\Models\User;
use App\Notifications\PushDemo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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

        // Intervalo de tiempo entre notificaciones (6 horas = 4 veces al día)
        $intervaloHoras = 6;

        Server::whereHas('disks', function ($query) {
            $query->where('notificable', true)->where('used', '>=', 90);
        })->with(['disks' => function ($query) {
            $query->where('notificable', true)->where('used', '>=', 90);
        }])->get()->each(function ($server) use ($users, $intervaloHoras) {
            $disks = $server->disks->where('notificable', true)->where('used', '>=', 90);
            if ($disks->count() > 0) {
                // Clave única para cada servidor en caché
                $cacheKey = 'server_notification_' . $server->id;
                $ultimaNotificacion = Cache::get($cacheKey);
                $ahora = Carbon::now();

                // Verificar si es hora de enviar una nueva notificación
                if (!$ultimaNotificacion || $ahora->diffInHours(Carbon::parse($ultimaNotificacion)) >= $intervaloHoras) {
                    $body = "";
                    $message = "El servidor " . $server->nombre . " -- " . $server->cliente->razon_social . " almacenamiento lleno:\n";
                    foreach ($disks as $disk) {
                        $body .= "- Disco montado en '" . $disk->mounted . " ' con (" . $disk->used . "%)\n";
                    }

                    // Enviar notificación
                    Notification::send($users, new PushDemo($message, $body, "verServidor", ['server' => $server->cliente_id]));

                    // Actualizar la hora de la última notificación
                    Cache::put($cacheKey, $ahora->toDateTimeString(), Carbon::now()->addDays(7));

                    $this->info("Notificación enviada para el servidor: " . $server->nombre);
                } else {
                    $this->info("Notificación omitida para el servidor: " . $server->nombre . " - Próxima notificación en " .
                        ($intervaloHoras - $ahora->diffInHours(Carbon::parse($ultimaNotificacion))) . " horas");
                }
            }
        });

        return 0;
    }
}
