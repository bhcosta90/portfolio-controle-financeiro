<?php

namespace Core\Application\Charge\Modules\Payment\UseCases\DTO\Payment;

class Input
{
    public function __construct(
        public string $id,
        public float $value,
        public string $date,
        public ?string $bank,
        public ?bool $chargeNext = null,
        public ?string $chargeDateNext = null,
    ) {
        
    }
}
