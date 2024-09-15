<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    // Relasjon for å hente menyelementer
    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id'); // Sørg for at foreign key er 'menu_id'
    }
}
