<?php

namespace Core\Application\Report\Contracts;

interface PriceInterface
{
    public function convert(float $value);

    public function prefix();
}
