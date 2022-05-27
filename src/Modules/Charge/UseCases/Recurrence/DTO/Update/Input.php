<?php

namespace Costa\Modules\Charge\UseCases\Recurrence\DTO\Update;

class Input
{
    public function __construct(
        public string|int $id,
        public string $name,
        public int $days,
    ) {
        //
    }
}
