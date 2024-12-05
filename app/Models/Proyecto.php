<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    public $fillable = [
        'nombre', 'descripcion', 'cliente_id', 'user_id'
    ];

    public static $rules = [
        'nombre' => 'required',
        'descripcion' => 'required',
        'cliente_id' => 'required'
    ];

    /**
     * Get the cliente that owns the Proyecto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Get all of the bitacora for the Proyecto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bitacora()
    {
        return $this->hasMany(Bitacora::class);
    }
}
