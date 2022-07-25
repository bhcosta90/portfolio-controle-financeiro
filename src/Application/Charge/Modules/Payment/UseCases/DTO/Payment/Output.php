<?php

namespace Core\Application\Charge\Modules\Payment\UseCases\DTO\Payment;

class Output
{
    public function __construct(
        public bool $success,
        public ?string $charge = null,
    ) {
        //
    }
}
