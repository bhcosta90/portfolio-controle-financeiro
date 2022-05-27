<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Payment;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        //
    }
}
