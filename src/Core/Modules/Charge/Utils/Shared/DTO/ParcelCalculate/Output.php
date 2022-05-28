<?php

namespace Costa\Modules\Charge\Utils\Shared\DTO\ParcelCalculate;

use DateTime;

class Output
{
    public function __construct(
        public DateTime $date,
        public float $value,
    ) {
        //
    }
}
