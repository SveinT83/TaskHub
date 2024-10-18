<?php

namespace tronderdata\categories\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Angi hvilke felter som kan fylles ut ved masseopprettelse
    protected $fillable = ['name', 'description', 'parent_id', 'slug', 'status', 'created_by', 'updated_by'];

    // Relasjon til child-kategorier
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order')->orderBy('id');
    }

    // Relasjon til parent-kategori
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
