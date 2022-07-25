<?php

namespace Core\Application\Charge\Modules\Receive\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $title,
        public ?string $resume,
        public string $customer,
        public string $customerName,
        public ?string $recurrence,
        public float $value,
        public ?float $pay,
        public string $date,
        public string $group,
        public string $id,
    ) {
        //
    }
}
