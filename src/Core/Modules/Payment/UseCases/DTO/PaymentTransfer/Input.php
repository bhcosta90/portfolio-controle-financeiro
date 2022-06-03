<?php

namespace Costa\Modules\Payment\UseCases\DTO\PaymentTransfer;

class Input
{
    public function __construct(
        public ?string $accountFrom,
        public ?string $accountTo,
        public float $value,
    ) {
        //
    }
}
