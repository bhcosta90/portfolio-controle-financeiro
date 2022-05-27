<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Find;

use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;

class Output
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ModelObject $relationship,
        public float $value,
        public float $payValue,
        public DateTime $date,
        public ?DateTime $dateStart = null,
        public ?DateTime $dateFinish = null,
        public ?string $recurrence = null,
        public ?string $id = null,
        public ?DateTime $createdAt = null,
    ) {
        //
    }
}
