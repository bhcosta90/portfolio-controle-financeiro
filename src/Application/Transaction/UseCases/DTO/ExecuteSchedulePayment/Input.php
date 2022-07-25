<?php

namespace Core\Application\Transaction\UseCases\DTO\ExecuteSchedulePayment;

use DateTime;

class Input
{
    public function __construct(
        public DateTime $date,
    ) {
        //        
    }
}
