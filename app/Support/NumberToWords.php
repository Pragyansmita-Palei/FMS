<?php

namespace App\Support;

use NumberFormatter;

class NumberToWords
{
    public static function convert($amount)
    {
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);

        return $formatter->format($amount);
    }
}
