<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Update;

use DateTime;

class Input
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public ?string $customer,
        public float $value,
        public DateTime $date,
        public ?string $recurrence = null,
    ) {
        //
    }
}
