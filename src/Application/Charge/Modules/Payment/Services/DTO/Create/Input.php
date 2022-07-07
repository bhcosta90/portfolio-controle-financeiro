<?php

namespace Core\Application\Charge\Modules\Payment\Services\DTO\Create;

use DomainException;

class Input
{
    public function __construct(
        public string  $tenant,
        public string  $title,
        public ?string $resume,
        public string  $company,
        public ?string $recurrence,
        public float   $value,
        public string  $date,
        public int     $parcel = 1,
    )
    {
        if ($parcel > 96) {
            throw new DomainException('Total installment must be at most 96');
        }

        if ($parcel < 1) {
            throw new DomainException('Total installment must be at least 1');
        }
    }
}
