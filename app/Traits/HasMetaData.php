<?php

namespace App\Traits;

use App\Models\MetaData;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMetaData
{
    /**
     * Get all metadata for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta(): MorphMany
    {
        return $this->morphMany(MetaData::class, 'parent');
    }

    /**
     * Get a specific meta value by key.
     *
     * @param string $key The meta key
     * @return mixed|null The meta value or null if not found
     */
    public function getMetaValue(string $key)
    {
        return $this->meta()->where('key', $key)->value('value');
    }

    /**
     * Set a meta value for this model.
     *
     * @param string $key The meta key
     * @param mixed $value The meta value
     * @param string|null $module The module name
     * @return \App\Models\MetaData
     */
    public function setMetaValue(string $key, $value, ?string $module = null)
    {
        return MetaData::updateOrCreate(
            [
                'parent_type' => get_class($this),
                'parent_id' => $this->id,
                'key' => $key,
            ],
            [
                'value' => $value,
                'module' => $module,
            ]
        );
    }

    /**
     * Remove a meta value.
     *
     * @param string $key The meta key
     * @return bool
     */
    public function removeMetaValue(string $key): bool
    {
        return (bool) $this->meta()->where('key', $key)->delete();
    }

    /**
     * Remove all meta values for this model.
     *
     * @param string|null $module Only delete values for this module if specified
     * @return bool
     */
    public function clearMeta(?string $module = null): bool
    {
        $query = $this->meta();
        
        if ($module) {
            $query->where('module', $module);
        }
        
        return (bool) $query->delete();
    }
}
