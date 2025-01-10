<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification as Notification;
use App\Notifications\PushDemo;
use Carbon\Carbon;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nit',
        'razon_social',
        'telefono',
        'user_id',
        'identificacion_encargado',
        'nombre_encargado',
        'fecha_vencimiento_meddream',
        'notifated_meddream',
    ];

    public static $rules = [
        'nit' => 'required',
        'nombre' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'celular' => 'required',
        'correo' => 'required|unique:users,email'
    ];

    /**
     * Get the user that owns the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function server()
    {
        return $this->hasOne(Server::class);
    }
}
