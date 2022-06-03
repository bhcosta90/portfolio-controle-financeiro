<?php

namespace Costa\Modules\Recurrence\UseCases\DTO\Create;

class Input
{
    public function __construct(
        public string $name,
        public int $days,
    ) {
        //
    }
}
