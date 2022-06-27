<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Pay;

class PayOutput
{
    public function __construct(
        public string $id,
        public float $value,
        public float $pay,
    ) {
        //
    }
}
