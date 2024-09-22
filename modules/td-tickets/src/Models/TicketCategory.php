<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_default', 'created_by', 'updated_by'];

    /**
     * En kategori kan ha mange tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_category_id');
    }

    /**
     * Relasjon til brukeren som opprettet kategorien.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasjon til brukeren som oppdaterte kategorien.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
