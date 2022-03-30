<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'telefono',
        'celular',
        'correo',
        'user_id'
    ];

    public static $rules = [
        'nit' => 'required',
        'nombre' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'celular' => 'required',
        'correo' => 'required|unique:clientes'
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
}
