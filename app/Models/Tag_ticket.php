<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag_ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tags_tickets';
    
    protected $fillable = [
        'tag_id',
        'ticket_id'
    ];
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the cliente that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
