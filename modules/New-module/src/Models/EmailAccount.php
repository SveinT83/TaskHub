<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// MODEL - EMAIL ACCOUNT
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This model handles the email account configurations and credentials.
// ---------------------------------------------------------------------------------------------------------------------------------------------------
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

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - Hidden attributes
    // --------------------------------------------------------------------------------------------------
    // These attributes should be hidden from arrays.
    // --------------------------------------------------------------------------------------------------
    protected $hidden = [
        'smtp_password',
        'imap_password',
    ];

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - Set SMTP password
    // --------------------------------------------------------------------------------------------------
    // Mutator for encrypting the SMTP password before saving it to the database.
    // --------------------------------------------------------------------------------------------------
    public function setSmtpPasswordAttribute($value)
    {

        // -------------------------------------------------
        // Encrypt the SMTP password
        // -------------------------------------------------
        $this->attributes['smtp_password'] = Crypt::encryptString($value);
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - Get SMTP password
    // --------------------------------------------------------------------------------------------------
    // Accessor for decrypting the SMTP password when retrieving it from the database.
    // --------------------------------------------------------------------------------------------------
    public function getSmtpPasswordAttribute($value)
    {

        // -------------------------------------------------
        // Decrypt the SMTP password
        // -------------------------------------------------
        return Crypt::decryptString($value);
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - Set IMAP password
    // --------------------------------------------------------------------------------------------------
    // Mutator for encrypting the IMAP password before saving it to the database.
    // --------------------------------------------------------------------------------------------------
    public function setImapPasswordAttribute($value)
    {

        // -------------------------------------------------
        // Encrypt the IMAP password
        // -------------------------------------------------
        $this->attributes['imap_password'] = Crypt::encryptString($value);
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - Get IMAP password
    // --------------------------------------------------------------------------------------------------
    // Accessor for decrypting the IMAP password when retrieving it from the database.
    // --------------------------------------------------------------------------------------------------
    public function getImapPasswordAttribute($value)
    {

        // -------------------------------------------------
        // Decrypt the IMAP password
        // -------------------------------------------------
        return Crypt::decryptString($value);
    }
}
