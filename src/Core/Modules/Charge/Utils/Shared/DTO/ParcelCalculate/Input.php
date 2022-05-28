<?php

namespace Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate;

use DateTime;

class Input
{
    public function __construct(
        public int $total,
        public float $value,
        public DateTime $date,
    ) {
        //
    }
}
