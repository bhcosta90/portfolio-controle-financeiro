<?php

namespace Costa\Modules\Payment\UseCases\DTO\FinancialBalance;

use DateTime;

class Input
{
    public function __construct(
        public DateTime $date,
    ) {
        //
    }
}
