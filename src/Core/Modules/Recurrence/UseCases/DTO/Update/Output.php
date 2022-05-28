<?php

namespace Costa\Modules\Recurrence\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public string $id,
        public string $name,
        public int $days,
    ) {

    }
}
