<?php

namespace Core\Application\Charge\Modules\Payment\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public string $title,
        public ?string $resume,
        public string $company,
        public string $companyName,
        public ?string $recurrence,
        public float $value,
        public ?float $pay,
        public string $date,
        public string $group,
        public string $id,
    ) {
        //
    }
}
