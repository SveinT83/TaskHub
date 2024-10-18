<?php

namespace tronderdata\kbartickles\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 
        'slug', 
        'content', 
        'category_id', 
        'status',
    ];

    // Legg til eventuelle relasjoner eller tilpasninger for modellen her
}
