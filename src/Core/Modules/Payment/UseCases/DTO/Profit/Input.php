<?php

namespace Costa\Modules\Payment\UseCases\DTO\Profit;

use DateTime;

class Input
{
    public function __construct(
        public DateTime $date,
    ) {
        //
    }
}
