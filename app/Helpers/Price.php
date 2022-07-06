<?php

namespace App\Helpers;

use Core\Application\Report\Contracts\PriceInterface;

class Price implements PriceInterface
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
