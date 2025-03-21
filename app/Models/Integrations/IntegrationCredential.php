<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationCredential extends Model
{
    use HasFactory;

    protected $table = 'integration_credentials';

    protected $fillable = [
        'integration_id',
        'key',
        'value',
        'api',
        'clientid',
        'clientsecret',
        'redirecturi',
        'baseurl',
        'username',
        'password',
    ];
}