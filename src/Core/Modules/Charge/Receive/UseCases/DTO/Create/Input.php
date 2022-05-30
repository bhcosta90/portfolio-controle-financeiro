<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Create;

use DateTime;

class Input
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $customerId,
        public float $value,
        public DateTime $date,
        public int $parcel,
        public ?string $recurrence = null,
        public ?string $customerName = null,
    ) {
        //
    }
}
