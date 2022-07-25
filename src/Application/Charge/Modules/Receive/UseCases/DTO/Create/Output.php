<?php

namespace Core\Application\Charge\Modules\Receive\UseCases\DTO\Create;

use Core\Shared\ValueObjects\ParcelObject;

class Output
{
    public function __construct(
        public string       $title,
        public ?string      $resume,
        public string       $customer,
        public ?string      $recurrence,
        public float        $value,
        public string       $date,
        public string       $group,
        public string       $id,
        public ParcelObject $parcel,
    ) {
        //
    }
}
