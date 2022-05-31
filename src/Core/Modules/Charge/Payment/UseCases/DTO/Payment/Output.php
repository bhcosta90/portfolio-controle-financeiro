<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Payment;

class Output
{
    public function __construct(
        public string $relationship,
        public string $charge,
        public string $date,
        public float $value,
        public array $accountFrom,
        public array $accountTo,
        public string $id,
        public string $createdAt,
    ) {
        //
    }
}
