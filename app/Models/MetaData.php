<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MetaData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_type',
        'parent_id',
        'key',
        'value',
        'module',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get the parent model (polymorphic relationship).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get metadata by entity type and ID
     *
     * @param string $type The model class name
     * @param int $id The model ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function forEntity(string $type, int $id)
    {
        return static::where('parent_type', $type)
            ->where('parent_id', $id)
            ->get();
    }

    /**
     * Get a single meta value by key
     *
     * @param string $type The model class name
     * @param int $id The model ID
     * @param string $key The meta key
     * @return mixed|null
     */
    public static function getValue(string $type, int $id, string $key)
    {
        return static::where('parent_type', $type)
            ->where('parent_id', $id)
            ->where('key', $key)
            ->value('value');
    }
}
