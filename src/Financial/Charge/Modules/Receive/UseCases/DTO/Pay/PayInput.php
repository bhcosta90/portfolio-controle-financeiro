<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Pay;

class PayInput
{
    public function __construct(
        public string $id,
        public float $value,
        public float $pay,
        public string $date,
        public ?string $bankAccountId = null,
    ) {
        //
    }
}
