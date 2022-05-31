<?php

namespace Costa\Modules\Charge\Receive\UseCases\DTO\Payment;

class Output
{
    public function __construct(
        public string $relationship,
        public string $charge,
        public string $date,
        public float $value,
        public ?string $accountFrom,
        public ?string $accountTo,
        public string $id,
        public string $created_at,
        public bool $completed,
    ) {
        //
    }
}
