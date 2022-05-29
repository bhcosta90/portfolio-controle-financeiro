<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Create;

class Output
{
    public function __construct(
        public int|string $id,
        public string $title,
        public ?string $description,
        public float $value,
        public string $customerId,
    ) {
    }
}
