<?php
namespace Taskhub\TdClients\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUser extends Model
{
    protected $fillable = ['client_id', 'site_id', 'first_name', 'last_name', 'email', 'phone'];

    // En bruker tilhører en klient
    public function client()
    {
        return $this->belongsTo(Client::class); // Knytter brukeren til en klient
    }

    // En bruker tilhører en site
    public function site()
    {
        return $this->belongsTo(ClientSite::class, 'site_id');
    }
}
