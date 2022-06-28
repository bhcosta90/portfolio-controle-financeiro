<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Pay;

use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity;

class PayOutput
{
    public function __construct(
        public string $id,
        public float $value,
        public float $pay,
        public bool $completed,
        public int $status,
        public ?PaymentEntity $charge,

    ) {
        //
    }
}
