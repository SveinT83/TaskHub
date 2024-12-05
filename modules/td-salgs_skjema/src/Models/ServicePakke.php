<?php

namespace tronderdata\TdSalgsSkjema\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePakke extends Model
{
    use HasFactory;

    /**
     * Tabellnavn.
     *
     * @var string
     */
    protected $table = 'service_pakker';

    /**
     * Felter som kan fylles via mass-assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'private',
        'is_enabled',
    ];

    /**
     * Felter som skal kastes til spesifikke typer.
     *
     * @var array
     */
    protected $casts = [
        'private' => 'boolean',
        'is_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasjon til `alacarte` (mange-til-mange).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function alacarteItems()
    {
        return $this->belongsToMany(AlacarteItems::class, 'service_pakker_alacarte', 'service_pakke_id', 'alacarte_id');
    }
}
