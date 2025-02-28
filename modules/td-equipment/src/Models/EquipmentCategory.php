<?php

namespace TronderData\Equipment\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    protected $table = 'categories'; // Bruker tabellen "categories"

    protected $fillable = ['name', 'module'];

    /**
     * Scope: Hent kun kategorier som er globale (NULL) eller spesifikke for `td-equipment`
     */
    public function scopeEquipmentCategories($query)
    {
        return $query->whereNull('module')->orWhere('module', 'td-equipment');
    }
}
