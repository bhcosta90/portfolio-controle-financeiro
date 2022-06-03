<?php

namespace Costa\Modules\Charge\Payment\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public int|string $id,
        public string $title,
        public ?string $description,
        public float $value,
        public float $pay,
        public string $date,
        public string $supplierId,
        public string $supplierName,
        public ?string $recurrenceId,
    ) {

    }
}
