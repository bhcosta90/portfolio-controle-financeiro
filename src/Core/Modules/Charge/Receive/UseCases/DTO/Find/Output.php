<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Find;

use DateTime;

class Output
{
    public function __construct(
        public int|string $id,
        public string $title,
        public ?string $description,
        public float $pay,
        public string $date,
        public float $value,
        public string $customerId,
        public string $customerName,
        public ?string $recurrenceId,
    ) {

    }
}
