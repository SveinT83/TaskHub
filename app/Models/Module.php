<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'version',
        'description',
        'path',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
