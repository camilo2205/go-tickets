<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])
->middleware('can:dashboard')
->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('users', UserController::class);
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('tickets', TicketController::class); 
    Route::resource('soportes', SoporteController::class); 
    Route::resource('respuestas', RespuestaController::class); 
});

require __DIR__.'/auth.php';
