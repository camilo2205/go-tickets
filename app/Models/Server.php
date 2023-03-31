<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{

    use HasFactory, SoftDeletes;

    public static $rules = [
        'nombre' => 'required',
        'disk_capacidad' => 'required|numeric',
        'ram' => 'nullable',
        'nit' => 'required',
        'cliente_id' => 'nullable',
    ];

    public function disks()
    {
        return $this->hasMany(Disk::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
