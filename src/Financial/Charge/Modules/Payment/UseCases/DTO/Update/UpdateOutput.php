<?php

namespace Core\Financial\Charge\Modules\Payment\UseCases\DTO\Update;

class UpdateOutput
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
