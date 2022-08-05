<?php

namespace App\Models;

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
        'estado'
    ];

    public static $rules = [
        'cliente_id' => 'required',
        'descripcion' => 'required',
        'prioridad' => 'required',
        'tipo' => 'required',
        'soportes' => 'required'
    ];

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
}
