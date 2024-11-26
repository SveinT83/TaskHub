<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class TimeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'taxable',
    ];

    // Relasjon til TicketTimeSpend
    public function ticketTimeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }
}
