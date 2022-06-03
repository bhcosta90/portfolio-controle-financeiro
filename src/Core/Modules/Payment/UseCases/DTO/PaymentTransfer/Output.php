<?php

namespace Costa\Modules\Payment\UseCases\DTO\PaymentTransfer;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}
