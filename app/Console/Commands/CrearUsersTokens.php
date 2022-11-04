<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CrearUsersTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {user} {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear token para el usuario';

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
        $userId = $this->argument('user');
        $tokenName = $this->argument('token');
        $user = User::find($userId);
        $token = $user->createToken($tokenName);
     
        print $token->plainTextToken;
    }
}
