<?php

use App\Models\User;
use App\Notifications\TicketNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use UltraMsg\WhatsAppApi;

function formatDate($date, $format = 'd/m/Y')
{
    if (!is_null($date)) {
        $fecha = Carbon::parse($date);
        return $fecha->format($format);
    } else {
        return '';
    }
}

function sendNotification(User $user, $message, $url, $ticket_id) {
    $user->notify(new TicketNotification([
        'message' => $message,
        'url' => $url,
        'ticket_id' => $ticket_id
    ]));
}

function sendSMS($telefono, $body)
{
    try {
        
    } catch (\Exception $e) {
        Log::debug("No notificado", ["Contexto" => "Notificación de ticket", "Error" => $e->getMessage()]);
    }
}

function sendToWhatsApp($to, $body)
{
    $token = config("app.WATOKEN", null);//""; // Ultramsg.com token
    $instance_id = config("app.WAINSTANCE", null);//""; // Ultramsg.com instance id
    $client = new WhatsAppApi($token, $instance_id);

    $api = $client->sendChatMessage($to, $body);
    Log::info($api);
}
