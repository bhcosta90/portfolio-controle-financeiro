<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public int|string $id,
        public string $title,
        public ?string $description,
        public float $value,
        public string $customer_id,
        public ?string $recurrence_id,
    ) {

    }
}