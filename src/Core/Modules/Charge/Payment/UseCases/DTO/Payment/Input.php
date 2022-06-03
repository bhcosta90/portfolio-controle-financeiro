<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Payment;

use DateTime;

class Input
{
    public function __construct(
        public string $id,
        public ?string $bank,
        public float $value,
        public DateTime $date,
        public float $charge,
    ) {
        //
    }
}