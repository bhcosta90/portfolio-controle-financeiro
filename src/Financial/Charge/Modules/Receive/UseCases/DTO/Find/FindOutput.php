<?php

namespace Core\Financial\Charge\Modules\Receive\UseCases\DTO\Find;

class FindOutput
{
    public function __construct(
        public string $id,
        public float $value,
        public string $date,
        public string $customerId,
        public ?string $recurrenceId,
    ) {
        //
    }
}
