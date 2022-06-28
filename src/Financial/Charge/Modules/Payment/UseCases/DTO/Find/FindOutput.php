<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Find;

class FindOutput
{
    public function __construct(
        public string $id,
        public float $value,
        public string $date,
        public string $companyId,
        public ?string $recurrenceId,
    ) {
        //
    }
}
