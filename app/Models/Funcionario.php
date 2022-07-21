<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcionario extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'cargo',
        'user_id'
    ];

    public static $rules = [
        'identificacion' => 'required',
        'nombre' => 'required',
        'cargo' => 'required',
        'celular' => 'required',
        'correo' => 'required|unique:users,email'
    ];

    /**
     * Get the user that owns the Funcionario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
