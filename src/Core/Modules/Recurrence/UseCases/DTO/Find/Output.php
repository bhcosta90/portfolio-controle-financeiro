<?php

namespace Costa\Modules\Recurrence\UseCases\DTO\Find;

class Output
{
    public function __construct(
        public int|string $id,
        public string $name,
        public int $days,
    ) {

    }
}