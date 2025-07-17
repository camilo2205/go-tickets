<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'prioridad',
        'encargado_id',
        'cliente_id',
        'enfoque',
        'estado',
    ];

    public function encargado()
    {
        return $this->belongsTo(Funcionario::class, 'encargado_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
