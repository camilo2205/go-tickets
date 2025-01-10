<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Models\User;
use App\Notifications\PushDemo;
use Illuminate\Support\Facades\Notification as Notification;
use Carbon\Carbon;

class NotifyLicenseExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:license-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los usuarios si la licencia de meddream está próxima a vencer';

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
 
         $clientes = Cliente::where('notifated_meddream', true)
             ->whereDate('fecha_vencimiento_meddream', '<=', Carbon::now()->addDays(15)) // Licencias que vencen en los próximos 5 días
             ->get();
 
         foreach ($clientes as $cliente) {
             $body = "El cliente " . $cliente->razon_social . " tiene meddream pronta a vencer.";
             Notification::send($users, new PushDemo("Licencia próxima a vencer", $body, "verTickets"));
         }
         $this->info('Notificaciones de licencias próximas a vencer enviadas correctamente.');
     }
}
