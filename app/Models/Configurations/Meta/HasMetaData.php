<?php

namespace App\Models\Configurations\Meta;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMetaData
{
    public function meta(): MorphMany
    {
        return $this->morphMany(MetaData::class, 'parent', 'parent_type', 'parent_id');
    }

    public function getMetaValue($key)
    {
        $meta = $this->meta()->where('key', $key)->first();
        return $meta ? $meta->value : null;
    }

    public function setMetaValue($key, $value)
    {
        \App\Services\MetaDataService::save($this, $key, $value);
    }
}
