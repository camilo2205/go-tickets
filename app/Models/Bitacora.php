<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

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

    /**
     * Get the cliente that owns the Bitacora
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get the funcionario that owns the Bitacora
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}
