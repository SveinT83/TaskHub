<?php

namespace TronderData\Equipment\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers'; // Kobler til riktig tabell

    protected $fillable = [
        'id', 'name', 'url', 'email', 'phone',
        'visit_address_01', 'visit_address_02', 'visit_zip', 'visit_city', 'visit_country',
        'post_address_01', 'post_address_02', 'post_zip', 'post_city', 'post_country',
        'category_id', 'vat_id'
    ];

    /**
     * Relasjon: Henter kategori fra `categories`-tabellen (hvis den finnes)
     */
    public function category()
    {
        return $this->belongsTo(\TronderData\Equipment\Models\EquipmentCategory::class, 'category_id');
    }

    /**
     * Scope: Henter alle leverandører, men gir en fallback-melding hvis ingen finnes
     */
    public static function getSuppliers()
    {
        $suppliers = self::all();

        if ($suppliers->isEmpty()) {
            return collect([(object) ['id' => 0, 'name' => 'No suppliers available']]);
        }

        return $suppliers;
    }

}