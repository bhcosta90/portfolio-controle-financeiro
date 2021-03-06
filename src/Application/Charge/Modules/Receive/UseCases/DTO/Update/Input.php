<?php

namespace Core\Application\Charge\Modules\Receive\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string  $id,
        public string  $title,
        public ?string $resume,
        public string  $customer,
        public ?string $recurrence,
        public float   $value,
        public string  $date,
    ) {
        //
    }
}
