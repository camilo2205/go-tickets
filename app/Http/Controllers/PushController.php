<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\PushDemo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Support\Facades\Notification;


class PushController extends Controller
{
    use HasPushSubscriptions;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        if ($user->funcionario) {
            $user->updatePushSubscription($endpoint, $key, $token);
        }
        return response()->json(['success' => true], 200);
    }
    /**
     * Send Push Notifications to all users.
     * 
     * @return \Illuminate\Http\Response
     */

    public function getToken()
    {
        return response()->json(["token" => config('webpush.vapid.public_key')]);
    }
}
