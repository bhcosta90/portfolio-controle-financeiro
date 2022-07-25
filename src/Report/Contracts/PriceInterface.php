<?php

namespace Core\Report\Contracts;

interface PriceInterface
{
    public function convert(float $value);

    public function prefix();
}
