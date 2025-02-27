<?php

namespace TronderData\Equipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment'; // Spesifiser tabellen hvis den ikke følger Laravel-konvensjonen

    protected $fillable = [
        'name',
        'category_id',
        'serial_number',
        'status',
        'certification_month',
        'description',
    ];

    /**
     * Relasjon: Henter kategori for utstyret.
     */
    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }
}
