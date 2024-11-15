<?php
namespace tronderdata\TdClients\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'main_email', 'vat_number', 'account_number'];

    public function sites()
    {

        return $this->hasMany(ClientSite::class); // Knytter klienten til flere sites
    }

    public function users()
    {
        return $this->hasMany(ClientUser::class); // Knytter klienten til flere brukere
    }
}