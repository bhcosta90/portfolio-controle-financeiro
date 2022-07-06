<?php

namespace Core\Application\Payment\Services\DTO\ExecutePayment;

use DateTime;

class Input
{
    public function __construct(
        public DateTime $date,
    ) {
        //
    }
}
