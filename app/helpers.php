<?php

use Carbon\Carbon;
use Twilio\Rest\Client;
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

function sendSMS($telefono, $body)
{
    try {
        $account_sid = config("app.TWILIO_SID", null);
        $auth_token = config("app.TWILIO_AUTH_TOKEN", null);
        $twilio_number = config("app.TWILIO_NUMBER", null);
        $indicativo = config('app.indicativo', '+58');

        $client = new Client($account_sid, $auth_token);
        // dd($account_sid, $auth_token, $twilio_number);

        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $indicativo . $telefono,
            array(
                'from' => $twilio_number,
                'body' => $body
            )
        );
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
