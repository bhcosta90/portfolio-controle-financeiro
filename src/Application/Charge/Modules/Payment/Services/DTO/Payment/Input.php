<?php

namespace Core\Application\Charge\Modules\Payment\Services\DTO\Payment;

class Input
{
    public function __construct(
        public string  $id,
        public ?float  $valuePayment,
        public bool    $newPayment,
        public ?string $dateNewPayment,
        public ?string $idAccountBank,
        public ?string $date = null,
    )
    {
        //
    }
}
