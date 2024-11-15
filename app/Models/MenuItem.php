<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MODEL - MENU ITEM
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This model represents a menu item in the application.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    1	name=id Primær	Type=bigint(20)		        Null=Nei	Standard=Ingen		Ekstra=AUTO_INCREMENT
    2	name=menu_id    Type=Indeks	bigint(20)		Null=Nei	Standard=Ingen
    3	name=parent_id  Type=Indeks	bigint(20)		Null=Ja	    Standard=NULL
    4	name=title	    Type=varchar(255)	        Null=Nei	Standard=Ingen
    5	name=url	    Type=varchar(255)	    	Null=Nei	Standard=Ingen
    6	name=icon	    Type=text	            	Null=Ja	    Standard=NULL
    7	name=permission	Type=varchar(255)	    	Null=Ja	    Standard=NULL
    8	name=order	    Type=int(11)			    Null=Nei	Standard=0
    9	name=created_at	Type=timestamp			    Null=Ja	    Standard=NULL
    10	name=updated_at	Type=timestamp			    Null=Ja	    Standard=NULL
*/

class MenuItem extends Model
{

    // -------------------------------------------------
    // Fillable attributes for mass assignment.
    // -------------------------------------------------
    protected $fillable = ['menu_id', 'parent_id', 'title', 'url', 'order'];


    // --------------------------------------------------------------------------------------------------
    // FUNCTION - MENU
    // --------------------------------------------------------------------------------------------------
    // Defines the relationship between MenuItem and Menu.
    // --------------------------------------------------------------------------------------------------
    public function menu()
    {

        // -------------------------------------------------
        // Belongs to Menu.
        // -------------------------------------------------
        return $this->belongsTo(Menu::class);
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - PARENT
    // --------------------------------------------------------------------------------------------------
    // Defines the relationship to the parent MenuItem.
    // --------------------------------------------------------------------------------------------------
    public function parent()
    {

        // -------------------------------------------------
        // Belongs to parent MenuItem.
        // -------------------------------------------------
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CHILDREN
    // --------------------------------------------------------------------------------------------------
    // Defines the relationship to the child MenuItems.
    // --------------------------------------------------------------------------------------------------
    public function children()
    {

        // -------------------------------------------------
        // Has many child MenuItems.
        // -------------------------------------------------
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
}