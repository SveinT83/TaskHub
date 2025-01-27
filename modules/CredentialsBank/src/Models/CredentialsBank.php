<?php

namespace Modules\CredentialsBank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CredentialsBank extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'encrypted_username', 'encrypted_password'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
