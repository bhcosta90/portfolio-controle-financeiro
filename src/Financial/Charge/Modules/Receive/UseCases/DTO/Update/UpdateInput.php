<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Update;

class UpdateInput
{
    public function __construct(
        public string $id,
        public float $value,
        public string $customerId,
        public string $date,
        public ?string $recurrenceId,
    ) {
        //
    }
}
