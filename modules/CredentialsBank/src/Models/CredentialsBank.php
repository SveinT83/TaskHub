<?php

namespace Modules\CredentialsBank\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CredentialsBank extends Model
{
    use HasFactory;

    // Explicitly set the table name to match the migration
    protected $table = 'credentials_bank';

    protected $fillable = [
        'user_id',
        'encrypted_username',
        'encrypted_password',
        'encrypted_aes_key',
        'iv',
        'uses_individual_key',
        'is_decrypted',
    ];

    protected $casts = [
        'uses_individual_key' => 'boolean',
        'is_decrypted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}