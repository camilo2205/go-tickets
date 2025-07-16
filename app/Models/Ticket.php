<?php

namespace App\Models;

use App\Events\NotificationTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'funcionario_id',
        'descripcion',
        'prioridad',
        'tipo',
        'estado',
        'created_by',
        'nombre_solicitante', // Nuevo campo
    ];

    public static $rules = [
        'cliente_id' => 'required',
        'descripcion' => 'required',
        'nombre_solicitante'=> 'required|min:10',
        'prioridad' => 'required',
        'tipo' => 'required',
        /*        'tags' => 'required' */
    ];

    protected $with = ['funcionario', 'cliente'];
    /**
     * Get the funcionario that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    /**
     * Get the cliente that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get all of the soportes for the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function soportes()
    {
        return $this->hasMany(Soporte::class);
    }

    /**
     * Get all of the respuestas for the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tags_tickets');
    }

    public function user_created()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        // Registrar eventos para created, updated, deleted
        static::created(function ($model) {
            $model->load('funcionario', 'cliente'); // Carga explícitamente la relación 'funcionario'

            broadcast(new NotificationTicket($model))->toOthers();
        });

        static::updated(function ($model) {
            // $model->load('funcionario', 'cliente'); // Carga explícitamente la relación 'funcionario'

            // broadcast(new NotificationTicket($model))->toOthers();
        });

        static::deleted(function ($model) {
            // $model->load('funcionario', 'cliente'); // Carga explícitamente la relación 'funcionario'

            // broadcast(new NotificationTicket($model))->toOthers();
        });
    }
}
