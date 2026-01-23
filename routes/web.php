<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\InstructivoController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\ServersController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
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
Route::post('/tickets/{ticket}/marcar-corregido', [TicketController::class, 'marcarCorregido'])->middleware(['auth'])
    ->middleware('can:tickets.edit')
    ->name('tickets.marcarCorregido');
Route::get('/tickets/reporte', [TicketController::class, 'reporte'])->middleware(['auth'])
    ->middleware('can:tickets.index')
    ->name('tickets:reporte');
Route::get('/tickets/notificar', [TicketController::class, 'notificar'])->middleware(['auth'])
    ->name('tickets:notificar');
Route::get('/getToken', [PushController::class, 'getToken'])->middleware(['auth']);
Route::post('/push', [PushController::class, 'store'])->middleware(['auth']);
Route::get('/tickets/respuestas/{id}', [TicketController::class, 'updateRespuestas'])->middleware(['auth']);
Route::get('/servers', [ServersController::class, 'index'])->middleware('auth');
Route::get('/clientes/{cliente}/server', [ClienteController::class, 'showServer'])->middleware(['auth'])
    ->name('clientes.servidor');
Route::put('/clientes/{cliente}/server', [ClienteController::class, 'updateDisk'])->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('tareas/render', [TareaController::class, 'render'])->name('tareas.render');
    Route::get('categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::resource('clientes', ClienteController::class);
    Route::resource('users', UserController::class);
    Route::resource('funcionarios', FuncionarioController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('soportes', SoporteController::class);
    Route::resource('tareas', TareaController::class);
    Route::resource('respuestas', RespuestaController::class);
    Route::resource('posts', InstructivoController::class);
    Route::resource('categories', CategoryController::class);

    // Rutas adicionales para tareas (index.blade.php)
    Route::patch('tareas/{tarea}/updateEnfoque', [TareaController::class, 'updateEnfoque'])->name('tareas.updateEnfoque');
    Route::patch('tareas/{tarea}/updateEstado', [TareaController::class, 'updateEstado'])->name('tareas.updateEstado');
    Route::patch('tareas/{tarea}/updatePrioridad', [TareaController::class, 'updatePrioridad'])->name('tareas.updatePrioridad');
    Route::patch('tareas/{tarea}/updateEncargado', [TareaController::class, 'updateEncargado'])->name('tareas.updateEncargado');
});

require __DIR__ . '/auth.php';
