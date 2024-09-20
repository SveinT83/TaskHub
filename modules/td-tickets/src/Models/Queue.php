<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'tickets_queues';

    protected $fillable = [
        'name',
        'description',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
