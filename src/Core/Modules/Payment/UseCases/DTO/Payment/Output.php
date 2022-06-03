<?php

namespace Costa\Modules\Payment\UseCases\DTO\Payment;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}
