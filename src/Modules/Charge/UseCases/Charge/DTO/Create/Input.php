<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Create;

use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;
use Exception;

class Input
{
    public function __construct(
        public string $title,
        public ?string $description,
        public float $value,
        public ModelObject $relationship,
        public DateTime $date,
        public int $parcel = 1,
        public ?string $recurrence = null,
    ) {
        if ($parcel < 1) {
            throw new Exception('Number of installments must be greater than 1');
        }
    }
}
