<?php

namespace App\Models\Configurations\Meta;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MetaData extends Model
{
    protected $table = 'meta_data';
    protected $guarded = [];
    protected $casts = [
        'value' => 'array',
    ];

    public function parent(): MorphTo
    {
        return $this->morphTo(null, 'parent_type', 'parent_id');
    }

    public function field()
    {
        return $this->belongsTo(MetaField::class, 'key', 'key');
    }
}
