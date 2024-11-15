<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alacarte extends Model
{
    use HasFactory;

    /**
     * Tabellnavn.
     *
     * @var string
     */
    protected $table = 'alacarte';

    /**
     * Primærnøkkel.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Felter som kan fylles via mass-assignment.
     *
     * @var array
     */
    protected $fillable = [
        'product_number',
        'name',
        'description',
        'price',
        'margine',
        'pr',
        'timebank',
    ];

    /**
     * Felter som skal kastes til spesifikke typer.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'margine' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasjon til `service_pakker` (mange-til-mange).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function servicePakker()
    {
        return $this->belongsToMany(ServicePakke::class, 'service_pakker_alacarte', 'alacarte_id', 'service_pakke_id');
    }
}
