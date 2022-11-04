<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('send-sms', function (Request $request) {
    try {
        $token = json_decode(Storage::get('token.json'))->token;
        $response = Http::withHeaders(['api-key' => config('app.SMS_APIKEY')])
            ->withToken($token)
            ->post("https://api.cellvoz.com/v2/sms/single", [
                'number' => config('app.indicativo') . $request->input('destino'),
                'message' => $request->input('mensaje'),
                'type' => 1
            ]);

        return $response;
    } catch (Exception $e) {
        Log::error($e->getMessage());
        return response()->json(['error' => 'Error del servidor. Mensaje no enviado.'], 500);
    }
})->middleware('auth:sanctum');
