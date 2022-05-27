<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Resume;

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
