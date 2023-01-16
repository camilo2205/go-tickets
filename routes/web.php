<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\InstructivoController;
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

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])
    ->middleware('can:dashboard')
    ->name('dashboard');
Route::get('/estadisticas', [HomeController::class, 'estadisticas'])->middleware(['auth'])
    ->middleware('can:dashboard')
    ->name('estadisticas');
Route::get('/tickets/{ticket}/respuestas', [TicketController::class, 'getRespuestas'])->middleware(['auth'])
    ->middleware('can:tickets.edit')
    ->name('tickets:repuestas');
Route::get('/tickets/reporte', [TicketController::class, 'reporte'])->middleware(['auth'])
    ->middleware('can:tickets.index')
    ->name('tickets:reporte');
Route::get('/tickets/notificar', [TicketController::class, 'notificar'])->middleware(['auth'])
    ->name('tickets:notificar');
Route::get('/getToken', [PushController::class, 'getToken'])->middleware(['auth']);
Route::post('/push', [PushController::class, 'store'])->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('users', UserController::class);
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('soportes', SoporteController::class);
    Route::resource('respuestas', RespuestaController::class);
    Route::resource('posts', InstructivoController::class);
});

require __DIR__ . '/auth.php';
