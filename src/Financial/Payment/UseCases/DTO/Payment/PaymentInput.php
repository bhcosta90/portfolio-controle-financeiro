<?php

namespace Core\Financial\Payment\UseCases\DTO\Payment;

class PaymentInput
{
    public function __construct(
        public string $id,
        public string $value,
        public ?string $accountFromId,
        public ?string $accountToId,
    ) {
        //
    }
}
