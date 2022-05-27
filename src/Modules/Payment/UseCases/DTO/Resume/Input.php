<?php

namespace Costa\Modules\Payment\UseCases\DTO\Resume;

use DateTime;

class Input
{
    public function __construct(
        public float $value,
        public DateTime $date,
    ) {
        //
    }
}
