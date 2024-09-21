<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Status extends Model
{
    protected $table = 'tickets_status';

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_default',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
