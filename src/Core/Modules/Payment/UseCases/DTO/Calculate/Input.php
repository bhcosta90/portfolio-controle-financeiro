<?php

namespace Costa\Modules\Payment\UseCases\DTO\Calculate;

use DateTime;

class Input
{
    public function __construct(
        public DateTime $date,
    ) {
        //
    }
}
