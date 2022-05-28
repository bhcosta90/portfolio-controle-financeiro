<?php

namespace Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate;

class Input
{
    public function __construct(
        public int $total,
        public float $value,
    ) {
        //
    }
}
