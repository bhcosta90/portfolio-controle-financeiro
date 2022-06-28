<?php

namespace Core\Financial\Payment\UseCases\DTO\Payment;

class PaymentOutput
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}
