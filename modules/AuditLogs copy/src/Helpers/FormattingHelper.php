<?php

namespace Modules\Core\src\Helpers;

class FormattingHelper
{
    public static function formatCurrency($amount, $currency = 'NOK')
    {
        return number_format($amount, 2) . " " . $currency;
    }

    public static function formatDate($date)
    {
        return date('d-m-Y', strtotime($date));
    }
}