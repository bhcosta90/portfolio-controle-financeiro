<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Payment;

class Input
{
    public function __construct(
        public string $id,
        public float $valueCharge,
        public float $valuePay,
        public ?string $dateSchedule,
        public ?string $bank,
        public array $accounts = [],
    ) {
        //
    }
}
