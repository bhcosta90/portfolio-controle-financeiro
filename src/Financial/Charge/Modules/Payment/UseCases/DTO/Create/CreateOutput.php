<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Create;

class CreateOutput
{
    public function __construct(
        public string $id,
        public string $groupId,
        public float $value,
        public string $date,
        public string $customerId,
    ) {
        //
    }
}
