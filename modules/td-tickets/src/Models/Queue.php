<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
