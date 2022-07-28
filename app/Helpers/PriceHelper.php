<?php

namespace App\Helpers;

use Core\Report\Contracts\PriceInterface;

class PriceHelper implements PriceInterface
{
    public function convert(float $value)
    {
        return str()->numberBr($value);
    }

    public function prefix()
    {
        return 'R$';
    }
}
