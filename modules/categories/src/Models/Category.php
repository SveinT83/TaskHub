<?php

namespace tronderdata\Categories\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Angi hvilke felter som kan fylles ut ved masseopprettelse
    protected $fillable = ['name', 'description', 'active', 'module'];

    // Eventuelle relasjoner eller tilpasninger for modellen kan legges til her
}
