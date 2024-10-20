<?php

namespace tronderdata\kbartickles\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // PROTECTED FILLABLE
    // Define the fillable fields for the article model
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    protected $fillable = [
        'title', 
        'slug', 
        'content', 
        'category_id', 
        'status',
    ];



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CATEGORY
    // Define the relationship between the article and the category
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function category()
    {
        return $this->belongsTo(\tronderdata\categories\Models\Category::class, 'category_id');
    }
}
