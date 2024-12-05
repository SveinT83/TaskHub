<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\EmailAccount;

class MailService
{
    public static function setMailConfig(EmailAccount $emailAccount)
    {
        $config = [
            'transport'     => 'smtp',
            'host'          => $emailAccount->smtp_host,
            'port'          => $emailAccount->smtp_port,
            'encryption'    => $emailAccount->smtp_encryption,
            'username'      => $emailAccount->smtp_username,
            'password'      => $emailAccount->smtp_password,
            'timeout'       => null,
            'auth_mode'     => 'login',
        ];

        Config::set('mail.mailers.smtp', $config);
        Config::set('mail.default', 'smtp');
        Config::set('mail.from', [
            'address' => $emailAccount->smtp_username,
            'name'    => $emailAccount->name,
        ]);
    }
}
