<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Update;

class UpdateInput
{
    public function __construct(
        public string $id,
        public float $value,
        public string $companyId,
        public string $date,
        public ?string $recurrenceId,
    ) {
        //
    }
}
