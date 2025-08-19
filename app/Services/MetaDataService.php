<?php

namespace App\Services;

use App\Models\MetaData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MetaDataService
{
    /**
     * Create or update a meta key for any model.
     *
     * @param Model $model The parent model
     * @param string $key The meta key
     * @param mixed $value The meta value
     * @param string|null $module The module name
     * @return \App\Models\MetaData
     */
    public function set(Model $model, string $key, $value, ?string $module = null)
    {
        return MetaData::updateOrCreate(
            [
                'parent_type' => get_class($model),
                'parent_id' => $model->id,
                'key' => $key,
            ],
            [
                'value' => $value,
                'module' => $module,
            ]
        );
    }

    /**
     * Get a specific meta value for any model.
     *
     * @param Model $model The parent model
     * @param string $key The meta key
     * @return mixed|null
     */
    public function get(Model $model, string $key)
    {
        return MetaData::where('parent_type', get_class($model))
            ->where('parent_id', $model->id)
            ->where('key', $key)
            ->value('value');
    }

    /**
     * Get all meta for any model.
     *
     * @param Model $model The parent model
     * @return \Illuminate\Support\Collection
     */
    public function all(Model $model): Collection
    {
        return MetaData::where('parent_type', get_class($model))
            ->where('parent_id', $model->id)
            ->get();
    }

    /**
     * Remove a specific meta key.
     *
     * @param Model $model The parent model
     * @param string $key The meta key
     * @return bool
     */
    public function delete(Model $model, string $key): bool
    {
        return (bool) MetaData::where('parent_type', get_class($model))
            ->where('parent_id', $model->id)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Delete all meta for a model.
     *
     * @param Model $model The parent model
     * @param string|null $module Only delete values for this module if specified
     * @return bool
     */
    public function deleteAll(Model $model, ?string $module = null): bool
    {
        $query = MetaData::where('parent_type', get_class($model))
            ->where('parent_id', $model->id);
        
        if ($module) {
            $query->where('module', $module);
        }
        
        return (bool) $query->delete();
    }

    /**
     * Clean up meta data for a specific module.
     *
     * @param string $module The module name
     * @param string|null $parentType Optional: Only clean up for this model type
     * @return int Number of records deleted
     */
    public function cleanupModule(string $module, ?string $parentType = null): int
    {
        $query = MetaData::where('module', $module);
        
        if ($parentType) {
            $query->where('parent_type', $parentType);
        }
        
        return $query->delete();
    }
}
