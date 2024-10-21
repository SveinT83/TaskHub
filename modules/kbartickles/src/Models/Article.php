<?php

namespace tronderdata\kbartickles\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Article extends Model
{

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // PROTECTED FILLABLE
    // Define the fillable fields for the article model
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    protected $fillable = [
        'user_id',
        'title', 
        'slug', 
        'content', 
        'category_id', 
        'status',
    ];



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION USER
    // Define the relationship between the article and the user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



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
