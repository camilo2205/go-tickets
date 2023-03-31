<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disk extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['capacity', 'used','mounted', 'server_id'];

    protected $casts = [
        'capacity' => 'integer',
        'used' => 'integer',
        'mounted' => 'string',
        'server_id' => 'integer',
    ];

    public static $rules = [
        'capacity' => 'required|integer',
        'used' => 'required|integer',
        'mounted' => 'required',
        'server_id' => 'required|integer',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
