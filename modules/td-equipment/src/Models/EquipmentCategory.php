<?php

namespace TronderData\Equipment\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $table = 'equipment_categories'; // Tabellnavn

    protected $fillable = ['name'];

    /**
     * Relasjon: Henter alle utstyr i denne kategorien.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'category_id');
    }
}
