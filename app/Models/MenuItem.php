<?php
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
    protected $fillable = ['menu_id', 'parent_id', 'title', 'url', 'order'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
}