<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Create;

use DateTime;

class Input
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $supplier,
        public float $value,
        public DateTime $date,
        public int $parcel,
        public ?string $recurrence = null,
        public ?string $supplierName = null,
    ) {
        //
    }
}
