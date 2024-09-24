<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'type', 'description', 'updated_by'];

    /**
     * Brukeren som sist oppdaterte innstillingen.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
