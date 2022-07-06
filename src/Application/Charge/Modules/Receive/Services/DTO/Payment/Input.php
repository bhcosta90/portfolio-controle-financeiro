<?php

namespace Core\Application\Charge\Modules\Receive\Services\DTO\Payment;

class Input
{
    public function __construct(
        public string $id,
        public float $valuePayment,
        public ?string $idAccountBank,
        public ?float $valueCharge = null,
        public ?string $date = null,
    ) {
        //
    }
}
