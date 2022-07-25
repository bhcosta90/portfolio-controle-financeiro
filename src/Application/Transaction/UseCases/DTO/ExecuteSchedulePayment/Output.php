<?php

namespace Core\Application\Transaction\UseCases\DTO\ExecuteSchedulePayment;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //        
    }
}
