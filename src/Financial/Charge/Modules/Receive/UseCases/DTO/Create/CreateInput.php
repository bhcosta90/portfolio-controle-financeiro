<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Create;

use Exception;

class CreateInput
{
    public function __construct(
        public string $groupId,
        public float $value,
        public string $customerId,
        public string $date,
        public int $parcels = 1,
    ) {
        if ($this->parcels < 1) {
            throw new Exception('Input parcel must be greater than 1');
        }
    }
}
