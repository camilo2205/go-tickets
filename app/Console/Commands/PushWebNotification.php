<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\PushDemo;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as Notification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class PushWebNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:webpush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para el envío de notificaciones de escritorio';

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
        $estado = "creado";
        $tickets = Ticket::where('estado', $estado)->where('notificado', 0);
        $cantidad_tickets = $tickets->count();

        $users = User::WhereHas('roles', function ($query) {
            $query->whereIn('name', ['administrativo', 'funcionario', 'superadmin']);
        })->get();

        if ($users) {
            $clientes = $tickets->join('clientes', 'clientes.id', '=', 'cliente_id')
                ->select('razon_social', DB::raw('count(*) as cantidad'))
                ->groupBy('razon_social')
                ->get();

            $body = "";
            if ($cantidad_tickets != 0) {
                foreach ($clientes as $cliente) {
                    $body .= "$cliente->razon_social ($cliente->cantidad) \n";
                }
                Notification::send($users, new PushDemo("Tienes " . $cantidad_tickets . " tickets nuevos", $body, "verTickets"));
                $tickets->update(['notificado' => 1]);
            }
        }
    }
}
