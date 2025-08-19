<?php

namespace App\Models\Configurations\Meta;

use Illuminate\Database\Eloquent\Model;

class MetaField extends Model
{
    protected $table = 'meta_fields';
    protected $guarded = [];
    protected $casts = [
        'default_value' => 'array',
        'options' => 'array',
    ];
}
