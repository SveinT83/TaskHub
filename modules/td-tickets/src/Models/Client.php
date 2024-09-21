<?php

namespace tronderdata\TdTickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User; // Importer User-modellen
use App\Models\Client; // Importer Client-modellen

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'status_id',
        'queue_id',
        'assigned_to',
        'priority',
        'due_date',
        'created_by',
        'updated_by',
    ];

    // Relasjoner
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function timeSpends()
    {
        return $this->hasMany(TicketTimeSpend::class);
    }
}
