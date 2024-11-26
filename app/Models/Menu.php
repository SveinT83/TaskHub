<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MODEL - MENU
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This model represents a menu in the application.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
	1	name=id Primær	Type=bigint(20)		                                            Attributter=UNSIGNED	            Null=Nei	Standard=Ingen		Ekstra=AUTO_INCREMENT
	2	name=menu_id     Type=Indeks	bigint(20)		                                    Attributter=UNSIGNED	            Null=Nei	Standard=Ingen
	3	name=parent_id   Type=Indeks	bigint(20)		                                    Attributter=UNSIGNED	            Null=Ja	    Standard=NULL
	4	name=title	    Type=varchar(255)	        Sammenligning=utf8mb4_unicode_ci	                                    Null=Nei	Standard=Ingen
	5	name=url	        Type=varchar(255)	        Sammenligning=utf8mb4_unicode_ci	                                    Null=Nei	Standard=Ingen
	6	name=icon	    Type=text	                Sammenligning=utf8mb4_unicode_ci	                                    Null=Ja	    Standard=NULL
	7	name=permission	Type=varchar(255)	        Sammenligning=utf8mb4_unicode_ci	                                    Null=Ja	    Standard=NULL
	8	name=order	    Type=int(11)			                                                                            Null=Nei	Standard=0
	9	name=created_at	Type=timestamp			                                                                            Null=Ja	    Standard=NULL
	10	name=updated_at	timestamp			                                                                                Null=Ja	    Standard=NULL

*/

class Menu extends Model
{
	// -------------------------------------------------
	// The attributes that are mass assignable.
	// -------------------------------------------------
	protected $fillable = ['title', 'slug', 'description', 'url'];


	// --------------------------------------------------------------------------------------------------
	// FUNCTION - ITEMS
	// --------------------------------------------------------------------------------------------------
	// Define a one-to-many relationship with MenuItem.
	// --------------------------------------------------------------------------------------------------
	public function items()
	{

		// -------------------------------------------------
		// Return the related menu items.
		// -------------------------------------------------
		return $this->hasMany(MenuItem::class);
	}
}
