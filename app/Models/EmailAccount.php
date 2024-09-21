<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmailAccount extends Model
{
    protected $fillable = [
        'name',
        'description',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'is_default',
    ];

    // Hvis du ønsker å kryptere passordene automatisk
    protected $hidden = [
        'smtp_password',
        'imap_password',
    ];

    // Mutator for å kryptere SMTP-passord
    public function setSmtpPasswordAttribute($value)
    {
        $this->attributes['smtp_password'] = Crypt::encryptString($value);
    }

    // Accessor for å dekryptere SMTP-passord
    public function getSmtpPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Mutator for å kryptere IMAP-passord
    public function setImapPasswordAttribute($value)
    {
        $this->attributes['imap_password'] = Crypt::encryptString($value);
    }

    // Accessor for å dekryptere IMAP-passord
    public function getImapPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
