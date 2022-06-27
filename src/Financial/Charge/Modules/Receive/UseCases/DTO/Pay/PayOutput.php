<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Pay;

class PayOutput
{
    public function __construct(
        public string $id,
        public float $value,
        public float $pay,
        public bool $completed,
        public int $status,
    ) {
        //
    }
}
