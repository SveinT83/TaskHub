<?php

namespace TronderData\Equipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    use HasFactory;

    protected $table = 'service_history';

    protected $fillable = [
        'equipment_id',
        'description',
        'service_date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
