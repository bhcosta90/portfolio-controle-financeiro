<?php

namespace Core\Application\Charge\Modules\Payment\Services\DTO\Payment;

class Output
{
    public function __construct(
        public string  $idPayment,
        public ?string $idCharge = null,
    )
    {
        //
    }
}
