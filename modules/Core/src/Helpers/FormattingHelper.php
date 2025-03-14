<?php

namespace Modules\Core\Helpers;

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