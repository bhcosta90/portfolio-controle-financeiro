<?php

namespace Core\Application\Charge\Modules\Payment\Services\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $resume,
        public string $company,
        public ?string $recurrence,
        public float $value,
        public string $date,
    ) {
        //
    }
}
