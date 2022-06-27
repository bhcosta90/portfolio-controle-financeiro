<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Update;

class UpdateOutput
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
