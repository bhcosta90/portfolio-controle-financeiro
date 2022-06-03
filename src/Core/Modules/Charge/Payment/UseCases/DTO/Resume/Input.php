<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Resume;

use DateTime;

class Input
{
    public function __construct(
        public string $type,
        public DateTime $date,
    ) {
        //
    }
}
