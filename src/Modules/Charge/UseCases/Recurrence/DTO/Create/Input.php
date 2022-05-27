<?php

namespace Costa\Modules\Charge\UseCases\Recurrence\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public int $days,
    ) {
        //
    }
}
