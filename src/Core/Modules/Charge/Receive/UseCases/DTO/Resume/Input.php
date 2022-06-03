<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Resume;

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
