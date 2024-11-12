<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    public $table = 'bitacora';

    public $fillable = [
        'cliente_id',
        'funcionario_id',
        'user_id',
        'nombre',
        'proyecto',
        'descripcion',
        'esfuerzo',
        'inicio',
        'fin'
    ];

    public static $rules = [
        'descripcion' => 'required',
    ];

    use HasFactory;
}
