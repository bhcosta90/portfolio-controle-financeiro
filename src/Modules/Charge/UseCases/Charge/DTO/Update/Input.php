<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Update;

use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;
use Exception;

class Input
{
    public function __construct(
        public int|string $id,
        public string $title,
        public ?string $description,
        public float $value,
        public ModelObject $relationship,
        public DateTime $date,
        public ?string $recurrence = null,
    ) {
        //
    }
}
