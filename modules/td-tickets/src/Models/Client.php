<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'address']; // Legg til felter som er relevante for klienter

    // Definer eventuelle relasjoner for Client her
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
