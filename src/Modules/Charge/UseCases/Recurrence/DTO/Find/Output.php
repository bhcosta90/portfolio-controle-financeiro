<?php

namespace Costa\Modules\Charge\UseCases\Recurrence\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public int $days,
        public ?string $id = null,
    ) {
    }
}
