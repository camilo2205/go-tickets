<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GetToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtener token para el envío de mensajes de texto';

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
        $response = Http::post('https://api.cellvoz.com/v2/auth/login', [
            'account' => config('app.SMS_ACCOUNT'),
            'password' => config('app.SMS_PASSWORD'),
        ]);
        Storage::disk('local')->put('token.json', $response);
    }
}
