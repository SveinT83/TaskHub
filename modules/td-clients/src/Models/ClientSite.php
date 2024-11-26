<?php
namespace tronderdata\TdClients\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSite extends Model
{
    protected $fillable = ['client_id', 'name', 'address', 'zip', 'city', 'county', 'country', 'state'];

    // En site tilhører én klient
    public function client()
    {
        return $this->belongsTo(Client::class); // Knytter site til en klient
    }

    // En site kan ha flere brukere
    public function users()
    {
        return $this->hasMany(ClientUser::class, 'site_id'); // Knytter site til flere brukere
    }
}
